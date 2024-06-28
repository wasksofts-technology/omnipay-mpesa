<?php

/**
 * Mpesa Token Request
 */

namespace Omnipay\Mpesa\Message;

class MpesaTokenRequest extends AbstractRequest
{
    public function getData()
    {
        return null;
    }

    public function sendData($body = null)
    {
        $httpResponse = $this->httpClient->request(
            'GET',
            parent::getEndpoint() . 'oauth/v1/generate?grant_type=client_credentials',
            [
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode("{$this->getConsumerKey()}:{$this->getConsumerSecret()}"),
            ],
            $body
        );
        // Empty response body should be parsed also as and empty array
        $body = (string) $httpResponse->getBody()->getContents();
        $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
        return $this->response = new MpesaResponse($this, $jsonToArrayResponse, $httpResponse->getStatusCode());
    }
}
