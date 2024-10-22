<?php

declare(strict_types=1);

namespace Path\Model;

trait PathBuilder
{
  private static $MULTI_SEP = '/(?:^(['.self::regsep.']{2})(?=[^'.self::regsep.']+['.self::regsep.']+[^'.self::regsep.']+['.self::regsep.']?)|(['.self::regsep.'])['.self::regsep.']*)/';
  private static $CUR_DIR = '/['.self::regsep.'][.](?=['.self::regsep.']|$)/';
  private static $consecutive_sep = ('/([\\\\\/])[\\\\\/]*/');

  private static $parent = '..';
  private static $current = '.';

  private static $togglesep = self::sep == '\\' ? '/' : '\\';
  private static $SELF_CURDIR = '/^(\.[\\\\\/]?)[\\\\\/]*$/';
  private static $ROOT = '/^(?:(?:['.self::regsep.']{2})[^'.self::regsep.']+['.self::regsep.']+[^'.self::regsep.']+['.self::regsep.']?|(?:[A-Z]:)*['.self::regsep.']?)|/';

  /**
   * 
   * 
   * @param iterable $paths [required]
   * @param bool     $isMap [optional]
   * 
   * @return string|string[]
   */
  public static function serialize(iterable $paths, bool $isMap = false)
  {
    return $isMap ? \array_map([self::class, 'sanitize'], $paths, [$isMap]) : self::sanitize(self::joins($paths));
  }

  /**
   * 
   * 
   * @param string|string[] $paths [required]
   * @return string simple joined path with current separator
   */
  private static function joins($paths)
  {
    return \is_array($paths) ? \join(self::sep, $paths) : $paths;
  }

  /**
   * 
   * 
   * @param string $path    [required]
   * @param bool   $autoSep [optional]
   * 
   * @return string Returns escaped path
   */
  public static function escape(string $path, bool $autoSep = true) : string
  {
    return \preg_replace(self::$consecutive_sep, '$1', $autoSep ? self::adjust($path) : $path);
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return object path info
   */
  private static function info(string $path) : object
  {
    return (object) \pathinfo($path);
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string adjustedd path
   */
  private static function adjust(string $path) : string
  {
    return \str_replace(self::$togglesep, self::sep, $path);
  }

  /**
   * 
   * 
   * @param string|string[] $paths   [required]
   * @param bool            $isPosix [optional]
   * 
   * @return string separated path string
   */
  public static function separate($paths, bool $isPosix = self::isPosix) : string
  {
    $path = self::canonicalize(self::joins($paths));
    $sep  = ($isPosix ? '/' : '\\');
    return $sep !== self::sep ? \str_replace(self::sep, $sep, $path) : $path;
  }

  /**
   * 
   * 
   * @param string $path   [required]
   * @param string $suffix [optional]
   * @param int    $levels [optional]
   * 
   * @return string path of directory
   */
  public static function dirname(string $path, string $suffix = '', int $levels = 1) : string
  {
    $dirname = \dirname($path, $levels);
    $lastsep = \substr($path, \strlen($dirname), 1);

    // Returns current directory '.' if $dirname is eq to null
    if ($dirname == null) {
      return self::$current;
    }

    // Handle: for posix operating system (Linux, MacOs)
    if (($posix = self::isPosix)) {
      if (self::escape($path, false) === self::$togglesep) {
        return self::$current;
      } elseif ($lastsep === self::$togglesep) {
        return self::dirname($dirname, $suffix, $levels);
      }
    }

    return $posix && $dirname===self::$togglesep ? self::sep : self::suffix($dirname, $suffix);
  }

  /**
   * for win32
   * 
   * @param string|string[] $paths [required]
   * @return string|null
   */
  private static function getdrive($paths)
  {
    $output = null;
    foreach((array) $paths as $path) {
      if (($drive = self::rootname($path)) && $drive !== self::sep) {
        $output = self::suffix($drive, self::sep);
      }
    }
    return $output;
  }

  /**
   * 
   * 
   * @param string|bool $target  [required]
   * @param string|bool $matcher [required]
   * 
   * @return bool verified for true otherwise false
   */
  public static function verify($target, &$matcher) : bool
  {
    // Support: (Linux, MacOs) operating system.
    // Don't verify if posix are present.
    // Returns always true to next operation.
    if (self::isPosix) {
      return true;
    }

    $m = self::getdrive($matcher);
    $t = self::getdrive($target);
    
    // Dont't verify if $matcher is network path.
    // Now modify given $matcher to slice URI
    if ($m && self::isURIPath($m)) {
      $matcher = self::sep.\substr($matcher, \strlen($target));
    }

    // To check verified conditions.
    $isVerified = ($t && $m && self::match(self::regex($t), $m)) || !$m;

    // Slice drive of $matcher for win32
    if ($isVerified && $m) {
      $matcher = \substr($matcher, \strlen($target));
    }

    return $isVerified;
  }

   /**
   * 
   * 
   * @param string|null $root [required]
   * @param array       $src  [required]
   * 
   * @return string attached root directory path
   */
  private static function attached(?string $root, array $src)
  {
    if ($root != null) {
      $src[0] = $root.($src[0] ?? '');
    }

    return self::serialize($src);
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string Returns pathname
   */
  public static function pathname(string $path) : string
  {
    $path = self::canonicalize($path);
    return \substr($path, strlen(self::getdrive($path) ?? ''));
    return '';
  }

  /**
   * 
   * @param string[] $paths [required]
   * @return string
   */
  private static function resolver(array $paths)
  {
    \array_unshift($paths, self::getcwd());
    $paths = self::serialize($paths, true);
    $root = self::getdrive($paths) ?? '';
    $i = \count($paths) - 1;
    $resolved = [];

    for(; $i >= 0; $i--) {
      $path = $paths[$i];
      if (!self::verify($root, $path)) {
        continue;
      }

      \array_unshift($resolved, $path);
      if ($path && $path[0] === self::sep) {
        break;
      }
    }

    return self::suffix(
      self::attached(
        self::rootname($root, true), $resolved
      ), self::sep
    );
  }

  private static function normalizer(string $pathURI) : string
  {
    // TODO: Dont't split unPosix separator if isPosix activated
    // TODO: Fix bugs of posix::ralative of specific version
    $root  = self::rootname($pathURI);
    $path  = self::pathname($pathURI);
    $files = \explode(self::sep, $path==null ? $pathURI : $path);
    $normalized = [];
    $upDirs     = [];
    $endPart    = @end($files);
    $isAbsolute  = self::rootname($path);
    $hasContains = function(array $array) {
      foreach($array as $data) {
        if ($data != null) {
          return true;
        }
      }
      return false;
    };

    // Fix bugs of current directory preview
    // Reset the files source set with [] if $path is eq to empty
    if ($path == null) {
      $files = [];
    }

    // 
    foreach($files as $file) {
      if ($file === self::$parent) {
        \array_pop($normalized)==null && \array_push($upDirs, $file);
      } else {
        \array_push($normalized, $file);
      }
    }

    // 
    if (!$isAbsolute) {
      if (\count($upDirs) > 0) {
        \array_unshift($normalized, ...$upDirs);
      } else if (!$hasContains($normalized) && $endPart == null) {
        \array_unshift($normalized, self::$current);
      }
    }
    
    // Fix bugs of $root directory consecutive slash
    // 
    return self::attached($root, $normalized);
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return bool true for network path otherwise false
   */
  public static function isURIPath(string $path)
  {
    return !!(self::match(self::$MULTI_SEP, $path, true)[1] ?? false);
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string sanitized path
   */
  public static function sanitize(string $path) : string
  {
    return \preg_replace(self::$MULTI_SEP, '$1$2$3', self::isPosix ? self::escape($path) : self::adjust($path));
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string canonicalized path
   */
  public static function canonicalize(string $path) : string
  {
    return \preg_replace(self::$CUR_DIR, '', self::sanitize($path));
  }

  /**
   * 
   * 
   * @param string $source [required]
   * @return string Regular-Expression
   */
  private static function regex(string $source) : string
  {
    return \sprintf('/^%s/', \str_replace(self::sep, self::regsep, $source));
  }

  /**
   * 
   * 
   * @return string current working directory.
   */
  public static function getcwd() : string
  {
    $cwd = \getcwd();
    return self::isPosix ? \preg_replace(self::regex('^[A-Z]:'), '', self::sanitize($cwd)) : $cwd;
  }

  /**
   * 
   * 
   * @param string $pattern       [required]
   * @param string $subject       [required]
   * @param bool   $returnMatches [optional]
   * 
   * @return array|false false or matches
   */
  private static function match(string $pattern, string $subject, bool $returnMatches = false)
  {
    $isMatched = \preg_match($pattern, self::adjust($subject), $matches);
    return $returnMatches ? $matches : $isMatched;
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string root directory
   */
  public static function rootname(string $path, bool $withPrefix = false) : string
  {
    $root = self::match(self::isPosix ? self::regex(self::sep) : self::$ROOT, $path, true)[0] ?? '';
    return $withPrefix ? (self::suffix($root, self::sep, true).self::sep) : $root;
  }

  /**
   * Returns an suffixed data, Triming right side data matched to suffix
   * value but do not trim [data] if [data] and suffix value are equal.
   * 
   * @param string $data       [required]
   * @param string $suffix     [required]
   * @param bool   $allMatched [optional]
   * 
   * @return string suffixed-data string
   */
  private static function suffix(string $data, string $suffix, bool $allMatched = false) : string
  {
    $cond = $allMatched ? true : $data !== $suffix;
    return $cond && \substr($data, - \strlen($suffix)) === $suffix ? \substr($data, 0, - \strlen($suffix)) : $data;
  }
}
?>