<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

class EnumCollection extends Collection
{
    protected ?string $enum;

    public function __construct($items = [], ?string $enum = null)
    {
        parent::__construct($items);

        $this->enum = $enum;
    }

    public function usingEnum(string $enum): self
    {
        $this->enum = $enum;

        return $this;
    }

    public function nullable(string $label): self
    {
        $this->prepend($label, '');

        return $this;
    }

    public function translate(): self
    {
        return $this->mapWithKeys(function ($value, $key) {
            if (Str::length($key) === 0) {
                return [null => $this->translateValue($value)];
            }

            return [$value => $this->translateValue($value)];
        });
    }

    protected function translateValue($value): string
    {
        if ($this->enum) {
            return $this->enum::translate($value);
        }

        return Enum::translate($value);
    }
}
