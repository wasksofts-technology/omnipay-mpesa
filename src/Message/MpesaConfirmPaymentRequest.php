<?php

namespace Omnipay\Mpesa\Message;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class MpesaConfirmPaymentRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate(
            'checkout_request_id',
        );

        $timestamp = date('YmdHis');
        $data['BusinessShortCode'] = $this->getPTNumber();
        $data['Password'] = $this->generatePassword($timestamp);
        $data['Timestamp'] =  $timestamp;
        $data['CheckoutRequestID'] = $this->getCheckoutRequestID();
    }

    public function getCheckoutRequestID()
    {
        return $this->getParameter('checkout_request_id');
    }

    public function setCheckoutRequestID($value)
    {
        return $this->setParameter('checkout_request_id', $value);
    }

    public function generatePassword($timesatamp)
    {
        return  base64_encode($this->getStorenumber() . $this->getPassKey() . $timesatamp);
    }
}
