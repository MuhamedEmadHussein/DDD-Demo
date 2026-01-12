<?php

namespace App\Domains\Order\ValueObjects;

use InvalidArgumentException;

readonly class Price
{
    public function __construct(
        public float $amount,
        public string $currency = 'USD'
    ) {
        if ($amount < 0) {
            throw new InvalidArgumentException("Amount cannot be negative");
        }
    }

    public function add(Price $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException("Currencies must match");
        }

        return new self($this->amount + $other->amount, $this->currency);
    }

    public function format(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}
