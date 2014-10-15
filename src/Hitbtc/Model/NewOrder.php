<?php
namespace Hitbtc\Model;

class NewOrder implements OrderInterface
{

    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var string
     */
    protected $clientOrderId;

    /**
     * @var string
     */
    protected $side;

    /**
     * @var string
     */
    protected $price;

    /**
     * @var string
     */
    protected $stopPrice;

    /**
     * @var string
     */
    protected $quantity;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $timeInForce;

    public function __construct()
    {
        $this->setClientOrderId(self::generateClientOrderId());
    }

    /**
     * @return string
     */
    public function getClientOrderId()
    {
        return $this->clientOrderId;
    }

    /**
     * @param  string $clientOrderId
     * @return $this
     */
    public function setClientOrderId($clientOrderId)
    {
        $this->clientOrderId = $clientOrderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param  string $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param  string $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * @param  string $side
     * @return $this
     */
    public function setSide($side)
    {
        $this->side = $side;

        return $this;
    }

    /**
     * @return string
     */
    public function getStopPrice()
    {
        return $this->stopPrice;
    }

    /**
     * @param  string $stopPrice
     * @return $this
     */
    public function setStopPrice($stopPrice)
    {
        $this->stopPrice = $stopPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param  string $symbol
     * @return $this
     */
    public function setSymbol($symbol)
    {
        $this->symbol = strval($symbol);

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeInForce()
    {
        return $this->timeInForce;
    }

    /**
     * @param  string $timeInForce
     * @return $this
     */
    public function setTimeInForce($timeInForce)
    {
        $this->timeInForce = $timeInForce;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public static function generateClientOrderId()
    {
        return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return array_filter(array(
            'symbol' => $this->getSymbol(),
            'clientOrderId' => $this->getClientOrderId(),
            'side' => $this->getSide(),
            'price' => $this->getPrice(),
            'stopPrice' => $this->getStopPrice(),
            'quantity' => $this->getQuantity(),
            'type' => $this->getType(),
            'timeInForce' => $this->getTimeInForce()
        ));
    }

}
