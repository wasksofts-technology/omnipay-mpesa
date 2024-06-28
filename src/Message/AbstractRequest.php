<?php

namespace Omnipay\Mpesa\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

/**
 * Abstract Request
 *
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    const STKPUSH  = 'mpesa/stkpush/v1/processrequest';
    /**
     * Live Endpoint URL
     *
     * When you’re set to go live, use the live credentials assigned to
     * your app to generate a new access token to be used with the live URIs.
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://api.safaricom.co.ke/';

    /**
     * Sandbox Endpoint URL
     *
     * The mpesa are supported in two environments. Use the Sandbox environment
     * for testing purposes, then move to the live environment for production processing.
     * When testing, generate an access token with your test credentials to make calls to
     * the Sandbox URIs. When you’re set to go live, use the live credentials assigned to
     * your app to generate a new access token to be used with the live URIs.
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://sandbox.safaricom.co.ke/';

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

    protected function getBaseData()
    {
        $data = array();
        return $data;
    }

    protected function getItemData()
    {
        $data = array();
        $items = $this->getItems();

        return $data;
    }
    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'POST';
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function sendData($data)
    {
        // Might be useful to have some debug code here, PayPal especially can be
        // a bit fussy about data formats and ordering.  Perhaps hook to whatever
        // logging engine is being used.
        $body = $this->toJSON($data);
        try {
            $httpResponse = $this->httpClient->request(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getToken(),
                    'Content-Type' => 'application/json',
                ],
                $body
            );
            // Empty response body should be parsed also as and empty array
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

    /**
     * Returns object JSON representation required by PayPal.
     * The PayPal REST API requires the use of JSON_UNESCAPED_SLASHES.
     *
     * @param int $options http://php.net/manual/en/json.constants.php
     * @return string
     */
    public function toJSON($data, $options = 0)
    {
        // Because of PHP Version 5.3, we cannot use JSON_UNESCAPED_SLASHES option
        // Instead we would use the str_replace command for now.
        // TODO: Replace this code with return json_encode($this->toArray(), $options | 64); once we support PHP >= 5.4
        if (version_compare(phpversion(), '5.4.0', '>=') === true) {
            return json_encode($data, $options | 64);
        }
        return str_replace('\\/', '/', json_encode($data, $options));
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new Response($this, $data, $statusCode);
    }
}
