<?php

declare(strict_types=1);

namespace Path;

use Path\Linux\Linux;
use Path\Win32\Win32;

use Path\Model\PathBlueprint;

class Path extends Base
{
  use PathBlueprint;
  
  protected const regsep = self::REGSEP;
  public const delimiter = self::DELIMITER;
  public const sep = self::SEP;
  public const win32 = Win32::class;
  public const posix = Linux::class;
}
?>