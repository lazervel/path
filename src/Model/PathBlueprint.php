<?php

declare(strict_types=1);

namespace Path\Model;

trait PathBlueprint
{
  use PathBuilder;

  public static function filePaths(iterable $paths, iterable $names) : iterable
  {
    $files = [];
    foreach($paths as $path) {
      foreach($names as $name) {
        $files[] = self::serialize([$path, $name]);
      }
    }
    return $files;
  }

  public static function parse(string $path) : string
  {
    return [
      'root' => self::rootname($path), 'dir' => self::dirname($path),
      'base' => self::basename($path), 'ext' => self::extname($path),
      'name' => self::filename($path)
    ];
  }

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

  public static function isAbsolute(string $path) : bool
  {
    $matched = self::rootname($path);
    return !!$matched && !\str_ends_with($matched, ':');
  }

  public static function isLocal(string $path) : bool
  {
    return \stream_is_local($path) || \strpos($path, 'file://');
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
    
    /**
     * $matchesLength matches the common base path to increase count
     * 
     * @var int $matchesLength
     */
    $matchesLength = 0;

    // Go through the array, to matches the common path
    while(\count($from) && \count($to) && $from[0] === $to[0]) {
      \array_shift($from);
      \array_shift($to);
    }
    
    // Create the relative path
    $relative = \str_repeat(self::$parent.self::sep, \count($from)).self::joins($to);
    return self::normalize($relative);
  }

  public static function resolve(string ...$paths) : string
  {
    return self::normalize(self::resolver($paths));
  }

  public static function normalize(string $path) : string
  {
    return self::normalizer($path);
  }

  public static function join(string ...$paths) : string
  {
    return self::normalize(self::serialize($paths));
  }

  public static function extname(string $path) : string
  {
    return self::info($path)->extension;
  }

  public static function filename(string $path) : string
  {
    return self::info($path)->filename;
  }

  public static function tmpName(string $name) : string
  {
    return $name.'\.!!'.'/.!'.strrev(strtr(base64_encode(random_bytes(3)), '/=', '-!'));
  }

  public static function basename(string $path, string $suffix = '') : string
  {
    return \is_callable('basename') ? \basename($path, $suffix) : self::suffix(self::info($path)->basename, $suffix);
  }
}
?>