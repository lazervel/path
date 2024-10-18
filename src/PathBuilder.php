<?php

declare(strict_types=1);

namespace Path;

use Path\Exception\RTException;
use Path\Util\PathHelper;

abstract class PathBuilder extends PathHelper
{
  private const MULTI_SEP = '/(?:^([\\\\]{2})(?=[^\\\\]+[\\\\]+[^\\\\]+[\\\\]?)|([\\\\])[\\\\]*)/';
  private const CUR_DIR = '/^[.][\\\\]|[\\\\][.](?=[\\\\]|$)/';

  public const sep = \DIRECTORY_SEPARATOR;
  public const parent = '..';
  public const current = '.';
  public const BSEP = '\\';
  public const FSEP = '/';
  public const delimiter = ':';
  public const UNC = '\\UNC\\';
  public const namespace = '\\\\?';
  
  protected const SELF_CURDIR = '/^(\.[\\\\\/]?)[\\\\\/]*$/';
  private const ROOT = '/^(?:(?:[\\\\]{2})[^\\\\]+[\\\\]+[^\\\\]+[\\\\]?|(?:[A-Z]:)*[\\\\]?)|/';

  /**
   * Perform a regular expression search and replace with convert subject multiple consecutive
   * slashes (both forward '/' and backward '\'). to single slash
   * 
   * @param string|string[] $pattern     [required]
   * @param string|string[] $replacement [required]
   * @param string|string[] $subject     [required]
   * 
   * @return mixed
   */
  private static function replace($pattern, $replacement, $subject)
  {
    return \preg_replace($pattern, $replacement, \str_replace([self::FSEP, self::BSEP], self::sep, $subject));
  }

  /**
   * Returns a Regular-Expression string It's used to verify path drive.
   * 
   * @param string $source [required]
   * @return string A Regular-Expression
   */
  private static function makeRegex(string $source)
  {
    return \sprintf('/^%s/', \str_replace(self::sep, '\\\\', $source));
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return stdClass
   */
  public static function pathInfo(string $path)
  {
    return (object) \pathinfo($path);
  }

  /**
   * 
   * @param string|string[] $paths [required]
   * @return string|false
   */
  public static function drivename($paths)
  {
    $output = false;
    foreach((array) $paths as $path) {
      if (($drive = self::rootname($path)) && $drive !== self::sep) {
        $output = self::suffix($drive, self::sep);
      }
    }
    return $output;
  }

  /**
   * Returns resolved path, To arranged final resolvement parts.
   * 
   * @param array   $resolved [required]
   * @param string  $root     [required]
   * 
   * @return string resolved string
   */
  private static function resolved(array $resolved, string $root)
  {
    $isRoot = $root != null;
    $isRoot && \array_unshift($resolved, $root);
    return self::suffix(self::serialize($resolved), self::sep);
  }

  /**
   * 
   * 
   * @param string $target  [required]
   * @param string $matcher [required]
   * 
   * @return bool verified status
   */
  private static function verify(string $target, string $matcher) : bool
  {
    $mDrive = self::drivename($matcher);
    $tDrive = self::drivename($target);

    if ($mDrive && self::isNetwork($mDrive)) {
      return false;
    }

    return ($tDrive && $mDrive && self::match(self::makeRegex($tDrive), $mDrive)) || !$mDrive;
  }

  /**
   * Check if a given file path is an network path.
   * 
   * @param string $path [required]
   * @return bool
   */
  public static function isNetwork(string $path) : bool
  {
    return !!(self::match(self::MULTI_SEP, $path, true)[1] ?? false);
  }

  /**
   * 
   * 
   * - Remove all multiple consecutive slashes '/' and '\' rm redundant ones.
   * - Not remove occurrences of './', which indicates the current directory.
   * - Replace all '/' and '\' slashes in path with a single separator slash.
   * 
   * @param string[] $path [required]
   * @return string
   */
  public static function stripe(array $path) : string
  {
    return self::replace(self::MULTI_SEP, '$1$2$3', \join(self::sep, $path));
  }

  /**
   * Returns system root directory name of path.
   * 
   * @param string $path [required]
   * @return string
   */
  public static function rootname(string $path) : string
  {
    return self::match(self::ROOT, $path, true)[0];
  }

  /**
   * Returns prefixed data to trim ends separator, again set prefix separator
   * 
   * @param string $data [required]
   * @return string prefixed data
   */
  private static function setPrefix(string $data) : string
  {
    return $data != null ? self::suffix($data, self::sep).self::sep : $data;
  }

  /**
   * 
   * 
   * - Remove all multiple consecutive slashes '/' and '\' rm redundant ones.
   * - Remove all occurrences of './', which indicates the current directory.
   * - Replace all '/' and '\' slashes in path with a single separator slash.
   * 
   * @param string $path [required]
   * @return string
   */
  public static function sanitize(string $path) : string
  {
    return self::replace([self::MULTI_SEP, self::CUR_DIR], ['$1$2$3', ''], $path);
  }

  /**
   * 
   * 
   * - Remove all multiple consecutive slashes '/' and '\' rm redundant ones.
   * - Remove all occurrences of './', which indicates the current directory.
   * - Replace all '/' and '\' slashes in path with a single separator slash.
   * 
   * @param string|string[] $paths [required]
   * @param string|null     $sep   [optional]
   * 
   * @return string
   * @throws RTException if seprator are invalid
   */
  public static function separate($paths, ?string $sep = null) : string
  {
    if ($sep && !($sep === self::FSEP || $sep === self::BSEP)) {
      throw new RTException(sprintf('Cannot separate Invalid separator [%s]', $sep));
    }

    $sep  = $sep ?? self::sep;
    $path = self::serialize((array) $paths);
    return $sep !== self::sep ? \str_replace(self::sep, $sep, $path) : $path;
  }

  /**
   * Perform a regular expression match with convert subject multiple consecutive
   * slashes (both forward '/' and backward '\'). to single slash
   * 
   * @param string $pattern       [required]
   * @param string $subject       [required]
   * @param bool   $returnMatches [optional]
   * 
   * @return array|false
   */
  private static function match(string $pattern, string $subject, bool $returnMatches = false)
  {
    $isMatched = \preg_match($pattern, self::sanitize($subject), $matches);
    return $returnMatches ? $matches : $isMatched;
  }

  /**
   * 
   * 
   * - Remove all multiple consecutive slashes '/' and '\' rm redundant ones.
   * - Remove all occurrences of './', which indicates the current directory.
   * - Replace all '/' and '\' slashes in path with a single separator slash.
   * 
   * @param iterable $paths [required]
   * @param bool     $isMap [optional]
   * 
   * @return string|array
   */
  public static function serialize(iterable $paths, bool $isMap = false)
  {
    return $isMap ?
      \array_map([self::class, 'sanitize'], $paths, [$isMap]) : self::sanitize(\join(self::sep, $paths));
  }

  /**
   * 
   * 
   * @param array $paths [required]
   * @return string resolved string
   */
  protected static function makeResolver(array $paths) : string
  {
    $root = self::drivename($paths);
    $i = \count($paths) - 1;
    $resolved = [];
    /**
     * 
     * @param string      $path
     * @param string|null $sep
     * 
     * @return string
     */
    $modify = function($path, $root, ?string $sep = null) {
      return $sep.\substr($path, \strlen($root));
    };
    /**
     * 
     * @param string $root
     * @param string $path
     * 
     * @return void|true
     */
    $isStopped = function($root, $path) {
      if (($path && $path[0] === self::BSEP)) {
        return true;
      }
    };

    for(; $i >= 0; $i--) {
      $path = $paths[$i];
      if (self::verify($root, $path)) {
        self::drivename($path) && ($path = $modify($path, $root));
      }
      elseif (self::isNetwork($root)) {
        $path = $modify($path, $root, self::sep);
      } else {
        continue;
      }

      self::prepend($resolved, $path);
      if ($isStopped($root, $path)) {
        break;
      }
    }

    return self::resolved($resolved, $root);
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string
   */
  public static function toNamespcedPath(string $path) : string
  {
    return self::namespace
      .\preg_replace_callback(self::MULTI_SEP, function($matched) {
      return $matched[1] ? self::UNC : $matched[3] ?? $matched[2];
    }, $path);
  }

  /**
   * Normalize a string path, reducing '..' and '.' parts. When multiple slashes are found,
   * they're replaced by a single one; when the path contains a trailing slash,
   * it is preserved. On Windows backslashes are used.
   * 
   * @param string $root [required]
   * @param string $path [required]
   * 
   * @return string normalized path
   */
  protected static function makeNormalizer(string $root, string $path) : string
  {
    $path = $path==null ? self::current : $path;
    $files = \explode(self::sep, $path);
    // TODO: no need prefix in Path::resolve(...) Fix bugs of specific version
    $root  = self::setPrefix($root);
    $normalized = [];
    $upDirs     = [];
    $endPart    = @end($files);
    $hasContains = function(array $array) {
      foreach($array as $data) {
        if ($data != null) {
          return true;
        }
      }
      return false;
    };

    foreach($files as $file) {
      $file === self::parent ?
        (self::remove($normalized) == null && self::append($upDirs, $file)) :
          self::append($normalized, $file);
    }

    if ($root) {
      self::prepend($normalized, $root);
    } else {
      self::hasLength($upDirs) ? self::prepend($normalized, $upDirs) :
        !$hasContains($normalized) && $endPart == null && self::prepend($normalized, self::current);
    }

    return self::stripe($normalized);
  }

  /**
   * Returns an suffixed data, Triming right side data matched to suffix
   * value but do not trim [data] if [data] and suffix value are equal.
   * 
   * @param string $data   [required]
   * @param string $suffix [required]
   * 
   * @return string suffixed-data string
   */
  protected static function suffix(string $data, string $suffix) : string
  {
    return $data !== $suffix && \mb_substr($data, - \mb_strlen($suffix)) === $suffix ? \mb_substr($data, 0, - \mb_strlen($suffix)) : $data;
  }
}
?>