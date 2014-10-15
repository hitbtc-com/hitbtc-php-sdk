<?php
namespace Hitbtc\Model;

class Order implements OrderInterface
{
    const STATUS_NEW = 'new';
    const STATUS_PARTIALLY_FILLED = 'partiallyFilled';
    const STATUS_FILLED = 'filled';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    protected $orderId;
    protected $clientOrderId;
    protected $orderStatus;
    protected $price;
    protected $quantity;
    protected $type;
    protected $timeInForce;
    protected $symbol;
    protected $side;
    protected $cumQuantity;

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
     * @return integer
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
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
     * @return string
     */
    public function getTimeInForce()
    {
        return $this->timeInForce;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->orderStatus;
    }

}
