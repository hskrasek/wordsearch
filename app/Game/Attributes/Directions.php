<?php

declare(strict_types=1);

namespace App\Game\Attributes;

use ArchTech\Enums\Meta\MetaProperty;
use Attribute;

#[Attribute(Attribute::TARGET_ALL)]
class Directions extends MetaProperty
{
}
