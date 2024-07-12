<?php

namespace Omnipay\Mpesa\Message;

use Omnipay\Common\Exception\InvalidResponseException;

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
        return $data;
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

    public function sendData($data)
    {
        $body = $this->toJSON($data);
        try {
            $httpResponse = $this->httpClient->request(
                'POST',
                $this->getEndpoint() .  'mpesa/stkpushquery/v1/query',
                [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getToken(),
                    'Content-Type' => 'application/json',
                ],
                $body
            );
            $body = (string) $httpResponse->getBody()->getContents();
            $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
            return $this->response = $this->createResponse($jsonToArrayResponse, $httpResponse->getStatusCode());
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
