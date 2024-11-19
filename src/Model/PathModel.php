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

use Path\Exception\RTException;
use Path\Utils\PathUtils;
use Path\Win32\Win32;
use Url\Url;

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
   * Returns information about a file path.
   * 
   * @param string $path  [required]
   * @param int    $flags [optional]
   * 
   * @return object Returns information about a file path.
   */
  public static function info(string $path, int $flags = \PATHINFO_ALL) : object
  {
    return (object) \array_merge(\pathinfo($path, $flags), ['root' => self::rootname($path)]);
  }

  /**
   * Returns tmp name, To make a tmp name with dirname of given path
   * 
   * @param string $path [required]
   * @return string tmp string attached with dirname.
   */
  public static function tmp(string $path) : string
  {
    return \dirname($path).self::sep.'.!!'.self::sep.'.!'.\strrev(
      \strtr(\base64_encode(\random_bytes(3)), '/=', '-!')
    );
  }

  /**
   * Returns an object from a path string - the opposite of format().
   * 
   * @param string $path [required]
   * @return string[] Returns information about a file path.
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
   * Check a valid path length and report exception.
   * 
   * @param string $path [required]
   * 
   * @throws \Path\Path\Exception\RTException
   * @return void
   */
  public static function checkLength(string $path) : void
  {
    \strlen($path) > \PHP_MAXPATHLEN && throw new RTException(
      \sprintf('Invalid path because path length exceeds [%d] characters.', \PHP_MAXPATHLEN)
    );
  }

  /**
   * Returns a filename without extention of given path
   * 
   * @param string $path [required]
   * @return string without extension filename
   */
  public static function filename(string $path) : string
  {
    return self::info($path)->filename;
  }

  /**
   * Determines whether {path} is an absolute path.
   * An absolute path will always resolve to the same location,
   * regardless of the working directory.
   * 
   * @param string $path [required]
   * @return bool absolute for true otherwise false return
   */
  public static function isAbsolute(string $path) : bool
  {
    $matched = self::rootname($path);
    return !!$matched && !\str_ends_with($matched, ':');
  }

  /**
   * isLocal function checks whether the provided path is local or not.
   * It determines if the given path refers to a local file or directory.
   * 
   * @param string $path [required]
   * @return bool  Returns true if the path is local, false otherwise.
   */
  public static function isLocal(string $path) : bool
  {
    $absPath = self::real($path);
    return $absPath && Win32::isAbsolute($absPath);
  }

  /**
   * optimize - method working As Path::format() but this method in common different,
   * This method will be convert backward slashes to forward slash
   * 
   * Normalize a string path, reducing '..' and '.' parts. When multiple slashes are found,
   * they're replaced by a single one; when the path contains a trailing slash,
   * it is preserved. On Windows backslashes are used.
   * 
   * @param string $path [required]
   * @return string
   */
  public static function optimize(string $path) : string
  {
    return self::doNormalize($path, true);
  }

  /**
   * Short hand apply custom methods of Path, with array Arguments. 
   * 
   * @param callable $method [required]
   * @param string[] $args   [required]
   */
  public static function apply($method, array $args)
  {
    $fn = !\is_callable($method) ?  [self::class, $method] : $method;
    return \call_user_func_array($fn, $args);
  }

  /**
   * canonicalize function converts a given path into its canonical (absolute and standardized) form.
   * It resolves relative paths, symbolic links, and eliminates redundant or unnecessary components
   * like '.' and '..' to return a clean and absolute version of the path.
   * 
   * @param string $path [required]
   * @return string|false Returns the canonical (absolute) path.
   */
  public static function canonicalize(string $path)
  {
    $absPath = self::normalize($path);
    return self::isLocal($absPath) ? self::real($absPath) : false;
  }

  /**
   * Returns extname method a path extension name from given path, Its used to get file ext
   * 
   * @param string $path [required]
   * @return string
   */
  public static function extname(string $path) : string
  {
    $src = \explode('.', self::basename($path));
    return \count($src) > 1 ? ('.'.@end($src)) : '';
  }

  /**
   * Normalize a string path, reducing '..' and '.' parts. When multiple slashes are found,
   * they're replaced by a single one; when the path contains a trailing slash,
   * it is preserved. On Windows backslashes are used.
   * 
   * @param string $path [required]
   * @return string Returns normalized path string.
   */
  public static function normalize(string $path) : string
  {
    return self::doNormalize($path);
  }

  /**
   * Join all arguments together and normalize the resulting path.
   * 
   * @param string ...$paths [required]
   * @return string Returns joined segments path.
   */
  public static function join(string ...$paths) : string
  {
    return self::normalize(\join(self::sep, $paths));
  }

  /**
   * hasExt method will check extension exists or not exists of given path and matcher
   * Extensions, if Given extensions in matched path extension then return true,
   * Otherwise return false
   * 
   * @param string          $path       [required]
   * @param string|string[] $extensions [required]
   * @param bool            $caseI      [optional] case-insensitive
   * @return string Return true if extension exists, Otherwise false
   */
  public static function hasExt(string $path, $extensions, bool $caseI = false) : bool
  {
    // Extracting extension from given path with change case according to $caseI
    $pathExtension = self::extname($path);
    $pathExtension = $caseI ? \strtolower($pathExtension) : $pathExtension;

    foreach((array) $extensions as $extension) {
      $modExtension = $caseI ? \strtolower($extension) : $extension;
      if ($pathExtension === $modExtension || $pathExtension === ".$modExtension") {
        return true;
      }
    }
    return false;
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
   * @return string Returns all resovled segements path
   */
  public static function resolve(string ...$paths) : string
  {
    return self::normalize(self::doResolve($paths));
  }

  /**
   * Returns a path string with removed path extension.
   * 
   * @param string $path [required]
   * @return string Modified path string with removed extension
   */
  public static function removeExt(string $path) : string
  {
    return self::suffix($path, self::extname($path), true);
  }

  /**
   * Returns canonicalized absolute pathname
   * 
   * @param string $path [required]
   * @return string|false absolute pathname
   */
  public static function real(string $path)
  {
    return \realpath($path);
  }

  /**
   * 
   * @param string[] $paths [required]
   * @return string
   */
  public static function localBase(array $paths) : string
  {
    
  }

  /**
   * Returns a path string with changed initial extension to replaced new extension.
   * If path extension and givent new extension are same then changeExt
   * will be not modify and return path with initial extension.
   * 
   * @param string $path   [required]
   * @param string $newExt [required]
   * @return string Modified path with changed extension
   */
  public static function changeExt(string $path, $newExt) : string
  {
    if (!self::hasExt($path, self::extname($path))) {
      return $path;
    }

    return self::removeExt($path) . self::prefix('.', $newExt);
  }

  /**
   * Creates an array by using one array for paths and another for its names
   * And creates multiple files combined with paths from to file names.
   * 
   * @param array $paths [required]
   * @param array $names [required]
   * 
   * @return array Combined and concate with new files.
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
   * Solve the relative path from {from} to {to} based on the current working directory.
   * At times we have two absolute paths, and we need to derive the relative path from
   * one to the other. This is actually the reverse transform of path.resolve.
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
   * Returns trailing name component of path
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
   * @param string|string[] $group [required]
   * @param string|string[] $paths [required]
   */
  public static function group($group, $paths)
  {

  }

  /**
   * To convert url 'https://example.com/home/parent/current/path' to '/home/parent/current/path'
   * 
   * @param string $url [required]
   * @return string Converted URL string to Path format
   */
  public static function UrlToPath(string $url) : string
  {
    return self::resolve((new Url($url))->pathname);
  }

  /**
   * On Windows systems only, returns an equivalent namespace-prefixed path for the given path.
   * If path is not a string, path will be returned without modifications.
   * This method is meaningful only on Windows system. On POSIX systems,
   * the method is non-operational and always returns path without modifications.
   * 
   * @param string $path [required]
   * @return string
   */
  public static function toNamespcedPath(string $path) : string
  {
    // Skip converting namespaced path returns simple path.
    if (self::$isPosix) {
      return $path;
    }

    $path = self::resolve($path);
    $rel = '\\\\?\\';
    return self::isURIPath($path) ? \str_replace(self::regsep, $rel.'UNC\\', $path) : $rel.$path;
  }

  /**
   * Returns a path string from an array-object - the opposite of parse().
   * format method are same work as `phpinfo` method But there install the
   * extra property `root` property to getting current root [dir] of path
   * 
   * @param array $pathObject [required]
   * @return string formated path string
   */
  public static function format(array $pathObject) : string
  {
    return self::_join([
      $pathObject['dir'] ?? $pathObject['root'] ?? '',
      $pathObject['base'] ?? (($pathObject['name'] ?? '').($pathObject['ext'] ?? ''))
    ], '');
  }

  /**
   * pathToURL - Convert path to url see example line: 422, 425
   * Returns path to url combination with (e.g., path, origin, ?query, ?hash)
   * 
   * https://example.com/home/local/foo
   * https://example.com/home/local/foo?uid=100002374736
   * https://example.com/home/local/foo#urlHash
   * https://example.com/home/local/foo?uid=100002374736#urlHash
   * 
   * @param string $path   [required]
   * @param string $origin [required]
   * @param string $query  [optional]
   * @param string $hash   [optional]
   * 
   * @return string Converted path string to URL format.
   */
  public static function pathToURL(string $path, string $origin, ?string $query = '', ?string $hash = '') : string
  {
    return self::suffix($origin, '/').'/'.self::posix::optimize(self::prefix('/', $path, false)).self::prefix('?', $query).self::prefix('#', $hash);
  }
}
?>