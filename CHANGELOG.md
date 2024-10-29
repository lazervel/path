# Release Notes
## [Unreleased](https://github.com/lazervel/path/compare/v7.3.0...v8.0.0)

## [8.0.0](https://github.com/lazervel/path/compare/v7.3.0...v8.0.0)
- [BC BREAK] Since v8.x, Due to significant updates in the library, all previous configurations may no longer work as expected. Users will need to reconfigure their settings to align with the new structure and behavior introduced in this update.
- Review the updated documentation for `Path::resolve()`, `Path::normalize`, `Path::join()`, and `Path::optimize()` and `Path::relative()` to understand the new behavior.
- Ensure that any calls to these methods are updated to accommodate these changes.

## [7.3.0](https://github.com/lazervel/path/releases/tag/v7.3.0) - 31 October 2024
- Added `Path::canonicalize()` method in `path` develope by [@shahzadamodassir](https://github.com/shahzadamodassir)
- Added `Path::optimize()` method in `path` develope by [@shahzadamodassir](https://github.com/shahzadamodassir)

## [7.2.0](https://github.com/lazervel/path/releases/tag/v7.2.0) - 30 October 2024
- Added `Path::isAbsolute()` method in `path` develope by [@shahzadamodassir](https://github.com/shahzadamodassir)
- Added `Path::isLocal()` method in `path` develope by [@shahzadamodassir](https://github.com/shahzadamodassir)

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