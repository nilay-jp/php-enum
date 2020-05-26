<?php

namespace jp\nilay\enum\tests\model;

use jp\nilay\enum\Enum;

class Height extends Enum
{
    protected string $name;
    protected string $abbr;

    public function __construct(string $name, string $abbr)
    {
        parent::__construct();
        $this->name = $name;
        $this->abbr = $abbr;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAbbr(): string
    {
        return $this->abbr;
    }

    /**
     * @Enum
     */
    public static function HIGH(): Height
    {
        return new static("High", "H");
    }

    /**
     * @Enum
     */
    public static function MEDIUM(): Height
    {
        return new static("Medium", "M");
    }

    /**
     * @Enum
     */
    public static function LOW(): Height
    {
        return new static("Low", "L");
    }

    public static function BAD_METHOD(): Height
    {
        return new static("Bad", "B");
    }
}
