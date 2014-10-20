<?php

namespace Hitbtc;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Message\Response;
use Hitbtc\Exception\InvalidRequestException;
use Hitbtc\Exception\RejectException;
use Hitbtc\Model\BalanceMain;
use Hitbtc\Model\BalanceTrading;
use Hitbtc\Model\NewOrder;
use Hitbtc\Model\Order;
use Hitbtc\Model\Trade;

class ProtectedClient
{
    protected $host;

    protected $publicKey;
    protected $secretKey;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    public function __construct($publicKey, $secretKey, $demo = false)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
        if ($demo) {
            if (is_string($demo) && strlen($demo) > 4) {
                $this->host = $demo;
            } else {
                $this->host = 'https://demo-api.hitbtc.com';
            }
        } else {
            $this->host = 'https://api.hitbtc.com';
        }
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new HttpClient([
                'base_url' => $this->host,
            ]);
            $this->httpClient->getEmitter()->attach(new AuthSubscriber($this->publicKey, $this->secretKey));

        }

        return $this->httpClient;
    }

    /**
     * Create new order
     *
     * @param  NewOrder                $order
     * @throws InvalidRequestException
     * @throws RejectException
     * @return Order
     */
    public function newOrder(NewOrder $order)
    {
        $response = $this->getHttpClient()->post('/api/1/trading/new_order', array(
            'body' => $order->asArray(),
            'exceptions' => false,
        ));
        $document = $response->json();

        if (isset($document['ExecutionReport'])) {
            if ($document['ExecutionReport']['execReportType'] == 'rejected') {
                throw new RejectException($document['ExecutionReport']['orderRejectReason'], $document['ExecutionReport']);
            } else {
                return new Order($document['ExecutionReport']);
            }
        }
        throw new InvalidRequestException($response->getBody());
    }

    /**
     * Cancel new or partiallyFilled order
     *
     * @param  Order                   $order
     * @param  null|string                    $cancelRequestId
     * @throws InvalidRequestException
     * @throws RejectException
     * @return Order
     */
    public function cancelOrder(Order $order, $cancelRequestId = null)
    {
        if (!$cancelRequestId) {
            $cancelRequestId = substr(NewOrder::generateClientOrderId(), 0, 30);
        }

        $response = $this->getHttpClient()->post('/api/1/trading/cancel_order', array(
            'body' => array(
                'clientOrderId' => $order->getClientOrderId(),
                'cancelRequestClientOrderId' => $cancelRequestId,
                'symbol' => $order->getSymbol(),
                'side' => $order->getSide()
            ),
            'exceptions' => false,
        ));
        $document = $response->json();
        if (isset($document['ExecutionReport'])) {
            return new Order($document['ExecutionReport']);
        } elseif (isset($document['CancelReject'])) {
            throw new RejectException($document['CancelReject']['rejectReasonCode'], $document['CancelReject']);
        }

        throw new InvalidRequestException($response->getBody());
    }

    /**
     * @param  null                    $symbols
     * @throws InvalidRequestException
     * @return Order[]
     */
    public function getActiveOrders($symbols = null)
    {
        $params = array('exceptions' => false);
        if ($symbols) {
            $params['query']['symbols'] = implode(',', (array) $symbols);
        }
        $response = $this->getHttpClient()->get('/api/1/trading/orders/active', $params);
        $document = $response->json();
        if (isset($document['orders'])) {
            $orders = [];
            foreach ($document['orders'] as $orderData) {
                $orders[] = new Order($orderData + array('price' => $orderData['orderPrice'], 'quantity' => $orderData['orderQuantity']));
            }

            return $orders;
        }
        throw new InvalidRequestException($response->getBody());
    }

    /**
     * @param  string|array            $symbols
     * @param  string                  $sort
     * @param  string|array            $statuses
     * @param  int                     $offset
     * @param  int                     $limit
     * @return Order[]
     * @throws InvalidRequestException
     */
    public function getRecentOrders($symbols = null, $sort = 'asc', $statuses = null, $offset = 0, $limit = 1000)
    {
        $query = array(
            'start_index' => $offset,
            'max_results' => $limit,
            'sort' => $sort
        );

        if ($symbols) {
            $query['symbols'] = implode(',', (array) $symbols);
        }
        if ($statuses) {
            $query['statuses'] = implode(',', (array) $statuses);
        }

        $response = $this->getHttpClient()->get('/api/1/trading/orders/recent', array('query' => $query, 'exceptions' => false));
        $document = $response->json();
        if (isset($document['orders'])) {
            $orders = [];
            foreach ($document['orders'] as $orderData) {
                $orders[] = new Order($orderData + array('price' => $orderData['orderPrice'], 'quantity' => $orderData['orderQuantity']));
            }

            return $orders;
        }
        throw new InvalidRequestException($response->getBody());
    }

    /**
     * @param  null                    $symbols
     * @param  string                  $by
     * @param  string                  $sort
     * @param  null                    $from
     * @param  null                    $till
     * @param  int                     $offset
     * @param  int                     $limit
     * @return Trade[]
     * @throws InvalidRequestException
     */
    public function getTrades($symbols = null, $by = 'trade_id', $sort = 'ask', $from = null, $till = null, $offset = 0, $limit = 1000)
    {
        $query = array(
            'start_index' => $offset,
            'max_results' => $limit,
            'sort' => $sort,
            'by' => $by
        );

        if ($symbols) {
            $query['symbols'] = implode(',', (array) $symbols);
        }

        if ($from) {
            $query['from'] = $from;
        }
        if ($till) {
            $query['till'] = $till;
        }

        $response = $this->getHttpClient()->get('/api/1/trading/trades', array('query' => $query, 'exceptions' => false));
        $document = $response->json();
        if (isset($document['trades'])) {
            $trades = [];
            foreach ($document['trades'] as $tradeData) {
                $trades[] = new Trade($tradeData);
            }

            return $trades;
        }
        throw new InvalidRequestException($response->getBody());
    }

    /**
     * @return BalanceTrading[]
     * @throws InvalidRequestException
     */
    public function getBalanceTrading()
    {
        $response = $this->getHttpClient()->get('/api/1/trading/balance', array('exceptions' => false));
        $document = $response->json();
        if (isset($document['balance'])) {
            $balances = [];
            foreach ($document['balance'] as $balanceData) {
                $balances[] = new BalanceTrading($balanceData['currency_code'], $balanceData['cash'], $balanceData['reserved']);
            }

            return $balances;
        }
        throw new InvalidRequestException($response->getBody());
    }

    /**
     * @return BalanceMain[]
     * @throws InvalidRequestException
     */
    public function getBalanceMain()
    {
        $response = $this->getHttpClient()->get('/api/1/payment/balance', array('exceptions' => false));
        $document = $response->json();
        if (isset($document['balance'])) {
            $balances = [];
            foreach ($document['balance'] as $balanceData) {
                $balances[] = new BalanceMain($balanceData['currency_code'], $balanceData['balance']);
            }

            return $balances;
        }
        throw new InvalidRequestException($response->getBody());
    }

    /**
     * @param $currency
     * @param  bool                    $new if need to create new address
     * @return string
     * @throws InvalidRequestException
     */
    public function getPaymentAddress($currency, $new = false)
    {
        if ($new) {
            $response = $this->getHttpClient()->post('/api/1/payment/address/' . $currency, array('exceptions' => false));
        } else {
            $response = $this->getHttpClient()->get('/api/1/payment/address/' . $currency, array('exceptions' => false));
        }
        $document = $response->json();
        if (isset($document['address'])) {
            return $document['address'];
        }
        throw new InvalidRequestException($response->getBody());
    }

    /**
     * @param $currency
     * @param $amount
     * @param $trading
     * @throws InvalidRequestException
     * @throws RejectException
     * @return string
     */
    protected function _transferAmount($currency, $amount, $trading)
    {
        $url = $trading ? '/api/1/payment/transfer_to_trading' : '/api/1/payment/transfer_to_main';
        $response = $this->getHttpClient()->post($url, array(
            'body' => array(
                'currency_code' => $currency,
                'amount' => $amount
            ),
            'exceptions' => false
        ));
        $document = $response->json();
        if (isset($document['transaction'])) {
            return $document['transaction'];
        } elseif (isset($document['message'])) {
            throw new RejectException($document['message'], $document);
        }
        throw new InvalidRequestException($response->getBody());
    }

    /**
     * Transfers funds from main accounts to trading; returns a transaction ID
     *
     * @param $currency
     * @param $amount
     * @return string                  transaction ID
     * @throws InvalidRequestException
     * @throws RejectException
     */
    public function transferToTrading($currency, $amount)
    {
        return $this->_transferAmount($currency, $amount, true);
    }

    /**
     * Transfers funds from trading accounts to main; returns a transaction ID
     *
     * @param $currency
     * @param $amount
     * @return string                  transaction ID
     * @throws InvalidRequestException
     * @throws RejectException
     */
    public function transferToMain($currency, $amount)
    {
        return $this->_transferAmount($currency, $amount, false);
    }

    /**
     * @param $currency
     * @param $amount
     * @param $address
     * @param null $paymentId
     * @throws InvalidRequestException
     * @throws RejectException
     */
    public function payout($currency, $amount, $address, $paymentId = null)
    {
        $response = $this->getHttpClient()->post('/api/1/payment/payout', array(
            'body' => array(
                'currency_code' => $currency,
                'amount' => $amount,
                'address' => $address,
                'extra_id' => $paymentId
            ),
            'exceptions' => false
        ));
        $document = $response->json();
        if (isset($document['transaction'])) {
            return $document['transaction'];
        } elseif (isset($document['message'])) {
            throw new RejectException($document['message'], $document);
        }
        throw new InvalidRequestException($response->getBody());
    }

    public function getTransactions($sort = 'asc', $offset = 0, $limit = 1000)
    {
        $query = array(
            'offset' => $offset,
            'limit' => $limit,
            'dir' => $sort
        );

        $response = $this->getHttpClient()->get('/api/1/payment/transactions', array('query' => $query, 'exceptions' => false));
        $document = $response->json();
        if (isset($document['transactions'])) {
            $transactions = [];
            foreach ($document['transactions'] as $txn) {
                $transactions[] = $txn;
            }

            return $transactions;
        }
        throw new InvalidRequestException($response->getBody());
    }

}
