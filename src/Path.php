<?php

declare(strict_types=1);

namespace Path;

use Path\PathBuilder;

class Path
{
  public const BSEP = PathBuilder::BSEP;
  public const FSEP = PathBuilder::FSEP;

  /**
   * Returns a accurrate pathname, exclude with rootname Its used to extract [local] pathname.
   * But not reducing '..' and '.' parts they're not replaced by a single one; when the path
   * contains a trailing slash.
   * 
   * @param string $path [required]
   * @return string local pathname
   */
  public static function pathname(string $path) : string
  {
    return PathBuilder::pathname($path);
  }

  /**
   * On Windows systems only, returns an equivalent namespace-prefixed path for the given path.
   * If path is not a string, path will be returned without modifications.
   * This method is meaningful only on Windows system. On POSIX systems,
   * the method is non-operational and always returns path without modifications.
   * 
   * @param string $path [required]
   * @return string namespaced-path
   */
  public static function toNamespcedPath(string $path) : string
  {

  }

  /**
   * Join all arguments together and normalize the resulting path string.
   * 
   * @param array<string|null> $paths [required]
   * @return string joined path string
   */
  public static function join(string ...$paths) : string
  {
    return self::normalize(self::separate($paths));
  }

  /**
   * Return the directory name of a path. Similar to the Unix dirname command.
   * On Windows, both slash (/) and backslash (\) are used as directory
   * separator character. In other environments, it is the forward slash (/).
   * 
   * @param string $path   [required]
   * @param int    $levels [optional]
   * @param string $suffix [optional]
   * @param string $prefix [optional]
   * 
   * @return string dirname
   */
  public static function dirname(string $path, int $levels = 1, string $suffix = '') : string
  {
    return PathBuilder::suffix(\dirname($path, $levels), $suffix);
  }

  /**
   * Returns clean path or string, to replace multi slashes to single (/)
   * slashes. And remove all current directory (./) seperator But do not
   * Remove current directory (^./) seperator of first index of path
   * 
   * @param string $path [required]
   * @return string clean path
   */
  public static function clean(string $path, ?string $sep=null) : string
  {
    return PathBuilder::clean($path, $sep);
  }

  /**
   * Normalize a string path, reducing '..' and '.' parts. When multiple slashes are found,
   * they're replaced by a single one; when the path contains a trailing slash,
   * it is preserved. On Windows backslashes are used.
   * 
   * @param string $path [required]
   * @return string normalized path
   */
  public static function normalize(string $path) : string
  {
    return PathBuilder::normalize(self::separate($path));
  }

  /**
   * Returns a path string from an array-object - the opposite of parse().
   * format method are same work as `phpinfo` method But there install the
   * extra property `root` property to getting current root [dir] of path
   * 
   * @param string $path [required]
   * @return array format LIKE (root, dir, base, ext, name)
   */
  public static function format(array $path) : string
  {

  }

  /**
   * Returns extname method a path extension name from given path, Its used to get file ext
   * 
   * @param string $path [required]
   * @return string extname
   */
  public static function extname(string $path) : string
  {
    return PathBuilder::fileExt(self::basename($path), 2);
  }

  /**
   * Solve the relative path from {from} to {to} based on the current working directory.
   * At times we have two absolute paths, and we need to derive the relative path from
   * one to the other. This is actually the reverse transform of path.resolve.
   * 
   * @param string $from [required]
   * @param string $to   [required]
   * 
   * @return string relative-path
   */
  public static function relative(string $path) : string
  {

  }

  /**
   * Returns a [root] directory LIKE `(eg: C:/ C: / //root/dir/)` extract
   * from path,[root] directory repersent a System or Server active drive
   * 
   * @param string $path [required]
   * @return string rootname
   */
  public static function rootname(string $path) : string
  {
    return PathBuilder::rootname($path);
  }

  /**
   * Determines whether {path} is an absolute path. An absolute path will always resolve
   * to the same location, regardless of the working directory.
   * If the given {path} is a zero-length string, false will be returned.
   * 
   * @param string $path [required]
   * @return boolean true/false
   */
  public static function isAbsolute(string $path) : bool
  {

  }

  /**
   * Returns filename method an filename name from given path, Its used to get filename.
   * 
   * @param string $path [required]
   * @return string filename
   */
  public static function filename(string $path) : string
  {
    return PathBuilder::fileExt(self::basename($path), 1);
  }

  /**
   * Returns an array-object from a path string - the opposite of format().
   * parse method are same work as `phpinfo` method But there install the
   * extra property `root` property to getting current root [dir] of path
   * 
   * @param string $path [required]
   * @return array format LIKE (root, dir, base, ext, name)
   */
  public static function parse(string $path) : string
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
   * Returns seprated path string with seperator LIKE (\\) and (/) of single or multiple path
   * And remove all current directory (./) seperator But do not Remove current directory
   * (^./) seperator of first index of path
   * 
   * @param string|array $paths [required]
   * @param string       $sep   [optional]
   * 
   * @return string separate path with seperator
   */
  public static function separate($paths, string $sep=self::BSEP) : string
  {
    return PathBuilder::separate($paths, $sep);
  }

  /**
   * Returns trailing name component of path basename, Its used to find, read and write files.
   * Return the last portion of a path. Similar to the Unix basename command.
   * Often used to extract the file name from a fully qualified path.
   * 
   * @param string $path   [required]
   * @param string $suffix [optional]
   * 
   * @return string basename
   */
  public static function basename(string $path, string $suffix = '') : string
  {
    return \file_exists('basename') ? \basename($path, $suffix) : \pathinfo($path)['basename'];
  }

  /**
   * The right-most parameter is considered {to}. Other parameters are considered an array of {from}.
   * Starting from leftmost {from} parameter, resolves {to} to an absolute path.
   * If {to} isn't already absolute, {from} arguments are prepended in right to left order, until an
   * absolute path is found. If after using all {from} paths still no absolute path is found,
   * the current working directory is used as well. The resulting path is normalized, and trailing
   * slashes are removed unless the path gets resolved to the root directory.
   * 
   * @param string ...$paths [required]
   * @return string resolved-path
   */
  public static function resolve(string ...$paths) : string
  {
    return self::normalize(PathBuilder::getCompleteSource($paths, self::pathname(\getcwd())));
  }
}
?>