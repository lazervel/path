<?php

declare(strict_types=1);

namespace Path\Util;

trait PathHelper
{
  /**
   * 
   * @param string &$array
   * @param mixed  $values
   * 
   * @return int
   */
  protected static function append(array &$array, $values)
  {
    return self::insert($array, $values, false);
  }

  /**
   * 
   * @param array $array
   * @return mixed
   */
  protected static function remove(array &$array)
  {
    return \array_pop($array);
  }

  /**
   * 
   * 
   * @param array $array
   * @return bool
   */
  protected static function hasLength(array $array) : bool
  {
    return \count($array) > 0;
  }

  /**
   * 
   * @param string &$array
   * @param mixed  $values
   * 
   * @return int
   */
  protected static function prepend(array &$array, $values)
  {
    return self::insert($array, $values, true);
  }

  /**
   * 
   * 
   * @param array &$array
   * @param mixed $values
   * @param bool  $isPrepend
   * 
   * @return int
   */
  private static function insert(array &$array, $values, $isPrepend)
  {
    $values = (array) $values;
    foreach($values as $value) {
      !!$isPrepend ? \array_unshift($array, $value) : \array_push($array, $value);
    }
  }
}
?>