<?php

namespace jp\nilay\enum\tests\model;

use jp\nilay\enum\Enum;

class HeightEx extends Height
{
    #[Enum]
    public static function EXTRA_HIGH(): HeightEx
    {
        return new static('High', 'EXH');
    }

    #[Enum]
    public static function EXTRA_LOW(): HeightEx
    {
        return new static('Low', 'EXL');
    }
}
