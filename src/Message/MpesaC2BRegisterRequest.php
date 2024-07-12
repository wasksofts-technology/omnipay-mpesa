<?php

namespace Omnipay\Mpesa\Message;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate(
            'status',
            'confirmation_url',
            'validation_url',
            'pt_number',
        );

        $data['ShortCode'] = $this->getPTNumber();
        $data['ResponseType'] = $this->getStatus();
        $data['ConfirmationURL'] =  $this->getConfirmationURL();
        $data['ValidationURL'] = $this->getValidationURL();
    }

    public function getPTNumber()
    {
        return $this->getParameter('pt_number');
    }

    public function setPTNumber($value)
    {
        return $this->setParameter('pt_number', $value);
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
}
