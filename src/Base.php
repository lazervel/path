<?php

declare(strict_types=1);

namespace Path;

abstract class Base
{
  protected const REGSEP = self::SEP === '/' ? self::LINUXREGSEP : self::WIN32REGSEP;
  protected const SEP = \DIRECTORY_SEPARATOR;
  protected const WIN32REGSEP = '\\\\';
  protected const LINUXREGSEP = '\/';
  protected const DELIMITER = self::SEP === '/' ? ':' : ';';
}
?>