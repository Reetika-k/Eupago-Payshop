<?php
/**
 * Created by euPago.pt
 * For untitled
 * Developer: Rui Barbosa
 * Date: 14/02/2018
 */

namespace Eupago\Payshop\Block;


use Magento\Framework\Phrase;
use Magento\Framework\Registry;


class Info extends \Magento\Payment\Block\Info
{

    public function getSpecificInformation()
    {
        $informations['Entidade'] = $this->getInfo()->getAdditionalInformation('entidade');
        $informations['Referencia'] = $this->getInfo()->getAdditionalInformation('referencia');
        return (object)$informations;
    }

    public function getMethodCode()
    {
        return $this->getInfo()->getMethodInstance()->getCode();
    }

}
