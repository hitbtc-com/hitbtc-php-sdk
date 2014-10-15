<?php
namespace Hitbtc\Model;

class BalanceTrading
{
    protected $currency;
    protected $available;
    protected $reserved;

    public function __construct($currency, $available, $reserved)
    {
        $this->currency = $currency;
        $this->available = $available;
        $this->reserved = $reserved;
    }

    /**
     * @return mixed
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return mixed
     */
    public function getReserved()
    {
        return $this->reserved;
    }

}
