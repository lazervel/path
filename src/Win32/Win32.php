<?php

declare(strict_types=1);

namespace Path\Win32;

use Path\Model\PathBlueprint;

final class Win32
{
  use PathBlueprint;

  protected const isPosix = false;
  public const delimiter = ';';
  public const sep = '\\';
  protected const regsep = '\\\\';
}
?>