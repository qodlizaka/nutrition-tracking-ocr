<?php

namespace App\Measurements;

use App\Interfaces\Measurement;
use App\Models\Food;
use Livewire\Wireable;

abstract class Base implements Measurement, Wireable
{
    public string $name;

    public string $unit;

    public string $icon;

    public float $value = 1;

    public float $baseMultiplier = 1;

    public function __construct(
        public Food $food,
        string $name = '',
        float $baseMultiplier = 0
    ) {
        if (!empty($name)) {
            $this->name = $name;
        }

        if (!empty($baseMultiplier)) {
            $this->baseMultiplier = $baseMultiplier;
        }
    }

    public function toLivewire(): array
    {
        return [
            'food_id' => $this->food->id,
            'name' => $this->name,
            'unit' => $this->unit,
            'icon' => $this->icon,
            'value' => $this->value,
            'base_multiplier' => $this->baseMultiplier,
        ];
    }

    public static function fromLivewire($payload): static
    {
        $food = Food::findOrFail($payload['food_id']);

        $instance = new static($food);

        $instance->value = $payload['value'];
        $instance->name = $payload['name'];
        $instance->unit = $payload['unit'];
        $instance->icon = $payload['icon'];
        $instance->baseMultiplier = $payload['base_multiplier'];

        return $instance;
    }

    public function getName(): string
    {
        return __($this->name);
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    abstract public function getMultiplier(): float;
}
