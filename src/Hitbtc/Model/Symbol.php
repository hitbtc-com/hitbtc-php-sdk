<?php
namespace Hitbtc\Model;

class Symbol
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    public function __toString()
    {
        return $this->code;
    }

}
