<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eupago\Payshop\Gateway\Response;
use Eupago\Payshop\Gateway\Http\Client\Client;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;

class TxnIdHandler implements HandlerInterface
{
    /**
     * Handles transaction id
     *
     * @param array $handlingSubject
     * @param array $response
     * @return void
     */

    public function handle(array $handlingSubject, array $response)
    {
        $response = $response[0];

        if (!isset($handlingSubject['payment'])
            || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }
        /** @var PaymentDataObjectInterface $paymentDO */
        $paymentDO = $handlingSubject['payment'];
        $payment = $paymentDO->getPayment();
        $order = $paymentDO->getOrder();
        if (isset($response->referencia)) {
            $payment->setAdditionalInformation('entidade', $response->entidade);
            $payment->setAdditionalInformation('referencia', $response->referencia);
            //$payment->setAdditionalInformation('data_limite', $response->data_fim);
            // $payment->setTransactionAdditionalInfo(\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS, $this->getDetails($response));
            $payment->setTransactionId($payment->getAdditionalInformation('referencia'));
        }
        $payment->setIsTransactionClosed(false);
        if (isset($response->estado_referencia) &&
            in_array($response->estado_referencia, Client::SUCCESS) &&
            $response->valor == $order->getGrandTotalAmount()) {
            $payment->setIsTransactionClosed(true);
        }
    }
}
