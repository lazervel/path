<?php

declare(strict_types=1);

/**
 * The PHP Path provides utlities help with handling or manipulating file and directory path.
 * 
 * The (path) Github repository
 * @see       https://github.com/lazervel/path
 * 
 * @author    Shahzada Modassir <shahzadamodassir@gmail.com>
 * @author    Shahzadi Afsara   <shahzadiafsara@gmail.com>
 * 
 * @copyright (c) Shahzada Modassir
 * @copyright (c) Shahzadi Afsara
 * 
 * @license   MIT License
 * @see       https://github.com/lazervel/path/blob/main/LICENSE
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Path\Model;

use Path\Utils\PathUtils;

/**
 * @uses PathUtils
 * 
 * PathModel trait: Provides methods and utilities related to paths
 * to simplify path handling in classes.
 * 
 * @author Shahzadi Afsara   <shahzadiafsara@gmail.com>
 * @author Shahzada Modassir <shahzadamodassir@gmail.com>
 */
trait PathModel
{
  /**
   * PathUtils Trait
   * Using PathUtils trait to provides and access some path-related methods and functionalities.
   */
  use PathUtils;

  /**
   * 
   * 
   * @param string $from [required]
   * @param string $to   [required]
   * 
   * @return string
   */
  public static function relative(string $from, string $to) : string
  {
    // Create arrayable paths after resolving {from} and {to} path.
    $from = \explode(self::sep, self::resolve($from));
    $to   = \explode(self::sep, self::resolve($to));

    // Go through the array, to matches the common path
    while(\count($from) && \count($to) && $from[0] === $to[0]) {
      \array_shift($from);
      \array_shift($to);
    }
    
    // Create the relative path
    $relative = \str_repeat(self::$parent.self::sep, \count($from)).self::_join($to);
    return self::normalize($relative);
  }

  /**
   * 
   * @param string $path [required]
   * @return string
   */
  public static function normalize(string $path) : string
  {
    return self::doNormalize($path);
  }

  /**
   * 
   * @param string ...$paths [required]
   * @return string
   */
  public static function join(string ...$paths) : string
  {
    return self::normalize(\join(self::sep, $paths));
  }

  /**
   * 
   * @param string ...$paths [required]
   * @return string
   */
  public static function resolve(string ...$paths) : string
  {
    return self::normalize(self::doResolve($paths));
  }
}
?>