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

## PHP Path of Features
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
- [Path::getcwd()](#pathgetcwd)
- [Path::info()](#pathinfo)
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
- [Path::tmp($path)](#pathtmppath)
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

For example, on POSIX:

```php
Path::combine(['C:/xampp/htdocs'], ['example.html', 'foo.txt']);
// Returns: ['C:\xampp\htdocs\example.html', 'C:\xampp\htdocs\foo.txt']

Path::combine(['C:/xampp/htdocs'], ['example.html']);
// Returns: ['C:\xampp\htdocs\example.html']

Path::combine(['C:/xampp/htdocs', '/path'], ['example.html']);
// Returns: ['C:\xampp\htdocs\example.html', '\path\example.html']

Path::combine(['C:/xampp/htdocs', '/path'], ['example.html', 'foot.txt', '.env']);
// Returns: ['C:\xampp\htdocs\example.html', 'C:\xampp\htdocs\foot.txt', 'C:\xampp\htdocs\.env', '\path\example.html', '\path\foot.txt', '\path\.env']
```

On Windows:

```php
Path::posix::combine(['C:\xampp\htdocs'], ['example.html', 'foo.txt']);
// Returns: ['C:/xampp/htdocs/example.html', 'C:/xampp/htdocs/foo.txt']

Path::posix::combine(['C:\xampp\htdocs'], ['example.html']);
// Returns: ['C:/xampp/htdocs/example.html']

Path::posix::combine(['C:\xampp\htdocs', '\path'], ['example.html']);
// Returns: ['C:/xampp/htdocs/example.html', '/path/example.html']

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
### Throwing Error
**Path\Exception\RTException: Invalid path because path length exceeds [2048] characters.**

## Path::delimiter

Provides the platform-specific path delimiter:

- `;` for Windows
- `:` for POSIX

For example, on POSIX:

```php
echo getenv('PATH');
// Prints: '/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin'

explode(Path::delimiter, getenv('PATH'));
// Returns: ['/usr/bin', '/bin', '/usr/sbin', '/sbin', '/usr/local/bin']
```

On Windows:

```php
echo getenv('PATH');
// Prints: 'C:\Windows\system32;C:\Windows;C:\Program Files\node\'

explode(Path::delimiter, getenv('PATH'));
// Returns ['C:\\Windows\\system32', 'C:\\Windows', 'C:\\Program Files\\node\\']
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

For example, on POSIX:

```php
// If `dir`, `root` and `base` are provided,
// `${dir}${Path::sep}${base}`
// will be returned. `root` is ignored.
Path::format([
  'root' => '/ignored',
  'dir' => '/home/user/dir',
  'base' => 'file.txt',
]);
// Returns: '/home/user/dir/file.txt'

// `root` will be used if `dir` is not specified.
// If only `root` is provided or `dir` is equal to `root` then the
// platform separator will not be included. `ext` will be ignored.
Path::format([
  'root' => '/',
  'base' => 'file.txt',
  'ext' => 'ignored',
]);
// Returns: '/file.txt'

// `name` + `ext` will be used if `base` is not specified.
Path::format([
  'root' => '/',
  'name' => 'file',
  'ext' => '.txt',
]);
// Returns: '/file.txt'

// The dot will be added if it is not specified in `ext`.
Path::format([
  'root' => '/',
  'name' => 'file',
  'ext' => 'txt',
]);
// Returns: '/file.txt'
```

On Windows:

```php
Path::format([
  'dir' => 'C:\\path\\dir',
  'base' => 'file.txt',
]);
// Returns: 'C:\\path\\dir\\file.txt'
```

## Path::getcwd()

For example, on POSIX:

```php
// If the current working directory is /xampp/htdocs,
Path::getcwd(); // Returns: /xampp/htdocs
```

On Windows:

```php
// If the current working directory is C:/xampp/htdocs,
// returns with drive LIKE (eg: C:,D:,F: etc.)
Path::getcwd(); // Returns: C:/xampp/htdocs
```

## Path::info()

For example, on POSIX:

```php
Path::info('/home/local/user/example.html');
// Returns: stdClass Object (
//   [dirname] => /home/local/user
//   [basename] => example.html
//   [extension] => html
//   [filename] => example
//   [root] => /
// )
```

On Windows:

```php
Path::info('C:/xampp/htdocs/path/Path.php');
// Returns: stdClass Object (
//   [dirname] => C:/xampp/htdocs/path
//   [basename] => Path.php
//   [extension] => php
//   [filename] => Path
//   [root] => C:\
// )
```

## Path::isAbsolute($path)

For example, on POSIX:

```php
Path::isAbsolute('/foo/bar'); // Returns: true
Path::isAbsolute('/baz/..');  // Returns: true
Path::isAbsolute('qux/');     // Returns: false
Path::isAbsolute('.');        // Returns: false
```

On Windows:
```php
Path::isAbsolute('//server');    // Returns: true
Path::isAbsolute('\\\\server');  // Returns: true
Path::isAbsolute('C:/foo/..');   // Returns: true
Path::isAbsolute('C:\\foo\\..'); // Returns: true
Path::isAbsolute('bar\\baz');    // Returns: false
Path::isAbsolute('bar/baz');     // Returns: false
Path::isAbsolute('.');           // Returns: false
```

## Path::isLocal($path)
```php
```

## Path::isURIPath($path)

**Support:** working only Windows:

```php
Path::isURIPath('//home/local/user/'); // Returns: true
Path::isURIPath('//home/local');       // Returns: true
Path::isURIPath('//home/local/');      // Returns: true
Path::isURIPath('/server/foo/');       // Returns: false
Path::isURIPath('D:/');                // Returns: false
Path::isURIPath('//home/');            // Returns: false
Path::isURIPath('C:/xampp/htdocs/');   // Returns: false
```

## Path::join([...$paths])
```php
Path::join('/foo', 'bar', 'baz/asdf', 'quux', '..');
// Returns: '/foo/bar/baz/asdf'

Path::join('foo', [], 'bar');
// Throws TypeError: Path\Path::join(): Argument #2 must be of type string, array given.
```

## Path::normalize($path)

For example, on POSIX:

```php
Path::normalize('/foo/bar//baz/asdf/quux/..');
// Returns: '/foo/bar/baz/asdf'
```

On Windows:

```php
Path::normalize('C:\\temp\\\\foo\\bar\\..\\');
// Returns: 'C:\\temp\\foo\\'
```

Since Windows recognizes multiple path separators, both separators will be replaced by instances of the Windows preferred separator (`\`):

```php
Path::win32::normalize('C:////temp\\\\/\\/\\/foo/bar');
// Returns: 'C:\\temp\\foo\\bar'
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

**Notice:** Don't use syntax `Path::win32::pathToURL()` or `Path::posix::pathToURL()`, This a common bugs. but don't worry we fix this bugs to next expected version `[v10.0.0]`.

For example, on POSIX:

```php
Path::pathToURL('server/auth/client', 'https://www.example.com', 'id=1');
// Returns: 'https://www.example.com/server/auth/client?id=1'

Path::pathToURL('server/auth/client', 'https://www.example.com');
// Returns: 'https://www.example.com/server/auth/client'

Path::pathToURL('server/auth/client', 'https://www.example.com', '?id=1', '#root');
// Returns: 'https://www.example.com/server/auth/client?id=1#root'

Path::pathToURL('server/auth/client', 'https://www.example.com', '?id=1', 'root');
// Returns: 'https://www.example.com/server/auth/client?id=1#root'
```

On Windows:

```php
Path::pathToURL('G:\\server\\auth\\client', 'https://www.example.com', 'id=1');
// Returns: 'https://www.example.com/server/auth/client?id=1'

Path::pathToURL('G:\\server\\auth\\client', 'https://www.example.com');
// Returns: 'https://www.example.com/server/auth/client'

Path::pathToURL('G:\\server\\auth\\client', 'https://www.example.com', '?id=1', '#root');
// Returns: 'https://www.example.com/server/auth/client?id=1#root'

Path::pathToURL('G:\\server\\auth\\client', 'https://www.example.com', '?id=1', 'root');
// Returns: 'https://www.example.com/server/auth/client?id=1#root'
```

## Path::posix

The `Path::posix` property provides access to POSIX specific implementations of the `Path` methods.

The API is accessible via `Path\Path::posix` or `Path\Linux\Linux::class`.

## Path::relative($from, $to)

For example, on POSIX:

```php
Path::relative('/data/orandea/test/aaa', '/data/orandea/impl/bbb');
// Returns: '../../impl/bbb'
```

On Windows:

```php
Path::relative('C:\\orandea\\test\\aaa', 'C:\\orandea\\impl\\bbb');
// Returns: '..\\..\\impl\\bbb'
```

## Path::resolve($path)

For example, on POSIX:

```php
Path::resolve('/foo/bar', './baz');
// Returns: '/foo/bar/baz'

Path::resolve('/foo/bar', '/tmp/file/');
// Returns: '/tmp/file'

Path::resolve('wwwroot', 'static_files/png/', '../gif/image.gif');
// If the current working directory is /home/myself/node,
// this returns '/home/myself/node/wwwroot/static_files/gif/image.gif'
```

On Windows:

```php
Path::resolve('/foo/bar', './baz');
// Returns: 'G:\foo\bar\baz'

Path::resolve('/foo/bar', '/tmp/file/');
// Returns: 'G:\tmp\file'

Path::resolve('wwwroot', 'static_files/png/', '../gif/image.gif');
// If the current working directory is C:\xampp\htdocs/,
// this returns 'C:\xampp\htdocs\wwwroot\static_files\gif\image.gif'
```

## Path::rootname($path)
```php
```

## Path::sep

For example, on POSIX:

```php
explode(Path::sep, 'foo/bar/baz');
// Returns: ['foo', 'bar', 'baz']
```

On Windows:

```php
explode(Path::sep, 'foo\\bar\\baz');
// Returns: ['foo', 'bar', 'baz']
```

On Windows, both the forward slash (`/`) and backward slash (`\`) are accepted as path segment separators; however, the `Path` methods only add backward slashes (`\`).

## Path::tmp($path)

For example, on POSIX:

```php
// Path::tmp suffix random tmp name in given path value.
Path::tmp('foot/bar/baz');
// Returns: 'foot/bar/.!!/.!HyZq'
// Returns: 'foot/bar/.!!/.!XTfs'
// Returns: 'foot/bar/.!!/.!C80D'
```

On Windows:

```php
// Path::tmp suffix random tmp name in given path value.
Path::tmp('foot\\bar\\baz');
// Returns: 'foot\bar\.!!\.!RBDZ'
// Returns: 'foot\bar\.!!\.!NPia'
// Returns: 'foot\bar\.!!\.!0Kbx'
```

## Path::toNamespacedPath($path)
```php
```

## Path::UrlToPath($url)

For example, on POSIX:

```php
Path::UrlToPath('https://www.example.com/server/auth/client?id=1');
// Returns: 'G:\server\auth\client'

Path::UrlToPath('https://www.example.com/server/auth/client');
// Returns: 'G:\server\auth\client'

Path::UrlToPath('https://www.example.com/server/auth/client?id=1#root');
// Returns: 'G:\server\auth\client'
```

On Windows:

```php
Path::UrlToPath('https://www.example.com/server/auth/client?id=1');
// Returns: '/server/auth/client'

Path::UrlToPath('https://www.example.com/server/auth/client');
// Returns: '/server/auth/client'

Path::UrlToPath('https://www.example.com/server/auth/client?id=1#root');
// Returns: '/server/auth/client'
```

## Path::win32

The `Path::win32` property provides access to Windows specific implementations of the `Path` methods.

The API is accessible via `Path\Path::win32` or `Path\Win32\Win32::class`.