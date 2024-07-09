<?php

/**
 * Mpesa Purchase Request
 */

namespace Omnipay\Mpesa\Message;

class MpesaPaymentRequest extends AuthorizeRequest
{
    public function getData()
    {
        $data = parent::getData();
        return $data;
    }
}
