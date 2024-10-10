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
   * Right side directory separator timmer regular expression
   * 
   * @var string
   */
  protected const RTRIM_SEP = '/([^\\\\\/]+)(\\\\|\/)+$/';

  /**
   * Drive and multi directory separator matcher Regular Expression
   * 
   * @var string
   */
  private const MULTI_SEP = '/(?:^(\\\\|\/)(\1)(?=[^\\\\\/]+[\\\\\/]+[^\\\\\/]+[\\\\\/]?)|([\/\\\\])[\/\\\\]*)/';

  /**
   * Returns a complete array-source to ready to normalize path string, It's used to resolve path string
   * Handle drives, trailing slashes and creating array-range
   * 
   * @param array $paths
   * @return array
   */
  protected static function getCompleteSource(array $paths)
  {
    /** @var string $root Getting a root directory */
    /** @var int $i for Reverse loop */
    /** @var array $path Store final modified path */
    $root = self::getDrive($paths);
    $i = \count($paths) - 1;
    $newPaths = [];

    // Go through the array, Adding each of the items to their new values
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
    return \join(self::BSEP, $newPaths);
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
    $hasContains = function(array $array) {
      foreach($array as $data) {
        if (!!$data) {
          return true;
        }
      }
      return false;
    };

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
      \count($tmpParents) > 0 ? \array_unshift($normalized, ...$tmpParents) : !$hasContains($normalized) && !$endPart && \array_unshift($normalized, '.', $endPart);
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
   * Add namespace identifier '\\\\?' and includes '\UNC\' of specific drive
   * And normalizing the path string.
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
  
  /**
   * Returns only matches path drive Otherwise return false, It used to gets drive
   * 
   * @param array $path
   * @return string|false
   */
  protected static function getDrive(array $paths)
  {
    /** @var bool|string Path drive */
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
   * There matches the same drive or relative path, Its used to making resolved array-source
   * 
   * @param string $target
   * @param string $matcher
   * 
   * @return bool
   */
  protected static function matchDrive(string $target, string $matcher) : bool
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
