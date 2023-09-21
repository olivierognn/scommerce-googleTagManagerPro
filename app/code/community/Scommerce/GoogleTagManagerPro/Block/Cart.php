<?php
/**
 *
 * @category    Scommerce
 * @package     Scommerce_GoogleTagManagerPro
 * @author		Scommerce Mage (core@scommerce-mage.co.uk)
 */
class Scommerce_GoogleTagManagerPro_Block_Cart extends Mage_Core_Block_Template
{
    public function getCartItems()
    {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $cartItems = $quote->getAllVisibleItems();
        $helper = Mage::helper('scommerce_googletagmanagerpro');
        $items = [];

        foreach ($cartItems as $cartItem) {
            $id = $cartItem->getId();
            $brand = $helper->getBrand($cartItem);
            $name = $cartItem->getProduct()->getName();
            $category = $helper->getProductCategoryName($cartItem);
            $price = $cartItem->getPrice();
            $list = $cartItem->getTrackingList();
            $items[] = [
                'name' => $this->jsQuoteEscape(trim($name)),
                'id' => $this->jsQuoteEscape($id),
                'price' => $price,
                'brand' => $this->jsQuoteEscape($brand),
                'category' => $this->jsQuoteEscape($category),
                'quantity' => $cartItem->getQty(),
                'list' => $list
            ];
        }
        return $items;
    }

    public function getCartTotal()
    {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $cartItems = $quote->getAllVisibleItems();
        $total = 0;

        foreach ($cartItems as $cartItem) {
            $total += $cartItem->getPrice()*$cartItem->getQty();
        }

        return $total;
    }

}