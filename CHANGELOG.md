# Release Notes
## [Unreleased](https://github.com/lazervel/path/releases/tag/v7.0.0)

## [7.1.0](https://github.com/lazervel/path/releases/tag/v7.1.0) - 30 October 2024
- Added `Path::info()` method to get information about a file path, develope by [@shahzadamodassir](https://github.com/shahzadamodassir)
- Added `Path::basename()` method develope by [@shahzadamodassir](https://github.com/shahzadamodassir)
- Added `Path::extname()` method develope by [@shahzadamodassir](https://github.com/shahzadamodassir)
- Added `Path::filename()` method develope by [@shahzadamodassir](https://github.com/shahzadamodassir)
- Added `Path::tmp()` method develope by [@shahzadamodassir](https://github.com/shahzadamodassir)
- Added `Path::format()` method develope by [@shahzadamodassir](https://github.com/shahzadamodassir)
- Added `Path::parse()` method develope by [@shahzadamodassir](https://github.com/shahzadamodassir)

## [7.0.0](https://github.com/lazervel/path/releases/tag/v7.0.0) - 29 October 2024
- Removed `Path::separate()` method that is no longer available.
- Removed `Path::real()` method that is no longer available.
- Renamed method `Path::filePaths()` to `Path::combine()` for clarity and consistency.
- Fixed bugs in method `Path::combine` that caused [e.g., incorrect output, duplicate combination, etc.].

## [6.0.0](https://github.com/lazervel/path/releases/tag/v6.0.0) - 29 October 2024
- Added new feature `Path::win32` property for `win32` (Windows) platform, develope by [@shahzadamodassir](https://github.com/shahzadamodassir)
- Added new feature `Path::posix` property for `posix` (Linux/macOs) platform, develope by [@shahzadamodassir](https://github.com/shahzadamodassir)

**Suggest:** We allow to use PHP `path` library version from [7.0.0](https://github.com/lazervel/path/releases/tag/v7.0.0) to [latest](https://github.com/lazervel/path/releases/latest) Recomended [latest](https://github.com/lazervel/path/releases/latest) version for best performance.