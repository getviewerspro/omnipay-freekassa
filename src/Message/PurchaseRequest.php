<?php
/**
 * FreeKassa driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-freekassa
 * @package   omnipay-freekassa
 * @license   MIT
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\FreeKassa\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getData()
    {
        $this->validate(
            'purse', 'secretKey',
            'amount', 'currency', 'transactionId'
        );

        return array_filter([
            'm' => $this->getPurse(),
            'oa' => $this->getAmount(),
            'o' => $this->getTransactionId(),
            'i' => strtolower($this->getCurrency()),
            's' => $this->calculateSignature(),
            'lang' => $this->getLanguage(),
            'em' => $this->getEmail()
        ]);
    }

    public function calculateSignature()
    {
        return md5(implode(':', [
            $this->getPurse(),
            $this->getAmount(),
            $this->getSecretKey(),
            $this->getTransactionId()
        ]));
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
