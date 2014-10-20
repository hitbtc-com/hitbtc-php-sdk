# Hitbtc SDK for PHP
The HitBTC SDK for PHP enables PHP developers to use HitBTC rest trading API in their PHP code, and build robust applications and software.

## Features
* Get trading and main balances
* Place new order
* Cancel order
* Return list of orders and trades
* Transfers funds between main and trading accounts
* Returns the last created or create new one incoming cryptocurrency address that can be used to deposit cryptocurrency to your account.
* Withdraws money and creates an outgoing crypotocurrency transaction
* Returns a list of payment transactions

## Installing via Composer

The recommended way to install hitbtc-php-sdk is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, update your project's composer.json file to include hitbtc-php-sdk:

```javascript
{
    "require": {
        "hitbtc-com/hitbtc-php-sdk": "~1.0"
    }
}
```

## Getting Started

Go to https://hitbtc.com/settings and create your api keys

## Quick Examples

**New order:**

```php
$client = new \Hitbtc\ProtectedClient('API key', 'API secret', $demo = false);

$newOrder = new \Hitbtc\Model\NewOrder();
$newOrder->setSide($newOrder::SIDE_SELL);
$newOrder->setSymbol('BTCUSD');
$newOrder->setTimeInForce($newOrder::TIME_IN_FORCE_GTC);
$newOrder->setType($newOrder::TYPE_LIMIT);
$newOrder->setQuantity(10);
$newOrder->setPrice(800);

try {
    $order = $client->newOrder($newOrder);
    var_dump($order->getOrderId());
    var_dump($order->getStatus()); // new
} catch (\Hitbtc\Exception\RejectException $e) {
    echo $e; // if creating order will rejected
} catch (\Hitbtc\Exception\InvalidRequestException $e) {
    echo $e->getMessage(); // error in request
} catch (\Exception $e) {
    echo $e->getMessage(); // other error like network issue
}
```

**Cancel order:**

```php
try {
    $order = $client->cancelOrder($order);
    var_dump($order->getStatus()); // canceled
} catch (\Hitbtc\Exception\RejectException $e) {
    echo $e; // if creating order will rejected
} catch (\Hitbtc\Exception\InvalidRequestException $e) {
    echo $e->getMessage(); // error in request
} catch (\Exception $e) {
    echo $e->getMessage(); // other error like network issue
}
```

**Get trading balance:**

```php
try {
    foreach ($client->getBalanceTrading() as $balance) {
        echo $balance->getCurrency() . ' ' . $balance->getAvailable() . ' reserved:' . $balance->getReserved() . "\n";
    }
} catch (\Hitbtc\Exception\InvalidRequestException $e) {
    echo $e;
} catch (\Exception $e) {
    echo $e;
}
//BTC 18.314848971 reserved:0.7004
//DOGE 1122543 reserved:0
```

**Get incoming cryptocurrency address that can be used to deposit cryptocurrency to your account:**

```php
try {
    $address = $client->getPaymentAddress('BTC');
} catch (\Hitbtc\Exception\InvalidRequestException $e) {
    echo $e;
} catch (\Exception $e) {
    echo $e;
}
```

**Transfers funds between main and trading accounts:**

```php
try {
    $tnxId = $client->transferToMain('BTC', 1.5);
} catch (\Hitbtc\Exception\InvalidRequestException $e) {
    echo $e;
} catch (\Exception $e) {
    echo $e;
}
```

## Documentation

See the [https://hitbtc.com/api](https://hitbtc.com/api) for more detail.

## License

hitbtc-php-sdk is licensed under the MIT License


