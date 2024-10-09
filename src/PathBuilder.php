<?php

declare(strict_types=1);

namespace Path;

abstract class PathBuilder
{
  /** @var string ROOT Use to get root directory first index of path LIKE /|//|C:|C:/ */
  protected const ROOT = '/^(?:(?:\\\\\\\\|\/\/)[^\\\\\/]+[\\\\\/]+[^\\\\\/]+[\\\\\/]?|(?:[A-Z]:)*[\\\\\/]?)|/';

  /** @var string FSEP and BSEP forward separator and backword separator */
  public const BSEP = '\\';
  public const FSEP = '/';

  /**
   * Matches the current directory identifier [.] of path
   * 
   * @var string
   */
  private const CUR_DIR = '/(?:^\.[\\\\\/])|(?:([^.])\.([\\\\\/]))/';

  /**
   * Matches the parent directory identifier [..] of path
   * 
   * @var string
   */
  private const PARENT_DIR = '/^(?:\.\.)$/';

  /**
   * 
   * @var string
   */
  protected const RTRIM_SEP = '/([^\\\\\/]+)(\\\\|\/)+$/';

  /**
   * 
   * @var string
   */
  private const MULTI_SEP = '/(?:^(\\\\|\/)(\1)(?=[^\\\\\/]+[\\\\\/]+[^\\\\\/]+[\\\\\/]?)|([\/\\\\])[\/\\\\]*)/';

  /**
   * 
   * 
   * @param array $paths
   * @param string $extra
   * @param 
   * 
   * @return array
   */
  protected static function getCompleteSource(array $paths, string $extra, $separate)
  {
    // Handle: If given $extra value then unshift extra value
    if ($extra) {
      \array_unshift($paths, $extra);
    }

    // Replace forward / backward \\ slash to single backward \\ slash all value
    $paths = \array_map($separate, $paths);

    /** @var string */
    $root = self::getDrive($paths);

    /** @var array */
    $source = [];

    /** @var int */
    $i = \count($paths) - 1;

    /** @var array */
    $newPaths = [];

    for(; $i >= 0; $i--) {
      $path = $paths[$i];

      if (self::matchDrive($root, $path)) {
        if (self::getDrive([$path])) {
          $path = \mb_substr($path, \mb_strlen($root));
        }
      
      // Otherwise skip the unmatched drive path
      } else {
        continue;
      }

      // Fix reverse behaviour and insert path teek.
      \array_unshift($newPaths, $path);

      if ($path && ($path[0] === self::BSEP)) {
        break;
      }
    }

    \array_unshift($newPaths, $root);
    return $separate($newPaths);
  }

  /**
   * Normalize a string path, reducing '..' and '.' parts. When multiple slashes are found,
   * they're replaced by a single one; when the path contains a trailing slash,
   * it is preserved. On Windows backslashes are used.
   * 
   * @param string $root     [required]
   * @param string $path     [required]
   * @param array  $separate [required]
   * 
   * @return string
   */
  protected static function normalized(string $root, string $path, array $separate)
  {
    $fDirs = \explode(self::BSEP, $path);
    $normalized = [];
    $tmpParents = [];
    $endPart = end($fDirs);
    function hasContains(array $array) {
      foreach($array as $data) {
        if (!!$data) {
          return true;
        }
      }
      return false;
    }

    foreach($fDirs as $fDir) {
      if ($hasParent = \preg_match(self::PARENT_DIR, $fDir)) {
        \array_pop($normalized)==null && \array_push($tmpParents, $fDir);
      } else {
        \array_push($normalized, $fDir);
      }
    }

    // Attach root dir If All Done!
    if ($root) {
      \array_unshift($normalized, $root);
    } else {
      \count($tmpParents) > 0 ? \array_unshift($normalized, ...$tmpParents) : !hasContains($normalized) && !$endPart && \array_unshift($normalized, '.', $endPart);
    }

    return $separate($normalized);
  }

  /**
   * Returns the refresh path or string to replace the multi slashes to single (/)
   * slashes. And remove all current directory (./) seperator But do not
   * Remove current directory (^./) seperator of first index of path
   * 
   * @param string $path [required]
   * @return string refresh path
   */
  public static function refresh(string $path, ?string $sep=null) : string
  {
    $replacement = ['$1'.($sep ?? '$3'), '$1'];
    return \preg_replace([self::MULTI_SEP, self::CUR_DIR], $replacement, $path);
  }

  /**
   * 
   * @param string $path
   * @return string
   */
  protected static function namespaceWith(string $path)
  {
    return '\\\\?'.\preg_replace_callback(self::MULTI_SEP, function($matched) {
      return $matched[1] ? '\\UNC\\' : $matched[3] ?? $matched[2];
    }, $path);
  }

  protected static function fileExt()
  {

  }

  /**
   * 
   * @param array $path
   * @return string|false
   */
  private static function getDrive(array $paths)
  {
    /** @var bool|string */
    $output = false;
    foreach($paths as $path) {
      \preg_match(self::ROOT, $path, $matches);
      $drive = $matches[0];
      if (!!$drive && $drive !== self::BSEP) {
        $output = \rtrim($drive, self::BSEP);
      }
    }
    
    return $output;
  }

  /**
   * 
   * @param string $target
   * @param string $matcher
   * 
   * @return bool
   */
  private static function matchDrive(string $target, string $matcher) : bool
  {
    $abspath = self::getDrive([$matcher]);
    $drive   = self::getDrive([$target]);

    return ($drive && $abspath && \preg_match(\sprintf('/^%s/', \str_replace(self::BSEP, '\\\\', $drive)), $abspath)) || !$abspath;
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