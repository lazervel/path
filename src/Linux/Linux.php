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

namespace Path\Linux;

use Path\Model\PathModel;

/**
 * POSIX-specific method for handling tasks.
 * Provides utlities help with handling or manipulating file and directory path.
 * 
 * @uses   PathModel
 * 
 * This method is intended for use on POSIX-compliant systems, such as Linux
 * and macOS. It may leverage POSIX-specific functionalities or commands
 * that do not exist in the Windows environment.
 * 
 * @author Shahzada Modassir <shahzadamodassir@gmail.com>
 * @author Shahzadi Afsara   <shahzadiafsara@gmail.com>
 */
class Linux
{
  /**
   * Working as Blueprint
   * Using PathModel trait to provides and access path-related methods and functionalities.
   */
  use PathModel;

  /**
   * Representing the delimiter for the (Linux/MacOs) platform.
   * 
   * @var string delimiter
   */
  public const delimiter = ':';

  /**
   * Representing the directory separator for the (Linux/MacOs) platform.
   * 
   * @var string sep
   */
  public const sep = '/';

  /**
   * !Important: For internal use only.
   * Representing the directory separator RegExp source for the (Linux/MacOs) platform.
   * 
   * @var string regsep
   */
  protected const regsep = '\/';
}
?>