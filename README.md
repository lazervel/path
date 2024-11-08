# PHP Path

PHP Path Help with handling or manipulating file and directory path.

![Banner](https://raw.githubusercontent.com/lazervel/assets/main/banners/path.png)

<p align="center">
<a href="https://github.com/shahzadamodassir"><img src="https://img.shields.io/badge/Author-Shahzada%20Modassir-%2344cc11?style=flat-square"/></a>
<a href="LICENSE"><img src="https://img.shields.io/github/license/lazervel/path?style=flat-square"/></a>
<a href="https://packagist.org/packages/filesys/path"><img src="https://img.shields.io/packagist/dt/filesys/path.svg?style=flat-square" alt="Total Downloads"></img></a>
<a href="https://github.com/lazervel/path/stargazers"><img src="https://img.shields.io/github/stars/lazervel/path?style=flat-square"/></a>
<a href="https://github.com/lazervel/path/releases"><img src="https://img.shields.io/github/release/lazervel/path.svg?style=flat-square" alt="Latest Version"></img></a>
</p>

## Installation

Installation is super-easy via [Composer](https://getcomposer.org)

```bash
composer require filesys/path
```

or add it by hand to your `composer.json` file.

- [Path::basename($path[, $suffix])](#pathbasenamepath-suffix)
- [Path::callMap($method, $args)](#pathcallmapmethod-args)
- [Path::canonicalize($path)](#pathcanonicalizepath)
- [Path::combine($paths, $names)](#pathcombinepaths-names)
- [Path::checkLength($path)](#pathchecklengthpath)
- [Path::delimiter](#pathdelimiter)
- [Path::dirname($path[, $suffix, $levels])](#pathdirnamepath-suffix-levels)
- [Path::extname($path)](#pathextnamepath)
- [Path::filename($path)](#pathfilenamepath)
- [Path::format($pathObject)](#pathformatpathobject)
- [Path::getcwd($path)](#pathgetcwdpath)
- [Path::info($path)](#pathinfopath)
- [Path::isAbsolute($path)](#pathisabsolutepath)
- [Path::isLocal($path)](#pathislocalpath)
- [Path::isURIPath($path)](#pathisuripathpath)
- [Path::join([...$paths])](#pathjoinpaths)
- [Path::normalize($path)](#pathnormalizepath)
- [Path::optimize($path)](#pathoptimizepath)
- [Path::parse($path)](#pathparsepath)
- [Path::pathname($path)](#pathpathnamepath)
- [Path::pathToURL($path, $origin[, ?$query, ?$hash])](#pathpathtourlpath-origin-query-hash)
- [Path::posix](#pathposix)
- [Path::relative($from, $to)](#pathrelativefrom-to)
- [Path::resolve($path)](#pathresolvepath)
- [Path::rootname($path)](#pathrootnamepath)
- [Path::sep](#pathsep)
- [Path::tmp($name)](#pathtmpname)
- [Path::toNamespacedPath($path)](#pathtonamespacedpathpath)
- [Path::UrlToPath($url)](#pathurltopathurl)
- [Path::win32](#pathwin32)

```php
use Path\Path;
require 'vendor/autoload.php';
```

## Path::basename($path[, $suffix])
```php
Path::basename('C:/xampp/htdocs/example.html');
// Returns: 'example.html'

Path::basename('C:/xampp/htdocs/example.html', '.html');
// Returns: 'example'
```

```php
Path::basename('/home/local/user/example.html');
// Returns: 'example.html'

Path::basename('/home/local/user/example.html', '.html');
// Returns: 'example'
```

## Path::callMap($method, $args)
```php

```

## Path::canonicalize($path)
```php
Path::canonicalize('C:/XamPP/HtDocS/DatA/comPoseR.jSon');
// Returns: 'C:\xampp\htdocs\data\composer.json'

Path::posix::canonicalize('/path/composer.json');
// Returns: 'G:\path\composer.json'
```

## Path::combine($paths, $names)
```php
Path::combine(['C:/xampp/htdocs'], ['example.html']);
// Returns: ['C:\xampp\htdocs\example.html']

Path::combine(['C:/xampp/htdocs', '/path'], ['example.html']);
// Returns: ['C:\xampp\htdocs\example.html', '\path\example.html']

Path::combine(['C:/xampp/htdocs'], ['example.html', 'foo.txt']);
// Returns: ['C:\xampp\htdocs\example.html', 'C:\xampp\htdocs\foo.txt']

Path::combine(['C:/xampp/htdocs', '/path'], ['example.html', 'foot.txt', '.env']);
// Returns: ['C:\xampp\htdocs\example.html', 'C:\xampp\htdocs\foot.txt', 'C:\xampp\htdocs\.env', '\path\example.html', '\path\foot.txt', '\path\.env']

/**
 * For POSIX (Linux/macOs) operating system.
 */
Path::posix::combine(['C:\xampp\htdocs'], ['example.html']);
// Returns: ['C:/xampp/htdocs/example.html']

Path::posix::combine(['C:\xampp\htdocs', '\path'], ['example.html']);
// Returns: ['C:/xampp/htdocs/example.html', '/path/example.html']

Path::posix::combine(['C:\xampp\htdocs'], ['example.html', 'foo.txt']);
// Returns: ['C:/xampp/htdocs/example.html', 'C:/xampp/htdocs/foo.txt']

Path::posix::combine(['C:\xampp\htdocs', '\path'], ['example.html', 'foot.txt', '.env']);
// Returns: ['C:/xampp/htdocs/example.html', 'C:/xampp/htdocs/foot.txt', 'C:/xampp/htdocs/.env', '/path/example.html', '/path/foot.txt', '/path/.env']
```

## Path::checkLength($path)
```php
// Check maximum path length on your system use \PHP_MAXPATHLEN constant.
Path::checkLength('your-path');

// Returns: if given path of length are valid so return (void) otherwise throwing RTException Error.
// PHP Fatal error:  Uncaught Path\Exception\RTException: Invalid path because path length exceeds [2048] characters.
```

## Path::delimiter
```php
```

## Path::dirname($path[, $suffix, $levels])
```php
```

## Path::extname($path)
```php
Path::extname('C:/xampp/htdocs/example.html');
// Returns: '.html'

Path::extname('index.coffee.md');
// Returns: '.md'

Path::extname('index.');
// Returns: '.'

Path::extname('index');
// Returns: ''

Path::extname('.index');
// Returns: '.index'

Path::extname('C:/xampp/htdocs/example.md');
// Returns: '.md' 
```

## Path::filename($path)
```php
```

## Path::format($pathObject)
```php
```

## Path::getcwd($path)
```php
```

## Path::info($path)
```php
```

## Path::isAbsolute($path)
```php
```

## Path::isLocal($path)
```php
```

## Path::isURIPath($path)
```php
```

## Path::join([...$paths])
```php
```

## Path::normalize($path)
```php
```

## Path::optimize($path)
```php
```

## Path::parse($path)
```php
```

## Path::pathname($path)
```php
```

## Path::pathToURL($path, $origin[, ?$query, ?$hash])
```php
```

## Path::posix
```php
```

## Path::relative($from, $to)
```php
```

## Path::resolve($path)
```php
```

## Path::rootname($path)
```php
```

## Path::sep
```php
```

## Path::tmp($name)
```php
```

## Path::toNamespacedPath($path)
```php
```

## Path::UrlToPath($url)
```php
```

## Path::win32
```php
```