<?php

namespace App\Interfaces;

interface Measurement
{
    public function getName(): string;

    public function getMultiplier(): float;

    public function getUnit(): string;

    public function getIcon(): string;

    public function setValue(float $value): static;
}
