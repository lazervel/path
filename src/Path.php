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

namespace Path;

use Path\Model\PathModel;
use Path\Win32\Win32;
use Path\Linux\Linux;

/**
 * Platform-independent method that performs tasks based on the detected operating system.
 * The PHP Path provides utlities help with handling or manipulating file and directory path.
 * 
 * @uses   PathModel
 * 
 * This method automatically detects whether the script is running on a Windows (Win32)
 * or POSIX-compliant (Linux/MacOS) system and executes the appropriate code for that platform.
 * 
 * @author Shahzada Modassir <shahzadamodassir@gmail.com>
 * @author Shahzadi Afsara   <shahzadiafsara@gmail.com>
 */
class Path
{
  /**
   * Working as Blueprint
   * Using PathModel trait to provides and access path-related methods and functionalities.
   */
  use PathModel;

  /**
   * Representing the directory separator for the current platform.
   * 
   * @var string sep
   */
  public const sep   = \DIRECTORY_SEPARATOR;

  /**
   * Representing the class name of the posix (Linux/MacOs) platform.
   * 
   * @var \Path\Linux\Linux posix
   */
  public const posix = Linux::class;

  /**
   * Representing the class name of the win32 (Windows) platform.
   * 
   * @var \Path\Win32\Win32 win32
   */
  public const win32 = Win32::class;

  /**
   * !Important: For internal use only.
   * Representing the directory separator RegExp source for the current platform.
   * 
   * @var string regsep
   */
  protected const regsep = self::sep === Win32::sep ? '\\\\' : '\/';

  /**
   * Representing the delimiter for the current platform.
   * 
   * @var string delimiter
   */
  public const delimiter = self::sep === Win32::sep ? Win32::delimiter : Linux::delimiter;
}
?>