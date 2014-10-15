<?php
namespace Hitbtc\Model;

class Trade
{
    protected $tradeId;
    protected $execPrice;
    protected $timestamp;
    protected $originalOrderId;
    protected $fee;
    protected $clientOrderId;
    protected $symbol;
    protected $side;
    protected $execQuantity;

    public function __construct($params = array())
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getClientOrderId()
    {
        return $this->clientOrderId;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->execPrice;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->execQuantity;
    }

    /**
     * @return float
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @return string
     */
    public function getOriginalOrderId()
    {
        return $this->originalOrderId;
    }

    /**
     * @return string
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Get date of transaction
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return \DateTime::createFromFormat('U u', intval($this->timestamp / 1000) . ' ' . $this->timestamp % 1000);
    }

    /**
     * @return mixed
     */
    public function getTradeId()
    {
        return $this->tradeId;
    }
}
