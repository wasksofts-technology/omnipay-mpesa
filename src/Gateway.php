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
            'storenumber' => '',
            'pt_number' => '',
            'consumer_key' => '',
            'consumer_secret' => '',
            'token' => '',
            'testMode' => false,
        ];
    }

    public function getConsumerKey()
    {
        return $this->getParameter('consumer_key');
    }

    public function setConsumerKey($value)
    {
        return $this->setParameter('consumer_key', $value);
    }

    public function getConsumerSecret()
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

    public function setStoreCode($value)
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

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\CompletePurchaseRequest', $parameters);
    }
}
