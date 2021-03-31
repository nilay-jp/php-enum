# php-enum

[![<nilay-jp>](https://circleci.com/gh/nilay-jp/php-enum.svg?style=shield)](<https://circleci.com/gh/nilay-jp/php-enum>)
[![<test>](https://github.com/nilay-jp/php-enum/workflows/test/badge.svg?branch=2.x)](<https://github.com/nilay-jp/php-enum/actions>)

Enum implementation for PHP.

## Example

Simple example.

```php
<?php

// "HIGH"
var_dump(Height::HIGH()->name()); 

// true
var_dump(Height::HIGH()->equals(Height::HIGH()));

// false
var_dump(Height::HIGH()->equals(Height::MEDIUM()));

// 0 | 1 | ... | n
var_dump(Height::HIGH()->ordinal());

// Height.MEDIUM
var_dump(Height::valueOf("MEDIUM"));

// Array<Height> [Height.HIGH, Height.MEDIUM, Height.LOW]
var_dump(Height::values());
```

```php

namespace com.example.app;

use jp\nilay\enum\Enum;

class Height extends Enum
{
    #[Enum]
    public static function HIGH(): Height { return new static(); }

    #[Enum]
    public static function MEDIUM(): Height { return new static(); }

    #[Enum]
    public static function LOW(): Height { return new static(); }
}
```

Extra attributes.

```php
<?php

// "High"
var_dump(Height::HIGH()->getName()); 

// "H"
var_dump(Height::HIGH()->getAbbr()); 
```

```php

namespace com.example.app;

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

    #[Enum]
    public static function HIGH(): Height { return new static("High", "H"); }

    #[Enum]
    public static function MEDIUM(): Height { return new static("Medium", "M"); }

    #[Enum]
    public static function LOW(): Height { return new static("Low", "L"); }
}
```
