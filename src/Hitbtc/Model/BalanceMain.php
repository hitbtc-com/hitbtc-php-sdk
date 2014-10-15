<?php
namespace Hitbtc\Model;

class BalanceMain
{
    protected $currency;
    protected $amount;

    public function __construct($currency, $amount)
    {
        $this->currency = $currency;
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

}
