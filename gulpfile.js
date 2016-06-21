var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var cleancss = require('gulp-cleancss');
var sass = require('gulp-sass');
var _ = require('underscore');
var del = require('del');
var vinylPaths = require('vinyl-paths');

var all_js = [
  {
    name: 'issue.min.js',
    sources: [
      'resources/assets/js/module-services.js',
      'resources/assets/js/app.js',
      'resources/assets/js/issue.js',
      'resources/assets/js/question-add-ctrl.js'
    ],
    dist: 'public/static/js/'
  }];

var all_css = [
  {
    name: 'issue.min.css',
    sources: [
      'resources/assets/sass/common.scss',
      'resources/assets/sass/iconfont.scss',
      'resources/assets/sass/issue.scss',
      'resources/assets/sass/question-add.scss'
    ],
    dist: 'public/static/css/'
  }
];

var js_tasks = [];
var css_tasks = [];

for (var i = 0; i < all_js.length; i++) {
  var name = all_js[i].name;
  var sources = all_js[i].sources;
  var dist = all_js[i].dist;
  var task_name = function (name, sources, dist) {
    gulp.task(name, function () {
      gulp.src(sources)
        .pipe(concat(name))
        .pipe(uglify({mangle: false}))
        .pipe(gulp.dest(dist));
    });
    return name;
  }(name, sources, dist);
  console.log('add task ' + task_name);
  js_tasks.push(task_name);
}

for (var i = 0; i < all_css.length; i++) {
  var name = all_css[i].name;
  var sources = all_css[i].sources;
  var dist = all_css[i].dist;
  var task_name = function (name, sources, dist) {
    gulp.task(name, function () {
      gulp.src(sources)
        .pipe(sass())
        .pipe(concat(name))
        .pipe(cleancss({keepBreaks: false}))
        .pipe(gulp.dest(dist));
    });
    return name;
  }(name, sources, dist);
  css_tasks.push(task_name);
}

var tasks = _.union(_.map(all_css, function (file) {
  return file.name;
}), _.map(all_js, function (file) {
  return file.name;
}));
gulp.task('default', tasks, function () {
  console.log("Default donef.");
});

gulp.task('dev', tasks, function () {
  for (var i = 0; i < all_js.length; i++) {
    gulp.watch(all_js[i].sources, [all_js[i].name]);
  }

  for (var i = 0; i < all_css.length; i++) {
    gulp.watch(all_css[i].sources, [all_css[i].name]);
  }
});

//clean 任务单独执行，一般用不到
gulp.task('clean', function () {
  gulp.src(_.map(all_css, function (file) {
    return file.dist + file.name;
  })).pipe(vinylPaths(del));
  gulp.src(_.map(all_js, function (file) {
    return file.dist + file.name;
  })).pipe(vinylPaths(del));
});