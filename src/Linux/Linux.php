<?php

declare(strict_types=1);

namespace Path\Linux;

use Path\Model\PathBlueprint;
use Path\Base;

final class Linux extends Base
{
  use PathBlueprint;
  
  protected const regsep = self::LINUXREGSEP;
  public const delimiter = ':';
  public const sep = '/';
}
?>