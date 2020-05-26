<?php

namespace jp\nilay\enum;

abstract class Enum implements IEnum
{
    /** @var Array<string, Array<string, int>> */
    private static array $methodNameMemo = [];

    /** @var Array<string, Array<string, Enum>> */
    private static array $enumMemo = [];
    private int $ordinal;

    protected function __construct()
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
     *
     * @return void
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
            $docComment = $reflectionMethod->getDocComment();

            if (!self::hasEnumAnnotation($docComment)) {
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
    }

    /**
     * @param string|bool $docComment
     *
     * @return bool
     */
    private static function hasEnumAnnotation($docComment): bool
    {
        if (!is_string($docComment)) {
            return false;
        }

        $lines = preg_split("/(\r?\n)/", $docComment);

        if (false === $lines) {
            return false;
        }

        foreach ($lines as $line) {
            if (!preg_match("/^(?=\s+?\*[^\/])(.+)/", $line, $matches)) {
                continue;
            }

            $info = $matches[1];
            $info = trim($info);
            $info = preg_replace("/^(\*\s+?)/", "", $info);

            if ("@Enum" === trim($info)) {
                return true;
            }
        }

        return false;
    }
}
