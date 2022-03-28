<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExchangeRate
 *
 * @ORM\Table(name="exchange_rate")
 * @ORM\Entity
 */
class ExchangeRate
{
    /**
     * @ORM\Column(name="id_exchange_rate", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private string $idExchangeRate;

    /**
     * @ORM\Column(name="currency", type="string", length=3, nullable=false)
     */
    private string $currency;

    /**
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private \DateTimeInterface $date;

    /**
     * @ORM\Column(name="rate", type="float", precision=9, scale=8, nullable=true)
     */
    private ?float $rate;

    public function getIdExchangeRate(): string
    {
        return $this->idExchangeRate;
    }

    public function setIdExchangeRate(string $idExchangeRate): self
    {
        $this->idExchangeRate = $idExchangeRate;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(?string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
