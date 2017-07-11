<?php

namespace Hitbtc;


use Psr\Http\Message\RequestInterface;

class AuthMiddleware
{
    protected $publicKey;
    protected $secretKey;

    public function __construct($publicKey, $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @param callable $handler
     *
     * @return callable
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use (&$handler) {
            $queryString = $request->getUri()->getQuery();
            $queryParts = \GuzzleHttp\Psr7\parse_query($queryString);

            $queryParts['apikey'] = $this->publicKey;
            $queryParts['nonce'] = $this->getNonce();

            $queryString = \GuzzleHttp\Psr7\build_query($queryParts);
            $request = $request->withUri($request->getUri()->withQuery($queryString));

            $message = $request->getUri()->getPath(). '?' . $queryString . $request->getBody();
            $sign = strtolower(hash_hmac('sha512', $message, $this->secretKey));

            $request = $request
                ->withAddedHeader('Api-Signature', $sign)
                ->withAddedHeader('User-Agent', 'Hitbtc PHP Client')
                ;

            return $handler($request, $options);
        };
    }

    protected function getNonce()
    {
        return intval(microtime(true) * 1000);
    }
}
