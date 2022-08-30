<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static ACTIVE()
 */
final class GameweekStatus extends Enum
{
    const PENDING = 'pending';
    const ACTIVE = 'active';
}
