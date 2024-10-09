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

  private const CURRENT_DIRECTORY = '.';

  /**
   * Matches the current directory identifier [.] of path
   * 
   * @var string
   */
  private const CUR_DIR    = '/(?:[^.]\.([\\\\\/]))/';

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
   * 
   * @param string $root
   * @param string $path
   * @param array  $separate
   * 
   * @return string
   */
  protected static function normalized(string $root, string $path, array $separate)
  {
    $normalized = [];
    $source = \explode(self::BSEP, $path);

    foreach($source as $teek) {
      \preg_match(self::PARENT_DIR, $teek) ? \array_pop($normalized) : \array_push($normalized, $teek);
    }
    
    if ($root) {
      \array_unshift($normalized, $root);
    }

    return self::compileWith($separate($normalized, self::BSEP));
  }

  /**
   * 
   * 
   * @param string $path
   * @param string $sep
   * 
   * @return string
   */
  public static function refresh(string $path, ?string $sep=null) : string
  {
    $replacement = ['$1'.($sep ?? '$3'), '$1'];
    return \preg_replace([self::MULTI_SEP, self::CUR_DIR], $replacement, $path);
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
   * @param string $data
   * @return string
   */
  private static function compileWith(string $data)
  {
    return $data ? $data : self::CURRENT_DIRECTORY;
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