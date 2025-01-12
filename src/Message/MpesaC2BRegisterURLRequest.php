<?php

namespace Omnipay\Mpesa\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class MpesaC2BRegisterURLRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate(
            'status',
            'confirmation_url',
            'validation_url',
            'version',
        );

        $data['ShortCode'] = $this->getPTNumber();
        $data['ResponseType'] = $this->getStatus();
        $data['ConfirmationURL'] =  $this->getConfirmationURL();
        $data['ValidationURL'] = $this->getValidationURL();
        return $data;
    }

    public function getStatus()
    {
        return $this->getParameter('status');
    }

    public function setStatus($value)
    {
        return $this->setParameter('status', $value);
    }

    public function getConfirmationURL()
    {
        return $this->getParameter('confirmation_url');
    }

    public function setConfirmationURL($value)
    {
        return $this->setParameter('confirmation_url', $value);
    }

    public function getValidationURL()
    {
        return $this->getParameter('validation_url');
    }

    public function setValidationURL($value)
    {
        return $this->setParameter('validation_url', $value);
    }

    public function getVersion()
    {
        return $this->getParameter('version');
    }

    public function setVersion($value)
    {
        return $this->setParameter('version', $value);
    }

    public function sendData($data)
    {
        $body = $this->toJSON($data);
        try {
            $httpResponse = $this->httpClient->request(
                'POST',
                $this->getEndpoint() .  'mpesa/c2b/' . $this->getVersion() . '/registerurl',
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
