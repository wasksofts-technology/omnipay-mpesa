<?php

namespace Omnipay\Mpesa;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Mpesa';
    }

    public function getDefaultParameters()
    {
        return [
            'consumer_key' => '',
            'consumer_secret' => '',
            'token' => '',
            'storenumber' => '',
            'pt_number' => '',
            'passkey' => '',
            'testMode' => false,
        ];
    }

    public function getConsumerKey(): string
    {
        return $this->getParameter('consumer_key');
    }

    public function setConsumerKey($value)
    {
        return $this->setParameter('consumer_key', $value);
    }

    public function getConsumerSecret(): string
    {
        return $this->getParameter('consumer_secret');
    }

    public function setConsumerSecret($value)
    {
        return $this->setParameter('consumer_secret', $value);
    }

    public function getStoreNumber()
    {
        return $this->getParameter('storenumber');
    }

    public function setStoreNumber($value)
    {
        return $this->setParameter('storenumber', $value);
    }

    public function getPTNumber()
    {
        return $this->getParameter('pt_number');
    }

    public function setPTNumber($value)
    {
        return $this->setParameter('pt_number', $value);
    }

    public function getPassKey()
    {
        return $this->getParameter('passkey');
    }

    public function setPassKey($value)
    {
        return $this->setParameter('passkey', $value);
    }

    /**
     * Get OAuth 2.0 access token.
     *
     * @param bool $createIfNeeded [optional] - If there is not an active token present, should we create one?
     * @return string
     */
    public function getToken($createIfNeeded = true)
    {
        if ($createIfNeeded && !$this->hasToken()) {
            $response = $this->createToken()->send();
            if ($response->isSuccessful()) {
                $data = $response->getData();
                if (isset($data['access_token'])) {
                    $this->setToken($data['access_token']);
                    $this->setTokenExpires(time() + $data['expires_in']);
                }
            }
        }
        return $this->getParameter('token');
    }

    /**
     * Create OAuth 2.0 access token request.
     *
     * @return \Omnipay\Mpesa\Message\MpesaTokenRequest
     */
    public function createToken()
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\MpesaTokenRequest', array());
    }

    /**
     * Set OAuth 2.0 access token.
     *
     * @param string $value
     * @return MpesaGateway provides a fluent interface
     */
    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    /**
     * Get OAuth 2.0 access token expiry time.
     *
     * @return integer
     */
    public function getTokenExpires()
    {
        return $this->getParameter('tokenExpires');
    }

    /**
     * Set OAuth 2.0 access token expiry time.
     *
     * @param integer $value
     * @return MpesaGateway provides a fluent interface
     */
    public function setTokenExpires($value)
    {
        return $this->setParameter('tokenExpires', $value);
    }

    /**
     * Is there a bearer token and is it still valid?
     *
     * @return bool
     */
    public function hasToken()
    {
        $token = $this->getParameter('token');
        $expires = $this->getTokenExpires();
        if (!empty($expires) && !is_numeric($expires)) {
            $expires = strtotime($expires);
        }
        return !empty($token) && time() < $expires;
    }

    public function createRequest($class, array $parameters = array())
    {
        if (!$this->hasToken() && $class != '\Omnipay\Mpesa\Message\MpesaTokenRequest') {
            // This will set the internal token parameter which the parent
            // createRequest will find when it calls getParameters().
            $this->getToken(true);
        }

        return parent::createRequest($class, $parameters);
    }


    public function payment(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\MpesaPaymentRequest', $parameters);
    }

    public function confirmPyament(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\CompletePurchaseRequest', $parameters);
    }
}
