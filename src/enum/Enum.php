<?php

namespace jp\nilay\enum;

use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
class Enum implements IEnum
{
    /** @var Array<string, Array<string, int>> */
    private static array $methodNameMemo = [];

    /** @var Array<string, Array<string, Enum>> */
    private static array $enumMemo = [];
    private int $ordinal;

    public function __construct()
    {
        $this->init();
    }

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        self::memorize();

        return (string) array_search($this->ordinal, self::$methodNameMemo[static::class]);
    }

    /**
     * {@inheritdoc}
     */
    public function equals(IEnum $other): bool
    {
        return is_subclass_of($other, Enum::class) && (static::class === get_class($other)) && $this->ordinal === $other->ordinal();
    }

    /**
     * {@inheritdoc}
     */
    public function ordinal(): int
    {
        return $this->ordinal;
    }

    private function init(): void
    {
        self::memorize(false);

        $caller = "";
        $debugTraces = debug_backtrace();

        foreach ($debugTraces as $key => $trace) {
            if (Enum::class === $trace["class"]) {
                $caller = $debugTraces[$key + 3]["function"];
                break;
            }
        }

        if (!isset(self::$methodNameMemo[static::class][$caller])) {
            throw new \BadMethodCallException("Unresolved reference: ".get_called_class().".".$caller);
        }

        $this->ordinal = self::$methodNameMemo[static::class][$caller];
    }

    /**
     * {@inheritdoc}
     */
    public static function values(): array
    {
        self::memorize();

        return array_values(self::$enumMemo[static::class]);
    }

    /**
     * {@inheritdoc}
     */
    public static function valueOf(string $name): Enum
    {
        self::memorize();

        if (!array_key_exists($name, self::$enumMemo[static::class])) {
            throw new \InvalidArgumentException(sprintf("No enum const class %s.%s", get_called_class(), $name));
        }

        return self::$enumMemo[static::class][$name];
    }

    /**
     * @param bool $memolizeEnumerators
     */
    private static function memorize($memolizeEnumerators = true): void
    {
        $isMethodNameMemorized = !empty(self::$methodNameMemo[static::class]);
        $isEnumMemorized = !empty(self::$enumMemo[static::class]);

        if ($memolizeEnumerators && $isMethodNameMemorized && $isEnumMemorized) {
            return;
        }

        $class = get_called_class();
        $reflectionClass = new \ReflectionClass($class);
        $reflectionMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_STATIC);
        $ordinal = 0;

        foreach ($reflectionMethods as $reflectionMethod) {
            if (!self::hasEnumAttribute($reflectionMethod)) {
                continue;
            }

            $methodName = $reflectionMethod->getName();

            if (!$isMethodNameMemorized) {
                self::$methodNameMemo[static::class][$methodName] = $ordinal;
                ++$ordinal;
            }

            if ($memolizeEnumerators) {
                self::$enumMemo[static::class][$methodName] = static::$methodName();
            }
        }
        exit;
    }

    private static function hasEnumAttribute(\ReflectionMethod $method): bool
    {
        $attributes = $method->getAttributes();

        foreach ($attributes as $attribute) {
            if (self::class === $attribute->getName()) {
                return true;
            }
        }

        return false;
    }
}
