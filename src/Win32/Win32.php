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

namespace Path\Win32;

use Path\Model\PathModel;

/**
 * Windows-specific method for handling tasks.
 * Provides utlities help with handling or manipulating file and directory path.
 * 
 * @uses   PathModel
 * 
 * This method is designed to perform specific operations that are compatible
 * with Windows platforms (Win32). It may utilize Windows-specific APIs or 
 * features that are not available on POSIX systems.
 * 
 * @author Shahzada Modassir <shahzadamodassir@gmail.com>
 * @author Shahzadi Afsara   <shahzadiafsara@gmail.com>
 */
class Win32
{
  /**
   * Working as Blueprint
   * Using PathModel trait to provides and access path-related methods and functionalities.
   */
  use PathModel;

  /**
   * Representing the delimiter for the (Windows) platform.
   * 
   * @var string delimiter
   */
  public const delimiter = ';';

  /**
   * Representing the directory separator for the (Windows) platform.
   * 
   * @var string sep
   */
  public const sep = '\\';

  /**
   * !Important: For internal use only.
   * Representing the directory separator RegExp source for the (Windows) platform.
   * 
   * @var string regsep
   */
  protected const regsep = '\\\\';
}
?>