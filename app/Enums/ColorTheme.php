<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Base()
 * @method static static Lavender()
 * @method static static Solarized()
 * @method static static MagicViolet()
 */
final class ColorTheme extends Enum
{
    const Base = 'base';
    const Lavender = 'lavender';
    const Solarized = 'solarized';
    const MagicViolet = 'magic-violet';
}