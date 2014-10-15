<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 12.08.14
 * Time: 15:20
 */

namespace Hitbtc;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;

class AuthSubscriber implements SubscriberInterface
{
    protected $publicKey;
    protected $secretKey;

    public function __construct($publicKey, $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @inheritdoc
     */
    public function getEvents()
    {
        return [
            'before' => ['onBefore', RequestEvents::SIGN_REQUEST]
        ];
    }

    public function onBefore(BeforeEvent $event)
    {
        $event->getRequest()->getQuery()->add('apikey', $this->publicKey);
        $event->getRequest()->getQuery()->add('nonce', $this->getNonce());

        $message = $event->getRequest()->getPath() . '?' . $event->getRequest()->getQuery() . $event->getRequest()->getBody();
        $sign = strtolower(hash_hmac('sha512', $message, $this->secretKey));

        $event->getRequest()->addHeader('Api-Signature', $sign);
        $event->getRequest()->addHeader('User-Agent', 'Hitbtc PHP Client');
    }

    protected function getNonce()
    {
        return intval(microtime(true) * 1000);
    }

}
