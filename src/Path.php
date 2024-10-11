<?php

declare(strict_types=1);

namespace Path;

use Path\PathBuilder;

class Path extends PathBuilder
{

  public static function dirname(string $path, int $levels = 1, string $suffix = '') : string
  {
    return self::suffix(\dirname($path, $levels), $suffix);
  }

  public static function filePaths($paths, $names)
  {
    $files = [];

    foreach($paths as $path) {
      foreach($names as $name) {
        $files[] = \rtrim($path, self::BSEP).self::FSEP.$name;
      }
    }

    return $files;
  }

  public static function format(array $pathObject) : string
  {
    $format = [];

    try {
      // Handle: basename or filename and extention
      \array_unshift($format, $pathObject['base'] ??
        $pathObject['name'].$pathObject['ext']
      );

      // Handle: insert dirname and rootname to 1st
      \array_unshift($format, $pathObject['dir'] ?? $pathObject['root']);
    } catch(Exception $e) {}

    return self::separate($format);
  }

  public static function extname(string $path) : string
  {
    return \pathinfo($path)['extension'];
  }

  public static function relative(string $from, string $to) : string
  {
    // Create arrayable paths after resolving {from} and {to} path.
    $from = \explode(self::BSEP, self::resolve($from));
    $to   = \explode(self::BSEP, self::resolve($to));
    
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
    if (!self::getDrive([$relative])) {
      $relative = $parents.$relative;
    }
    
    return $relative;
  }

  public static function isAbsolute(string $path) : bool
  {
    $matched = self::rootname($path);
    return !!$matched && !\str_ends_with($matched, self::delimiter);
  }

  public static function filename(string $path) : string
  {
    return \pathinfo($path)['filename'];
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

  public static function basename(string $path, string $suffix = '') : string
  {
    return \is_callable('basename') ? \basename($path, $suffix) : \pathinfo($path)['basename'];
  }

  public static function real($paths) : array
  {
    $paths = (array) $paths;
    
    foreach($paths as $i=>$path) {
      if (!\realpath($path)) unset($paths[$i]);
    }

    return $paths;
  }

  public static function join(string ...$paths) : string
  {
    return self::normalize(\join(self::BSEP, $paths));
  }

  public static function pathname(string $path)
  {
    $path = self::separate($path);
    return \substr($path, \strlen(self::rootname($path)));
  }

  public static function normalize(string $path)
  {
    return self::normalized(self::rootname($path), self::pathname($path));
  }

  public static function resolve(string ...$paths)
  {
    self::prepend($paths, \getcwd());
    return self::normalize(self::makeResolver(self::separateMap($paths)));
  }
}
?>