# PHP Path ![verified](https://raw.githubusercontent.com/lazervel/assets/main/icons/verified.png)

PHP Path Help with handling or manipulating file and directory path.

![Banner](https://raw.githubusercontent.com/lazervel/assets/main/banners/path.png)

<p align="center">
<a href="https://github.com/shahzadamodassir"><img src="https://img.shields.io/badge/Author-Shahzada%20Modassir-%2344cc11?style=flat-square"/></a>
<a href="LICENSE"><img src="https://img.shields.io/github/license/lazervel/path?style=flat-square"/></a>
<a href="https://packagist.org/packages/filesys/path"><img src="https://img.shields.io/packagist/dt/filesys/path.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://github.com/lazervel/path/stargazers"><img src="https://img.shields.io/github/stars/lazervel/path?style=flat-square"/></a>
<a href="https://github.com/lazervel/path/releases"><img src="https://img.shields.io/github/release/lazervel/path.svg?style=flat-square" alt="Latest Version"></a>
<a href="https://github.com/lazervel/path/graphs/contributors"><img src="https://img.shields.io/github/contributors/lazervel/path?style=flat-square" alt="Contributors"></a>
<a href="/"><img src="https://img.shields.io/github/repo-size/lazervel/path?style=flat-square" alt="Repository Size"></a>
</p>

## Composer Installation

Installation is super-easy via [Composer](https://getcomposer.org)

```bash
composer require filesys/path
```

or add it by hand to your `composer.json` file.

## The Features of PHP Path
- [Path::basename($path[, $suffix])](#pathbasenamepath-suffix)
- [Path::canonicalize($path)](#pathcanonicalizepath)
- [Path::changeExt($path, $newExt)](#pathchangeextpath-newext)
- [Path::combine($paths, $names)](#pathcombinepaths-names)
- [Path::checkLength($path)](#pathchecklengthpath)
- [Path::delimiter](#pathdelimiter)
- [Path::dirname($path[, $suffix, $levels])](#pathdirnamepath-suffix-levels)
- [Path::extname($path)](#pathextnamepath)
- [Path::filename($path)](#pathfilenamepath)
- [Path::format($pathObject)](#pathformatpathobject)
- [Path::getcwd()](#pathgetcwd)
- [Path::hasExt($path)](#pathhasextpath)
- [Path::info()](#pathinfo)
- [Path::isAbsolute($path)](#pathisabsolutepath)
- [Path::isLocal($path)](#pathislocalpath)
- [Path::isURIPath($path)](#pathisuripathpath)
- [Path::join([...$paths])](#pathjoinpaths)
- [Path::localBase($paths)]()
- [Path::normalize($path)](#pathnormalizepath)
- [Path::optimize($path)](#pathoptimizepath)
- [Path::parse($path)](#pathparsepath)
- [Path::pathname($path)](#pathpathnamepath)
- [Path::pathToURL($path, $origin[, ?$query, ?$hash])](#pathpathtourlpath-origin-query-hash)
- [Path::posix](#pathposix)
- [Path::relative($from, $to)](#pathrelativefrom-to)
- [Path::removeExt($path)](#pathremoveextpath)
- [Path::resolve(...$paths)](#pathresolvepath)
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

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- `$suffix` [string](https://www.php.net/manual/en/language.types.string)  An optional suffix to remove
- Return: [string](https://www.php.net/manual/en/language.types.string)

The `Path::basename()` method returns the last portion of a path, similar to the Unix basename command. Trailing `directory separators` are ignored.

For example, on POSIX:

```php
Path::basename('C:\\xampp\\htdocs\\example.html');
// Returns: 'example.html'

Path::basename('C:\\xampp\\htdocs\\example.html', '.html');
// Returns: 'example'
```

On Windows:

```php
Path::basename('/home/local/user/example.html');
// Returns: 'example.html'

Path::basename('/home/local/user/example.html', '.html');
// Returns: 'example'
```

## Path::canonicalize($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

canonicalize function converts a given path into its canonical (absolute and standardized) form. It resolves relative paths, symbolic links, and eliminates redundant or unnecessary components
like '.' and '..' to return a clean and absolute version of the path.

For example, on POSIX:

```php
Path::canonicalize('C:\\XamPP\\HtDocS\\DatA\\comPoseR.jSon');
// Returns: 'C:\\xampp\\htdocs\\data\\composer.json'
```

On Windows:

```php
Path::canonicalize('/path/composer.json');
// Returns: 'G:\\path\\composer.json'
```

## Path::changeExt($path, $newExt)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- `$newExt` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Returns a path string with changed initial extension to replaced new extension.<br>
If path extension and givent new extension are same then changeExt will be not modify and return path with initial extension.

```php
Path::changeExt('/foo/bar/baz/asdf/quux.html', '.php');
// Returns: '/foo/bar/baz/asdf/quux.php'

Path::changeExt('/foo/bar/baz/asdf/vector.gif', 'svg');
// Returns: '/foo/bar/baz/asdf/vector.svg'
```

## Path::combine($paths, $names)

**Parameters:**
- `$paths` [array](https://www.php.net/manual/en/language.types.array)
- `$names` [array](https://www.php.net/manual/en/language.types.array)
- Return: [array](https://www.php.net/manual/en/language.types.array)

Creates an array by using one array for paths and another for its names. And creates multiple files combined with paths from to file names.

For example, on POSIX:

```php
Path::combine(['/xampp/htdocs'], ['example.html', 'foo.txt']);
// Returns: ['/xampp/htdocs/example.html', '/xampp/htdocs/foo.txt']

Path::combine(['/xampp/htdocs'], ['example.html']);
// Returns: ['/xampp/htdocs/example.html']

Path::combine(['/xampp/htdocs', '/path'], ['example.html']);
// Returns: ['/xampp/htdocs/example.html', '/path/example.html']

Path::combine(['/xampp/htdocs', '/path'], ['example.html', 'foot.txt', '.env']);
// Returns: ['/xampp/htdocs/example.html', '/xampp/htdocs/foot.txt', '/xampp/htdocs/.env', '\path\example.html', '\path\foot.txt', '\path\.env']
```

On Windows:

```php
Path::combine(['C:\\xampp\\htdocs'], ['example.html', 'foo.txt']);
// Returns: ['C:\\xampp\\htdocs\\example.html', 'C:\\xampp\\htdocs\\foo.txt']

Path::combine(['C:\\xampp\\htdocs'], ['example.html']);
// Returns: ['C:\\xampp\\htdocs\\example.html']

Path::combine(['C:\\xampp\\htdocs', '\\path'], ['example.html']);
// Returns: ['C:\\xampp\\htdocs\\example.html', '\\path\\example.html']

Path::combine(['C:\\xampp\\htdocs', '\\path'], ['example.html', 'foot.txt', '.env']);
// Returns: ['C:\\xampp\\htdocs\\example.html', 'C:\\xampp\\htdocs\\foot.txt', 'C:\\xampp\\htdocs\\.env', '\\path\\example.html', '\\path\\foot.txt', '\\path\\.env']
```

## Path::checkLength($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [void](https://www.php.net/manual/en/language.types.void)

Check a valid path length and report exception.

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

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- `$suffix` [string](https://www.php.net/manual/en/language.types.string)
- `$levels` [int](https://www.php.net/manual/en/language.types.integer)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Get the directory name of a given path with optional suffix removal and level adjustment. This function returns the parent directory's path of the provided file or directory path. It also allows for an optional suffix to be removed from the base name and specifies how many levels up the directory to go.

For example, on POSIX:

```php
Path::dirname('/foo/bar/baz/asdf/quux\\abcd\\xyz');
// Returns: '/foo/bar/baz/asdf'
```

On Windows:

```php
Path::dirname('/foo/bar/baz/asdf/quux');
// Returns: '/foo/bar/baz/asdf'

Path::dirname('/foo/bar/baz/asdf/quux\\abcd\\xyz');
// Returns: '/foo/bar/baz/asdf/quux\abcd'
```

## Path::extname($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Returns extname method a path extension name from given path, Its used to get file extention.

```php
Path::extname('C:\\xampp\\htdocs\\example.html');
// Returns: '.html'

Path::extname('index.coffee.md');
// Returns: '.md'

Path::extname('index.');
// Returns: '.'

Path::extname('index');
// Returns: ''

Path::extname('.index');
// Returns: '.index'

Path::extname('C:\\xampp\\htdocs\\example.md');
// Returns: '.md' 
```

## Path::filename($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Returns a filename without extention of given path.

```php
Path::filename('/foo/bar/baz/asdf/quux.html');
// Returns: 'quux.html'

Path::filename('example.txt');

Path::filename('/');
// Returns: ''
// Returns: 'example.txt'

Path::filename('example');
// Returns: 'example'

Path::filename('C:\\path\\dir\\file.txt');
// Returns: 'file.txt'
```

## Path::format($pathObject)

**Parameters:**
- `$pathObject` [array](https://www.php.net/manual/en/language.types.array)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Returns a path string from an array-object - the opposite of parse(). format method are same work as `phpinfo` method But there install the extra property `root` property to getting current root [dir] of path

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

Retrieves the current working directory based on the operating system. This method returns the current working directory in a format appropriate for the platform. For `POSIX` systems, it returns a path like `/xampp/htdocs/`, while for Windows systems, it returns a path like `C:/xampp/htdocs/`.

For example, on POSIX:

```php
// If the current working directory is /xampp/htdocs,
Path::getcwd(); // Returns: /xampp/htdocs
```

On Windows:

```php
// If the current working directory is C:\\xampp\\htdocs,
// returns with drive LIKE (eg: C:,D:,F: etc.)
Path::getcwd(); // Returns: C:\\xampp\\htdocs
```

## Path::hasExt($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [bool](https://www.php.net/manual/en/language.types.boolean)

hasExt method will check extension exists or not exists of given path and matcher Extensions, if Given extensions in matched path extension then return true, Otherwise return false.

```php
Path::hasExt('/foo/bar/baz/asdf/vector.png', ['.gif', '.jpg', '.png']); // Returns: true
Path::hasExt('/foo/bar/baz/asdf/vector.gif', '.gif');                   // Returns: true
Path::hasExt('/foo/bar/baz/asdf/vector.gif', 'gif');                    // Returns: true
Path::hasExt('/foo/bar/baz/asdf/vector.gif', ['gif', 'jpeg', 'png']);   // Returns: true

Path::hasExt('/foo/bar/baz/asdf/vector.pdf', ['.gif', '.jpg', '.png']); // Returns: false
Path::hasExt('/foo/bar/baz/asdf/vector.gif', '.svg');                   // Returns: false
Path::hasExt('/foo/bar/baz/asdf/vector.gif', 'png');                    // Returns: false
Path::hasExt('/foo/bar/baz/asdf/vector.gif', ['svg', 'jpeg', 'png']);   // Returns: false
```

## Path::info()

Returns information about a file path.

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
Path::info('C:\\xampp\\htdocs\\path\\Path.php');
// Returns: stdClass Object (
//   [dirname] => C:/xampp/htdocs/path
//   [basename] => Path.php
//   [extension] => php
//   [filename] => Path
//   [root] => C:\
// )
```

## Path::isAbsolute($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [bool](https://www.php.net/manual/en/language.types.boolean)

Determines whether {path} is an absolute path. An absolute path will always resolve to the same location,regardless of the working directory.

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

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [bool](https://www.php.net/manual/en/language.types.boolean)

isLocal function checks whether the provided path is local or not. It determines if the given path refers to a local file or directory.

```php
Path::isLocal('C:Users\JohnDoe\Documents\file.txt');  // Returns: 'false'
Path::isLocal('//home/user/file.txt');                // Returns: 'false'
Path::isLocal('C:\Program Files\file//file.txt');     // Returns: 'false'
Path::isLocal('C:/Windows\\System32');                // Returns: 'false'
Path::isLocal('D:\\Data\report.pdf');                 // Returns: 'false'
Path::isLocal('C:\Users\JohnDoe\Documents\file.txt'); // Returns: 'true'
Path::isLocal('D:\Projects\Code\index.html');         // Returns: 'true'
Path::isLocal('/home/user/documents/report.pdf');     // Returns: 'true'
Path::isLocal('\\ServerName\SharedFolder\image.png'); // Returns: 'true'
Path::isLocal('E:\Music\Rock\song.mp3');              // Returns: 'true'
```

## Path::isURIPath($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [bool](https://www.php.net/manual/en/language.types.boolean)

Check if the given path is a valid network path. A valid network path starts with two backslashes `\\` or `//` followed by the server name, and can include subdirectories.

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

**Parameters:**
- `...$paths` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Join all arguments together and normalize the resulting path.

```php
Path::join('/foo', 'bar', 'baz/asdf', 'quux', '..');
// Returns: '/foo/bar/baz/asdf'

Path::join('foo', [], 'bar');
// Throws TypeError: Path\Path::join(): Argument #2 must be of type string, array given.
```

## Path::localBase($paths)
```php
// Temporary Unavailable
```

## Path::normalize($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Normalize a string path, reducing `..` and `.` parts. When multiple slashes are found, they're replaced by a single one; when the path contains a trailing slash, it is preserved. On Windows backslashes are used.

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

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [array](https://www.php.net/manual/en/language.types.array)&lt;[string](https://www.php.net/manual/en/language.types.string),[string](https://www.php.net/manual/en/language.types.string)&gt;

Returns an object from a path string - the opposite of `format()`.

For example, on POSIX:

```php
Path::parse('/home/user/dir/file.txt');
// Returns:
// [
//   'root' => '/',
//   'dir' => '/home/user/dir',
//   'base' => 'file.txt',
//   'ext' => '.txt',
//   'name' => 'file'
// ]
```

```
┌─────────────────────┬────────────┐
│          dir        │    base    │
├──────┬              ├──────┬─────┤
│ root │              │ name │ ext │
"  /    home/user/dir / file  .txt "
└──────┴──────────────┴──────┴─────┘
(All spaces in the "" line should be ignored. They are purely for formatting.)
```

On Windows:

```php
path.parse('C:\\path\\dir\\file.txt');
// Returns:
// [
//   'root' => 'C:\\',
//   'dir' => 'C:\\path\\dir',
//   'base' => 'file.txt',
//   'ext' => '.txt',
//   'name' => 'file'
// ]
```

```
┌─────────────────────┬────────────┐
│          dir        │    base    │
├──────┬              ├──────┬─────┤
│ root │              │ name │ ext │
" C:\      path\dir   \ file  .txt "
└──────┴──────────────┴──────┴─────┘
(All spaces in the "" line should be ignored. They are purely for formatting.)
```

## Path::pathname($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Get the path name of a given file or directory. This function returns the path name without any suffix or modification and without drive.

For example, on POSIX:

```php
Path::pathname('//var/www/httpdocs/config/config.yml');
// Returns: '/var/www/httpdocs/config/config.yml'

Path::pathname('C:////temp\\\\/\\/\\/foo/bar');
// Returns: 'C:/temp\foo/bar'

Path::pathname('/');
// Returns: '/'

Path::pathname('/var/www/httpdocs/config/config.yml');
// Returns: '/var/www/httpdocs/config/config.yml'
```

On Windows:

```php
// Handle Network Path, Here network path are '\\\\var\\www'
Path::pathname('\\\\var\\www\\httpdocs\\config\\config.yml');
// Returns: '\\httpdocs\\config\\config.yml'

Path::pathname('C:////temp\\\\/\\/\\/foo/bar');
// Returns: '\\temp\\foo\\bar'

Path::pathname('\\var\\www\\httpdocs\\config\\config.yml');
// Returns: '\\var\\www\\httpdocs\\config\\config.yml'

Path::pathname('\\\\var\\www\\');
// Returns: '\\'

Path::pathname('C:');
// Returns: ''

Path::pathname('C:\\');
// Returns: '\\'

Path::pathname('\\\\var\\www');
// Returns: ''

Path::pathname('G:var\\www\\httpdocs\\config\\config.yml');
// Returns: 'var\\www\\httpdocs\\config\\config.yml'
```

Since Windows recognizes multiple path separators, both separators will be replaced by instances of the Windows preferred separator (`\`):

## Path::pathToURL($path, $origin[, ?$query, ?$hash])

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- `$origin` [string](https://www.php.net/manual/en/language.types.string)
- `$query` [string](https://www.php.net/manual/en/language.types.string)
- `$hash` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

pathToURL - Convert path to url, Returns path to url combination with `(e.g., path, origin, ?query, ?hash)`.

**Notice:** Don't use syntax `Path::win32::pathToURL()` or `Path::posix::pathToURL()`, This a common bugs. but don't worry we fix this bugs to next expected version `[v10.2.0]`.

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

**Parameters:**
- `$from` [string](https://www.php.net/manual/en/language.types.string)
- `$to` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Solve the relative path from `from` to `to` based on the current working directory. At times we have two absolute paths, and we need to derive the relative path from one to the other. This is actually the reverse transform of path.resolve.

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

## Path::removeExt($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Returns a path string with removed path extension.

```php
Path::removeExt('/var/www/web.php');
// Returns: '/var/www/web'

Path::removeExt('.env.local');
// Returns: '.env'

Path::removeExt('.html');
// Returns: '' bugs detected

Path::removeExt('file.txt');
// Returns 'file'

Path::removeExt('G:/path/.github');
// Returns: 'G:/path/' bugs detected
```

## Path::resolve(...$path)

**Parameters:**
- `...$paths` [string](https://www.php.net/manual/en/language.types.string) A sequence of path segments.
- Return: [string](https://www.php.net/manual/en/language.types.string)

The right-most parameter is considered `to`. Other parameters are considered an array of `from`. Starting from leftmost `from` parameter, resolves `to` to an absolute path. If `to` isn't already absolute, `from` arguments are prepended in right to left order, until an absolute path is found. If after using all `from` paths still no absolute path is found, the current working directory is used as well. The resulting path is normalized, and trailing slashes are removed unless the path gets resolved to the root directory.

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
// Returns: 'G:\\foo\\bar\\baz'

Path::resolve('/foo/bar', '/tmp/file/');
// Returns: 'G:\\tmp\\file'

Path::resolve('wwwroot', 'static_files/png/', '../gif/image.gif');
// If the current working directory is C:\\xampp\\htdocs/,
// this returns 'C:\\xampp\\htdocs\\wwwroot\\static_files\\gif\\image.gif'
```

## Path::rootname($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Retrieves the root name of the path. This method extracts the root component of a given path, which can be useful for determining the base directory or starting point for further path manipulations.

For example, on POSIX:

```php
Path::rootname('C:\\xampp\\htdocs\\');
// Returns: ''

Path::rootname('/var/ww/httpdocs');
// Returns: '/'

Path::rootname('C:\\');
// Returns: ''

Path::rootname('G:');
// Returns: ''

Path::rootname('//var/www/httpdocs');
// Returns: '/'
```

On Windows:

```php
Path::rootname('C:\\xampp\\htdocs\\');
// Returns: 'C:\\'

Path::rootname('/var/ww/httpdocs');
// Returns: '\\'

Path::rootname('C:\\');
// Returns: 'C:\\'

Path::rootname('G:');
// Returns: 'G:'

Path::rootname('//var/www/httpdocs');
// Returns: '\\\\var\\www\\'
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

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

Returns tmp name, To make a tmp name with dirname of given path.

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
// Returns: 'foot\\bar\\.!!\\.!RBDZ'
// Returns: 'foot\\bar\\.!!\\.!NPia'
// Returns: 'foot\\bar\\.!!\\.!0Kbx'
```

## Path::toNamespacedPath($path)

**Parameters:**
- `$path` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

On `Windows` systems only, returns an equivalent namespace-prefixed path for the given path. If path is not a string, path will be returned without modifications. This method is meaningful only on Windows system. On `POSIX` systems, the method is non-operational and always returns path without modifications.

```php
Path::toNamespacedPath('\\foo\\bar/baz\\asdfquux\\abcd\\xyz');
// Returns: '\\\\?\\G:\\foo\\bar\\baz\\asdfquux\\abcd\\xyz'

Path::toNamespacedPath('//foo\\bar/baz\\asdfquux\\abcd\\xyz');
// Returns: '\\\\?\\UNC\\foo\\bar\\baz\\asdfquux\\abcd\\xyz'
```

## Path::UrlToPath($url)

**Parameters:**
- `$url` [string](https://www.php.net/manual/en/language.types.string)
- Return: [string](https://www.php.net/manual/en/language.types.string)

To convert url `https://example.com/home/parent/current/path` to `/home/parent/current/path`.

For example, on POSIX:

```php
Path::UrlToPath('https://www.example.com/server/auth/client?id=1');
// Returns: 'G:\\server\\auth\\client'

Path::UrlToPath('https://www.example.com/server/auth/client');
// Returns: 'G:\\server\\auth\\client'

Path::UrlToPath('https://www.example.com/server/auth/client?id=1#root');
// Returns: 'G:\\server\\auth\\client'
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

## Resources
- [Report issue](https://github.com/lazervel/path/issues) and [send Pull Request](https://github.com/lazervel/path/pulls) in the [main Lazervel repository](https://github.com/lazervel/path)
