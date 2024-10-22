<?php

declare(strict_types=1);

namespace Path;

use Path\Model\PathBlueprint;
use Path\Win32\Win32;
use Path\Linux\Linux;

class Path
{
  use PathBlueprint;

  public const sep = \DIRECTORY_SEPARATOR;
  public const win32 = Win32::class;
  public const posix = Linux::class;
  protected const isPosix = self::sep === Win32::sep ? false : true;
  protected const regsep = self::sep === Win32::sep ? '\\\\' : '\/';
  public const delimiter = self::sep === Win32::sep ? Win32::delimiter : Linux::delimiter;
}
?>