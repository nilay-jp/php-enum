<?php

namespace jp\nilay\enum;

interface IEnum
{
    public function name(): string;

    /**
     * @param \jp\nilay\enum\IEnum $other
     */
    public function equals(IEnum $other): bool;

    public function ordinal(): int;

    /**
     * @return Array<IEnum>
     */
    public static function values(): array;

    /**
     * @return \jp\nilay\enum\IEnum
     *
     * @throws \InvalidArgumentException
     */
    public static function valueOf(string $name): IEnum;
}
