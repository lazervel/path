<?php

declare(strict_types=1);

namespace Path\Win32;

use Path\Model\PathBlueprint;
use Path\Base;

final class Win32 extends Base
{
  use PathBlueprint;
  
  protected const regsep = self::WIN32REGSEP;
  public const delimiter = ';';
  public const sep = '\\';
}
?>