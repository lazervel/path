<?php

declare(strict_types=1);

namespace Path;

final class PathBuilder
{
  /** @var string ROOT Use to get root directory first index of path LIKE /|//|C:|C:/ */
  private const ROOT = '/^(?:(?:\\\\\\\\|\/\/)[^\\\\\/]+[\\\\\/]+[^\\\\\/]+[\\\\\/]?|(?:[A-Z]:)*[\\\\\/]?)|/';

  /** @var string S  */
  private const S = '/(?:^(\\\\\\\\|\/\/)(?=[^\\\\\/])|([\/\\\\])[\/\\\\]*)/';

  /** @var string SEP Matches the all single global seprator LIKE (/|\\) */
  private const SEP = '/[\\\\\/]/';

  /** @var string CURDIR Matches the current directory identifier [.] of path */
  private const CURDIR = '/(?:[^.]\.([\\\\\/]))/';

  /** @var string PARENT Matches the parent directory identifier [..] of path */
  private const PARENT = '/^(?:\.\.)$/';

  /** @var string FSEP and BSEP forward separator and backword separator */
  public const BSEP = '\\';
  public const FSEP = '/';

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
    /** @var array $normalized Store normalized [teek] */
    $normalized = [];

    // Declare an important variable are neeeded!
    $root   = self::rootname($path);
    $path   = self::pathname($path);
    $source = \explode(self::BSEP, $path);

    // Go throw normalizing process insert teek!
    foreach($source as $teek) {
      \preg_match(self::PARENT, $teek) ? \array_pop($normalized) : \array_push($normalized, $teek);
    }

    // SET: push previous root directory of path
    \array_unshift($normalized, $root);
    return self::separate($normalized,
      self::BSEP
    );
  }

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
    return \mb_substr($path, \mb_strlen(self::rootname($path)));
  }

  /**
   * 
   * 
   * @param array $paths [required]
   * @return int root index
   */
  public static function getCompleteSource(array $paths, string $curDir) : string
  {
    /** @var array $response Returns to get Complete source */
    $response = ['paths'=> []];

    // Store default current working path direcory
    \array_unshift($paths, $curDir);

    /** @var int $length Total paths of length */
    $length = \count($paths);

    /** @var array $temp Store temporarily Data */
    $temp = [];

    /** @var bool $hasRoot To check present root directory */
    $hasRoot = false;

    foreach($paths as $i => $path) {
      $pathTeek = self::separate($path, self::BSEP);

      // TODO: to enhance and improve code structure of specific version
      // SET: root directory from path
      if (\preg_match(self::ROOT, $pathTeek, $matches) && $matches[0] && $matches[0] !== self::BSEP) {
        $response['root'] = self::suffix(self::separate($matches, self::BSEP), self::BSEP);
        $pathTeek = self::clean(\mb_substr($path, \mb_strlen($response['root'])), self::BSEP);
      }

      // PUSH all $pathTeek filtered data in temp
      \array_push($temp, $pathTeek);

      // SET: last matches of root directory path
      if ($pathTeek[0]===self::BSEP) {
        $hasRoot = true;
        ($length === $i + 1) ? ($response['paths'] = [$pathTeek]) : ($response['i'] = $i);
        continue;
      }
    }

    // If not present any root directory in current path
    // SET: All done!
    if (!$hasRoot) {
      $response['i'] = 0;
    }
    
    // Create a paths range, If present root directory index in $response
    if (empty($response['paths']) && isset($response['i'])) {
      $response['paths'] = \array_slice($temp, $response['i'], $length);
      unset($response['i']);
    }

    // Now store final root directory in Completed paths
    \array_unshift($response['paths'], $response['root']);
    return self::separate($response['paths'], self::BSEP);
  }

  /**
   * Returns clean path or string, to replace multi slashes to single (/)
   * slashes. And remove all current directory (./) seperator But do not
   * Remove current directory (^./) seperator of first index of path
   * 
   * @param string $path [required]
   * @param string $sep  [optional]
   * 
   * @return string self slashed cleaned-path
   */
  public static function clean(string $path, ?string $sep = null) : string
  {
    $sep = $sep ? $sep : '$1$2';
    return \preg_replace([self::S, self::CURDIR], [$sep, '$1'], $path);
  }

  /**
   * TODO: Add a new features ltrim rtirm slashes of specific version
   * Returns seprated path string with seperator LIKE (\\) and (/) of single or multiple path
   * And remove all current directory (./) seperator But do not Remove current directory
   * (^./) seperator of first index of path
   * 
   * @param string|array $paths [required]
   * @param string       $sep   [optional]
   * 
   * @return string separate path with seperator
   */
  public static function separate($paths, string $sep) : string
  {
    $path = self::clean(\join($sep, (array)$paths));
    return \preg_replace(self::SEP, $sep, $path);
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
    \preg_match(self::ROOT, $path, $matched);
    return $matched[0];
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
  public static function suffix(string $data, string $suffix) : string
  {
    return $data !== $suffix && \mb_substr($data, - \mb_strlen($suffix)) === $suffix ? \mb_substr($data, 0, - \mb_strlen($suffix)) : $data;
  }
}
?>