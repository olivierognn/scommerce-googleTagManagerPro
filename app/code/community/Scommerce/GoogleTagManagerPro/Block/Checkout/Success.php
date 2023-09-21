<?php
/**
 *
 * @category    Scommerce
 * @package     Scommerce_GoogleTagManagerPro
 * @author		Scommerce Mage (core@scommerce-mage.co.uk)
 */
class Scommerce_GoogleTagManagerPro_Block_Checkout_Success extends Mage_Core_Block_Template
{
    public function getOrder()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        return Mage::getModel('sales/order')->load($orderId);
    }

    /**
     * @return array
     */
    public function getConversionData()
    {
        $helper = Mage::helper('scommerce_googletagmanagerpro');
        $order = $this->getOrder();
        $email = $order->getCustomerEmail();
        $emailhash = hash('sha256', $email);
        $firstname = $order->getBillingAddress()->getFirstname();
        $firstnamehash =  hash('sha256', $firstname);
        $lastname = $order->getBillingAddress()->getLastname();
        $lastnamehash =  hash('sha256', $lastname);

        $resultArray = [
            'email' => $email,
            'emailhash' => $emailhash,
            'address' => [
                'first_name' => $firstname,
                'first_namehash' => $firstnamehash,
                'last_name' => $lastname,
                'last_namehash' => $lastnamehash,
                'street' => implode(', ', $order->getBillingAddress()->getStreet()),
                'city' => $order->getBillingAddress()->getCity(),
                'postal_code' => $order->getBillingAddress()->getPostcode(),
                'country' => $order->getBillingAddress()->getCountryId(),
            ]
        ];
        if ($region = $order->getBillingAddress()->getRegion()) {
            $resultArray['address']['region'] = $region;
        }
        if ($phone = $order->getBillingAddress()->getTelephone()) {
            $phonehash =  hash('sha256', $phone);
            $resultArray['phone_number'] = $phone;
            $resultArray['phone_numberhash'] = $phonehash;
        }
        $resultArray['revenue_with_vat_and_shipping'] = $order->getGrandTotal();
        $resultArray['revenue_without_vat_and_shipping'] = $order->getSubtotal();
        if ($helper->isTransactionIdEnabled()) {
            $resultArray['transaction_id'] = $order->getIncrementId();
        }
        return $resultArray;
    }
}
