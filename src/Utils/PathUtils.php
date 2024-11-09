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

namespace Path\Utils;

use Path\Exception\RTException;

/**
 * PathUtils Trait
 * PathUtils trait to provides and access some path-related methods and functionalities.
 * 
 * @author Shahzada Modassir <shahzadamodassir@gmail.com>
 * @author Shahzadi Afsara   <shahzadiafsara@gmail.com>
 */
trait PathUtils
{
  private static $MULTI_SEP = '/(?:^(['.self::regsep.']{2})(?=[^'.self::regsep.']+['.self::regsep.']+[^'
  .self::regsep.']+['.self::regsep.']?)|(['.self::regsep.'])['.self::regsep.']*)/';

  /**
   * To matches the current directory with slash or not slash.
   * 
   * @var string
   */
  private static $CUR_DIR = '/[\\\\\/][.](?=[\\\\\/]|$)/';

  /**
   * To matches the first current directory './' or '.\'
   * 
   * @var string
   */
  private static $fCurDir = '/^(?:\.[\\\\\/])(?=.)/';

  /**
   * Switch the separator to opposit of the current separator.
   * 
   * @var string
   */
  private static $togglesep = self::sep == '\\' ? '/' : '\\';

  /**
   * isPosix the identify with (Linux/MacOs) for the current platform.
   * 
   * @var bool
   */
  private static $isPosix = self::sep === '\\' ? false : true;

  /**
   * To matches the consecutive slashes.
   * 
   * @var string
   */
  private static $consep = ('/([\\\\\/])[\\\\\/]*/');

  /**
   * Representing the dot operator for the parent directory.
   * 
   * @var string
   */
  private static $parent = '..';

  /**
   * Representing the dot operator for the current directory.
   * 
   * @var string
   */
  private static $curdir = '.';

  private static $ROOT = '/^(?:(?:['.self::regsep.']{2})[^'.self::regsep.']+['.self::regsep.']+[^'
  .self::regsep.']+['.self::regsep.']?|(?:^[A-Z]:)*['.self::regsep.']?)|/';

  /**
   * !Important: For internal use only.
   * 
   * Removes consecutive slashes and Replace all consecutive slashes with a single separator.
   * This method will removed all current directory operator like './' or '.\\' of path string.
   * 
   * @param string $path     [required]
   * @param bool   $fAutoSep [optional]
   * 
   * @return string The modified path string with current directory '.' removed.
   */
  private static function clean(string $path, bool $fAutoSep = false) : string
  {
    return preg_replace(self::$CUR_DIR, '', self::escape($path, $fAutoSep));
  }

  /**
   * !Important: For internal use only.
   * 
   * Splits the path string into an array using separator as the current separator.
   * This method takes a string and divides it into parts wherever current separator is found.
   * 
   * This method will override or replace seprators as requirements of needed.
   * 
   * @param string $path        [required]
   * @param bool   $overrideAll [optional] auto-separate
   * 
   * @return array An array of strings created by splitting the original string.
   */
  private static function split(string $path, bool $overrideAll = false) : array
  {
    return \explode(self::sep, self::escape($path, $overrideAll));
  }

  /**
   * !Important: For internal use only.
   * 
   * Removes consecutive slashes and Replace all consecutive slashes with a single separator.
   * This method will [auto-separate or auto-format] as requirements of needed.
   * 
   * @param string $path     [required]
   * @param bool   $fAutoSep [optional]
   * 
   * @return string The modified path string with consecutive slashes replaced.
   */
  private static function escape(string $path, bool $fAutoSep = false) : string
  {
    $path = $fAutoSep || !self::$isPosix ? \str_replace(self::$togglesep, self::sep, $path) : $path;
    return self::$isPosix ?
      \preg_replace(self::$consep, '$1', $path) : \preg_replace(self::$MULTI_SEP, '$1$2$3', $path);
  }

  /**
   * !Important: For internal use only.
   * 
   * Check if the given path is a valid network path.
   * A valid network path starts with two backslashes \\ or // followed by the server name,
   * and can include subdirectories.
   * 
   * @param string $path [required]
   * @return bool Returns true if the path is a valid network path, false otherwise.
   */
  public static function isURIPath(string $path) : bool
  {
    return !!(self::match(self::$MULTI_SEP, $path, true)[1] ?? false);
  }

  /**
   * Get the path name of a given file or directory.
   * This function returns the path name without any suffix or modification and without drive.
   * 
   * @param string $path [required]
   * @return string The path name of the given file or directory.
   */
  public static function pathname(string $path) : string
  {
    $path = self::clean($path);
    return \substr($path, \strlen(self::getdrive($path)));
  }

  /**
   * !Important: For internal use only.
   * 
   * Converts the given source string into a regular expression pattern.
   * This method replaces path separators defined by `self::sep` with 
   * the corresponding regular expression separators defined by `self::regsep`.
   * 
   * @param string $source [required]
   * @return string Regular Expression modified with separator.
   */
  private static function regex(string $source) : string
  {
    return \sprintf('/^%s/', \str_replace(self::sep, self::regsep, $source));
  }

  /**
   * !Important: For internal use only.
   * 
   * Normalize a string path, reducing '..' and '.' parts. When multiple slashes are found,
   * they're replaced by a single one; when the path contains a trailing slash,
   * it is preserved. On Windows backslashes are used.
   * This method will auto-separate for POSIX (Linux/macOs) requirements as needed.
   * 
   * @param string $pathURI  [required]
   * @param bool   $fAutoSep [optional]
   * 
   * @return string Returns normalized path string.
   */
  private static function doNormalize(string $pathURI, bool $fAutoSep = false) : string
  {
    $root  = self::rootname($pathURI);
    $path  = self::pathname($pathURI);
    $files = $path == null ? [] : self::split($path, $fAutoSep);
    $normalized = [];
    $upDirs     = [];
    $endPart    = @end($files);
    $notAbsPath = !self::rootname($path);
    $hasContains = function(array $array) {
      foreach($array as $data) {
        if ($data != null) {
          return true;
        }
      }
      return false;
    };

    // Got through the foreach, for normalizing the path
    foreach($files as $file) {
      if ($file === self::$parent) {
        \array_pop($normalized) == null && ($upDirs[] = $file);
      } else {
        $normalized[] = $file;
      }
    }

    // Add suffix separator of network uri for both platform.
    if (self::isURIPath($root)) {
      $root = self::suffix($root, self::sep, true).self::sep;
    }

    // Sotred parents or current directory identifiers LIKE '../' './'
    if ($notAbsPath) {
      if (\count($upDirs) > 0) {
        \array_unshift($normalized, ...$upDirs);
      } else if (!$hasContains($normalized) && $endPart == null && !\str_ends_with($root, self::sep)) {
        \array_unshift($normalized, self::$curdir);
      }
    }

    return self::final($root, $normalized);
  }

  /**
   * Retrieves a list of available drives on the system.
   * Get the last valid drive path from an array or return the drive path from a given string.
   * 
   * @param string|string[] $paths [required]
   * @return string|null The last valid drive path found, or an empty string if none is found.
   */
  private static function getdrive($paths)
  {
    $drive = '';
    foreach((array) $paths as $path) {
      if (($root = self::rootname($path)) && $root !== self::sep) {
        $drive = self::suffix($root, self::sep);
      }
    }
    return $drive;
  }

  /**
   * Get the directory name of a given path with optional suffix removal and level adjustment.
   * This function returns the parent directory's path of the provided file or directory path.
   * It also allows for an optional suffix to be removed from the base name and
   * specifies how many levels up the directory to go.
   * 
   * @param string $path   [required]
   * @param string $suffix [optional]
   * @param int    $levels [optional]
   * 
   * @return string The directory name of the given path after processing.
   */
  public static function dirname(string $path, string $suffix = '', int $levels = 1) : string
  {
    $dirname = \dirname($path, $levels);
    $lastsep = \substr($path, \strlen($dirname), 1);
  }

  /**
   * !Important: For internal use only.
   * 
   * Matches a given pattern against a subject string.
   * This method adjusts multiple consecutive slashes in the subject to a single separator,
   * then checks if the pattern matches the adjusted subject. If $returnMatches is true,
   * it returns the matches found; otherwise,
   * it returns a boolean indicating if a match was found.
   * 
   * @param string $pattern       [required]
   * @param string $subject       [required]
   * @param bool   $returnMatches [optional]
   * 
   * @return array|bool array of matches if $returnMatches is true,
   * or a boolean indicating if a match was found.
   */
  private static function match(string $pattern, string $subject, bool $returnMatches = false)
  {
    $isMatched = \preg_match($pattern, self::escape($subject), $matches);
    return $returnMatches ? $matches : $isMatched;
  }

  /**
   * Retrieves the root name of the path.
   * This method extracts the root component of a given path,
   * which can be useful for determining the base directory or starting point for further path
   * manipulations.
   * 
   * @param string $path [required]
   * @return string The root name of the path.
   */
  public static function rootname(string $path) : string
  {
    return self::match(self::$isPosix ? '/^'.self::regsep.'/' : self::$ROOT, $path, true)[0] ?? '';
  }

  /**
   * Retrieves the current working directory based on the operating system.
   * This method returns the current working directory in a format appropriate for the platform.
   * For POSIX systems, it returns a path like '/xampp/htdocs/', while for Windows systems,
   * it returns a path like 'C:/xampp/htdocs/'.
   * 
   * @return string The current working directory.
   */
  public static function getcwd() : string
  {
    $cwd = self::escape(\getcwd(), true);
    return self::$isPosix ? \preg_replace('/^[A-Z]:/', '', $cwd) : $cwd;
  }

  /**
   * !Important: For internal use only.
   * 
   * This function resolves a path by combining the given segments into a single path.
   * It does not handle or normalize '..' or '.' segments, meaning they will remain in the output.
   * 
   * @param string[] $paths [required]
   * @return string The resolved path by combining into a single path.
   */
  private static function doResolve(array $pathsURI) : string
  {
    \array_unshift($pathsURI, self::getcwd());
    $paths = \array_map([self::class, 'clean'], $pathsURI);
    $i = \count($paths) - 1;
    $root = self::getdrive($paths);
    $resolves = [];

    for(; $i >= 0; $i--) {
      $path = $paths[$i];

      if (!self::verify($root, $path)) {
        continue;
      }
      
      \array_unshift($resolves, $path);
      if ($path && $path[0] === self::sep) {
        break;
      }
    }

    return self::final(
      self::suffix($root, self::sep, true).self::sep, $resolves, true
    );
  }

  /**
   * !Important: For internal use only.
   * 
   * final - Returns a final configured normalized path string with attached root directory.
   * - Attach or implements $root directory in $normalized data
   * - Removes current directory operator './' or '.\' first index of normalized path.
   * - Removes consecutive slashes and Replace consecutive slashes with a single separator.
   * And join $results with current seperator
   * 
   * @param string|null $root    [required]
   * @param array       $results [required]
   * @param bool        $rls     [optional]
   * 
   * @return string final path escaped and join $normalize with $root directory.
   */
  private static function final(?string $root, array $results, bool $rls = false) : string
  {
    $path = self::_join($results);
    if ($root != null) {
      $path = $root.self::prefix(self::sep, $path, false);
    }

    // Dealing for Path::resolve removing last separator of path.
    if ($rls && $path !== $root) {
      $path = self::suffix($path, self::sep, true);
    }

    return \preg_replace(self::$fCurDir, '', self::escape($path));
  }

  /**
   * !Important: For internal use only.
   * 
   * Joins an array of paths into a single string using a defined separator.
   * If the input is already a string (not an array), it simply returns the input as is.
   * 
   * @param string|string[] $paths [required]
   * @return string The joined paths as a string.
   */
  private static function _join($paths, ?string $sep = null) : string
  {
    return \is_array($paths) ? \join($sep ?? self::sep, $paths) : $paths;
  }

  /**
   * !Important: For internal use only.
   * 
   * Check if the given drive of path matched to the $target drive of path.
   * This method will match the path drive of $target path drive and $matcher path drive or,
   * check the none-drive of path of given $matcher path.
   * 
   * @param string|null $target  [required]
   * @param string|null $matcher [required]
   * 
   * @return bool Returns true if the given path drive are matched, false otherwise.
   */
  private static function verify(?string $target, ?string &$matcher) : bool
  {
    // SKIP the drive verification, All check passed!
    // Don't check drive verification of the path drive for POSIX (Linux/MacOs) platform.
    if (self::$isPosix) {
      return true;
    }

    // Retrieving the drive from $matcher and $target
    $matcherDrive = self::getdrive($matcher);
    $targetDrive  = self::getdrive($target);
    
    // Removes Network Path URI of $matcher path, If given $matcher is Network Path.
    // And return always true skipping the  drive verification.
    if (($isURI = $matcherDrive && self::isURIPath($matcherDrive))) {
      $matcher = \substr($matcher, \strlen($target));
    }

    // Let's start the drive verification or none-drive $matcherDrive verification.!
    $isVerified = ($targetDrive && $matcherDrive && self::match(self::regex($targetDrive), $matcherDrive)) ||
    !$matcherDrive;

    // Removes drive of $matcher path, If $target drive and $matcher drive are matched.
    if ($isVerified && $matcherDrive && !$isURI) {
      $matcher = \substr($matcher, \strlen($target));
    }

    return $isVerified;
  }

  /**
   * !Important: For internal use only.
   * 
   * This method checks if the provided string ends with the specified suffix.
   * If it does, the method returns the string without that suffix.
   * If the suffix does not match, the original string is returned unchanged.
   * 
   * @param string $data       [required]
   * @param string $suffix     [required]
   * @param bool   $allMatched [optional]
   * 
   * @return string The modified string without the suffix,
   * or the original string if no match.
   */
  private static function suffix(string $data, string $suffix, bool $allMatched = false) : string
  {
    $cond = $allMatched ? true : $data !== $suffix;
    return $cond && \substr($data, - \strlen($suffix)) === $suffix ? \substr($data, 0, - \strlen($suffix)) : $data;
  }

  /**
   * !Important: For internal use only.
   * 
   * This method checks if the provided string ends with the specified prefix.
   * If it does, the method returns the string without that prefix.
   * If the prefix does not match, the original string is returned unchanged.
   * 
   * @param string $prefix     [required]
   * @param string $data       [required]
   * @param bool   $allMatched [optional]
   * 
   * @return string The modified string without the prefix,
   * or the original string if no match.
   */
  private static function prefix(string $prefix, string $data, bool $addPrefix = true, bool $allMatched = false) : string
  {
    $cond = $allMatched ? true : $data !== $prefix;
    $prefixed = $cond && \substr($data, 0, \strlen($prefix)) === $prefix ? \substr($data, \strlen($prefix)) : $data;
    return $addPrefix && $prefixed != null ? $prefix.$prefixed : $prefixed;
  }
}
?>