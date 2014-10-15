<?php
namespace Hitbtc\Model;

interface OrderInterface
{
    const SIDE_SELL = 'sell';
    const SIDE_BUY = 'buy';

    const TYPE_LIMIT = 'limit';
    const TYPE_MARKET = 'market';
    const TYPE_STOP_LIMIT = 'stopLimit';
    const TYPE_STOP_MARKET = 'stopMarket';

    /**
     * Good-Til-Canceled - Default value
     */
    const TIME_IN_FORCE_GTC = 'GTC';

    /**
     * Immediate-Or-Cancel
     */
    const TIME_IN_FORCE_IOC = 'IOC';

    /**
     * Fill-Or-Kill
     */
    const TIME_IN_FORCE_FOK = 'FOK';

    /**
     * Day
     */
    const TIME_IN_FORCE_DAY = 'DAY';
}
