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
   * @param string $path  [required]
   * @param int    $flags [optional]
   * 
   * @return object Returns information about a file path.
   */
  public static function info(string $path, int $flags = \PATHINFO_ALL) : object
  {
    return (object) \array_merge(\pathinfo($path, $flags), ['root' => self::root($path)]);
  }

  /**
   * 
   * @param string $name [required]
   * @return string
   */
  public static function tmp(string $name) : string
  {
    return $name.'\.!!'.'/.!'.\strrev(
      \strtr(\base64_encode(\random_bytes(3)), '/=', '-!')
    );
  }

  /**
   * 
   * @param string $path [required]
   * @return string[]
   */
  public static function parse(string $path) : array
  {
    return [
      'root' => self::rootname($path),
      'dir'  => self::dirname($path),
      'base' => self::basename($path),
      'ext'  => self::extname($path),
      'name' => self::filename($path)
    ];
  }

  /**
   * 
   * @param string $path [required]
   * @return string
   */
  public static function filename(string $path) : string
  {
    return self::info($path)->filename();
  }

  /**
   * 
   * @param string $path [required]
   * @return bool
   */
  public static function isAbsolute(string $path) : bool
  {
    $matched = self::rootname($path);
    return !!$matched && !\str_ends_with($matched, ':');
  }

  /**
   *
   * @param string $path [required]
   * @return bool True if the path is local, false otherwise.
   */
  public static function isLocal(string $path) : bool
  {
    $absPath = \realpath($path);
    return $absPath && self::isAbsolute($absPath);
  }

  /**
   * 
   * @param string $path [required]
   * @return string
   */
  public static function optimize(string $path) : string
  {
    return self::doNormalize($path, true);
  }

  /**
   * 
   * @param string $path [required]
   * @return string|false
   */
  public static function canonicalize(string $path)
  {
    $absPath = self::resolve($path);
    return self::isLocal($absPath) ? $absPath : false;
  }

  /**
   * 
   * @param string $path [required]
   * @return string
   */
  public static function extname(string $path) : string
  {
    return self::info($path)->extension();
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

  /**
   * 
   * @param array $paths [required]
   * @param array $names [required]
   * 
   * @return array
   */
  public static function combine(array $paths, array $names) : array
  {
    $files = [];

    // Returns $names without combined if $paths are empty!
    if (\count($paths) === 0) {
      return $names;
    }

    $paths = \array_unique($paths);
    $names = \array_unique($names);

    foreach($paths as $path) {
      foreach($names as $name) {
        $files[] = self::clean(self::_join([$path, $name]), true);
      }
    }
    return $files;
  }

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
   * @return string Returns trailing name component of path.
   */
  public static function basename(string $path, string $suffix = '') : string
  {
    return \basename($path, $suffix);
  }

  /**
   * 
   * @param array $pathObject [required]
   * @return string
   */
  public static function format(array $pathObject) : string
  {
    return self::_join([
      $pathObject['dir'] ?? $pathObject['root'] ?? '',
      $pathObject['base'] ?? (($pathObject['name'] ?? '').($pathObject['ext'] ?? ''))
    ], '');
  }
}
?>