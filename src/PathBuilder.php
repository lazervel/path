<?php

declare(strict_types=1);

namespace Path;

abstract class PathBuilder
{
  protected const ROOT = '/^(?:(?:[\\\\]{2})[^\\\\]+[\\\\]+[^\\\\]+[\\\\]?|(?:[A-Z]:)*[\\\\]?)|/';

  public const namespace = '\\\\?';
  public const parent = '..';
  public const current = '.';
  public const BSEP = '\\';
  public const FSEP = '/';
  public const delimiter = ':';
  public const UNC = '\\UNC\\';
  public const sep = self::BSEP;
  
  private const CUR_DIR = '/(?:^(\.[\\\\]))|(?:([^.])\.([\\\\]))/';
  private const MULTI_SEP = '/(?:^([\\\\]{2})(?=[^\\\\]+[\\\\]+[^\\\\]+[\\\\]?)|([\\\\])[\\\\]*)/';

  private static function match(string $pattern, string $subject, bool $returnMatches = false)
  {
    $isMatched = \preg_match($pattern, self::nuturalize($subject), $matches);
    return $returnMatches ? $matches : $isMatched;
  }

  private static function replace($pattern, $replacement, $subject)
  {
    return \preg_replace($pattern, $replacement, self::nuturalize($subject));
  }

  private static function nuturalize(string $path)
  {
    return \str_replace(self::FSEP, self::BSEP, $path);
  }

  public static function rootname(string $path) : string
  {
    return self::match(self::ROOT, $path, true)[0];
  }

  public static function separate($paths, ?string $sep = null)
  {
    $path = self::serialize((array) $paths, true);
    $sep = $sep == null ? self::BSEP : $sep;
    return $sep !== self::BSEP ? \str_replace(self::BSEP, $sep, $path) : $path;
  }

  
  public static function serialize(array $paths, bool $isClean = false)
  {
    return self::sanitize(\join(self::BSEP, $paths), $isClean);
  }

  public static function sanitize(string $path, bool $isClean = true)
  {
    $replacement = ['$1$2$3', (!$isClean ? '$1' : null).'$2'];
    return self::replace([self::MULTI_SEP, self::CUR_DIR], $replacement, $path);
  }

  /**
   * 
   * @param array $path
   * @return string|false
   */
  private static function getDrive(array $paths)
  {
    /** @var bool|string Path drive */
    $output = false;
    foreach($paths as $path) {
      if (($drive = self::rootname($path)) && $drive !== self::BSEP) {
        $output = \rtrim($drive, self::BSEP);
      }
    }
    return $output;
  }

  private static function isNetwork(string $path)
  {
    return !!(self::match(self::MULTI_SEP, $path, true)[1] ?? false);
  }

  private static function addPrefix(string $root)
  {
    return $root != null ? \rtrim($root, self::BSEP).self::BSEP : null;
  }

  protected static function makeResolver(array $paths)
  {
    $root = self::getDrive($paths);
    $isRoot = $root != null;
    $i = \count($paths) - 1;
    $resolved = [];
    /**
     * 
     * @param string $root
     * @param string $path
     * 
     * @return void|true
     */
    $isStopped = function($root, $path, &$resolved) {
      if (($path && $path[0] === self::BSEP) ||
        (self::isNetwork($root))) {
        self::prepend($resolved, \substr($path, \strlen($root)));
        return true;
      }
    };
    /**
     * 
     * @param array  $resolved
     * @param string $root
     * @param bool   $isRoot
     * 
     * @return string
     */
    $output = function($resolved, $root, $isRoot) {
      $isRoot && self::prepend($resolved, $root);
      $r = self::serialize($resolved, true);
      if (\count($resolved) > 1) {
        return \rtrim($r, self::BSEP);
      }
      return $r;
    };

    for(; $i >= 0; $i--) {
      $path = $paths[$i];
      
      if (self::verifyDrive($root, $path)) {
        if (self::getDrive([$path])) {
          $path = \substr($path, \strlen($root));
        }
      } else {
        continue;
      }

      self::prepend($resolved, $path);
      
      if ($isStopped($root, $path, $resolved)) {
        break; // Stop Immediately loop
      }
    }

    return $output($resolved, $root, $isRoot);
  }

  private static function createRegex(string $source)
  {
    return \sprintf('/^%s/', \str_replace(self::BSEP, '\\\\', $source));
  }

  private static function verifyDrive(string $target, string $matcher)
  {
    $matcherDrive = self::getDrive([$matcher]);
    $targetDrive  = self::getDrive([$target]);
    
    if ($matcherDrive && self::isNetwork($matcherDrive)) {
      return false;
    }

    return $targetDrive && $matcherDrive && self::match(self::createRegex($targetDrive), $matcherDrive) ||
      !$matcherDrive;
  }

  /**
   * 
   * @param array $array
   * @return mixed
   */
  private static function remove(array &$array)
  {
    return \array_pop($array);
  }

  /**
   * 
   * @param array $paths
   * @return array
   */
  public static function separateMap(array $paths)
  {
    $output = [];
    foreach($paths as $path) {
      $output[] = self::separate($path);
    }
    return $output;
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
  protected static function normalized(string $root, string $path) : string
  {
    // TODO: fix current directory bugs of specific version
    $path = $path==null ? self::current.self::BSEP : $path;
    $fDirs = \explode(self::BSEP, $path);
    $root = self::addPrefix($root);
    $normalized = [];
    $tmpParents = [];
    $endPart = @end($fDirs);
    $hasContains = function(array $array) {
      foreach($array as $data) {
        if ($data != null) {
          return true;
        }
      }
      return false;
    };
    
    foreach($fDirs as $fDir) {
      if ($fDir === self::parent) {
        self::remove($normalized)==null && self::append($tmpParents, $fDir);
      } else {
        self::append($normalized, $fDir);
      }
    }
    
    if ($root) {
      self::prepend($normalized, $root);
    } else {
      \count($tmpParents) > 0 ? self::prepend($normalized, $tmpParents) : !$hasContains($normalized) && $endPart == null && self::prepend($normalized, self::current);
    }
    
    return self::serialize($normalized);
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