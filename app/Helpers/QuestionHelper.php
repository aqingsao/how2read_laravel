<?php

class QuestionHelper {

    public static function from_type($type)
    {
      if ($type == 1)
        return '官方作者';
      if ($type == 2)
        return '维基百科';
      if ($type == 3)
        return '标准读音';
      return '其他';
    }
}

?>