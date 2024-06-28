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


    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\CompletePurchaseRequest', $parameters);
    }
}
