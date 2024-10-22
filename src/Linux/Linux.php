<?php

declare(strict_types=1);

namespace Path\Linux;

use Path\Model\PathBlueprint;

final class Linux
{
  use PathBlueprint;

  protected const regsep = '\/';
  public const delimiter = ':';
  public const sep = '/';
  protected const isPosix = true;
}
?>