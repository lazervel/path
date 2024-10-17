<?php

declare(strict_types=1);

namespace Path;

class Path extends PathBuilder
{
  /**
   * Returns a parent directory's of path, provide with suffix
   * If the dirname ends in suffix this will also be cut off.
   * 
   * @param string $path   [required]
   * @param string $suffix [optional]
   * @param int    $level  [optional]
   * 
   * @return string
   */
  public static function dirname(string $path, string $suffix = '', int $level = 1) : string
  {
    return self::suffix(\dirname($path, $level), $suffix);
  }

  /**
   * Checks if a stream is a local stream Path or URL
   * 
   * @param string $path [required]
   * @return bool
   */
  public static function isLocal(string $path) : bool
  {
    return \stream_is_local($path) || \strpos($path, 'file://');
  }

  /**
   * 
   * 
   * @param array $pathObject [required]
   * @return string
   */
  public static function format(array $pathObject) : string
  {
    $format = [];

    try {
      // Handle: basename or filename and extention
      self::prepend($format, $pathObject['base'] ??
        $pathObject['name'].$pathObject['ext']
      );

      // Handle: insert dirname and rootname to 1st
      self::prepend($format, $pathObject['dir']??$pathObject['root']);
    } catch(Exception $e) {}

    return self::separate($format);
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string
   */
  public static function filename(string $path) : string
  {
    return self::pathInfo($path)->filename;
  }

  /**
   * 
   * 
   * @param string|string[] $paths [required]
   * @return string|array
   */
  public static function real($paths)
  {
    if (!is_iterable($paths)) {
      return \realpath($paths);
    }

    foreach($paths as $i=>$path) {
      if (!\realpath($path)) unset($paths[$i]);
    }

    return $paths;
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string
   */
  public static function extname(string $path) : string
  {
    return self::pathInfo($path)->extension;
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string
   */
  public static function lastDir(string $path) : string
  {
    $dirs = \explode(self::sep, self::sanitize(\dirname($path)));
    return @end($dirs);
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return bool
   */
  public static function isAbsolute(string $path) : bool
  {
    $matched = self::rootname($path);
    return !!$matched && !\str_ends_with($matched, self::delimiter);
  }

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
   * 
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
    
    /**
     * $matchesLength matches the common base path to increase count
     * 
     * @var int $matchesLength
     */
    $matchesLength = 0;

    // Go through the array, to matches the common path
    foreach($from as $index => $part) {
      if (isset($to[$index]) && $to[$index] === $part) {
        $matchesLength++;
      } else {
        break; // Stop Immediately loop
      }
    }
    
    // Calculate how many directories to go up from $from
    $upDirs = \count($from) - $matchesLength;
    $parents = \str_repeat('..\\', $upDirs);

    $relative = self::separate(\array_slice($to, $matchesLength));

    // Handle: If not drive exists in $relative path then attach $parents
    if (!self::drivename($relative)) {
      $relative = $parents.$relative;
    }
    
    return $relative;
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string
   */
  public static function pathname(string $path) : string
  {
    $path = self::separate($path);
    return \substr($path, \strlen(self::rootname($path)));
  }

  public static function join(string ...$paths) : string
  {
    return self::normalize(self::stripe($paths));
  }

  /**
   * 
   * 
   * @param string $path [required]
   * @return string
   */
  public static function normalize(string $path) : string
  {
    if (\preg_match(self::SELF_CURDIR, $path, $matches)) {
      return $matches[1];
    }
    return self::makeNormalizer(self::rootname($path), self::pathname($path));
  }

  /**
   * 
   * 
   * @param iterable $paths [required]
   * @param iterable $names [required]
   * 
   * @return iterable
   */
  public static function filePaths(iterable $paths, iterable $names) : iterable
  {
    $files = [];
    foreach($paths as $path) {
      foreach($names as $name) {
        $files[] = self::stripe([$path, $name]);
      }
    }
    return $files;
  }

  /**
   * 
   * 
   * @param string[] $paths [required]
   * @return string
   */
  public static function resolve(string ...$paths) : string
  {
    self::prepend($paths, \getcwd());
    return self::normalize(self::makeResolver(self::serialize($paths, true)));
  }

  /**
   * 
   * 
   * @param string $path   [required]
   * @param string $suffix [optional]
   * 
   * @return string
   */
  public static function basename(string $path, string $suffix = '') : string
  {
    return \is_callable('basename') ? \basename($path, $suffix) : self::suffix(self::pathInfo($path)->basename, $suffix);
  }
}
?>