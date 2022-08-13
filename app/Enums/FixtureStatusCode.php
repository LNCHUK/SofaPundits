<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static TBD()
 * @method static static NS()
 * @method static static HT()
 * @method static static ET()
 * @method static static P()
 * @method static static FT()
 * @method static static AET()
 * @method static static PEN()
 * @method static static BT()
 * @method static static SUSP()
 * @method static static PST()
 * @method static static CANC()
 * @method static static ABD()
 * @method static static AWD()
 * @method static static WO()
 * @method static static LIVE()
 */
final class FixtureStatusCode extends Enum
{
    const TO_BE_DEFINED = 'TBD';
    const NOT_STARTED = 'NS';
    const FIRST_HALF = '1H';
    const HALF_TIME = 'HT';
    const SECOND_HALF = '2H';
    const EXTRA_TIME = 'ET';
    const PENALTIES_IN_PROGRESS = 'P';
    const MATCH_FINISHED = 'FT';
    const FINISHED_AFTER_EXTRA_TIME = 'AET';
    const FINISHED_AFTER_PENALTIES = 'PEN';
    const BREAK_TIME_IN_EXTRA_TIME = 'BT';
    const MATCH_SUSPENDED = 'SUSP';
    const MATCH_POSTPONED = 'PST';
    const MATCH_CANCELLED = 'CANC';
    const MATCH_ABANDONED = 'ABD';
    const TECHNICAL_LOSS = 'AWD';
    const WALKOVER = 'WO';
    const IN_PROGRESS = 'LIVE';
}
