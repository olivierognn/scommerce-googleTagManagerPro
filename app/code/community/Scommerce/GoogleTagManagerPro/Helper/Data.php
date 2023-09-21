<?php
/**
 *
 * This class is required for admin to define configuration settings.
 * @category    Scommerce
 * @package     Scommerce_GoogleTagManagerPro
 * @author		Scommerce Mage (core@scommerce-mage.co.uk)
 */
class Scommerce_GoogleTagManagerPro_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * Admin configuration paths
     *
     */
    const XML_PATH_ENABLED 					= 'scommerce_googletagmanagerpro/options/enabled';
    const XML_PATH_LICENSE_KEY				= 'scommerce_googletagmanagerpro/options/license_key';
    const XML_PATH_ACCOUNT_ID				= 'scommerce_googletagmanagerpro/options/account_id';
    const XML_PATH_EE_ENABLED				= 'scommerce_googletagmanagerpro/options/enhanced_ecommerce';
    const XML_PATH_ENHANCED_CONVERSION_ENABLED = 'scommerce_googletagmanagerpro/options/enhanced_conversion';
    const XML_PATH_TRANSACTION_ID_ENABLED = 'scommerce_googletagmanagerpro/options/include_transaction_id';
    const XML_PATH_ENHANCED_CONVERSION_FORMAT  = 'scommerce_googletagmanagerpro/options/pii_data_format';
    const XML_PATH_ENHANCED_BRAND_DROPDOWN  = 'scommerce_googletagmanagerpro/options/brand_dropdown';
    const XML_PATH_ENHANCED_BRAND_TEXT      = 'scommerce_googletagmanagerpro/options/brand_text';
    const XML_PATH_BASE 					= 'scommerce_googletagmanagerpro/options/base';
    const XML_PATH_REVENUE 					= 'scommerce_googletagmanagerpro/options/revenue';
    const XML_PATH_ENABLE_DYNAMIC 			= 'scommerce_googletagmanagerpro/options/enable_dynamic';
    const XML_PATH_ENABLE_OPTIMIZE 			= 'scommerce_googletagmanagerpro/options/enable_optimize';
    const XML_PATH_OPTIMIZE_CONTAINER_ID 	= 'scommerce_googletagmanagerpro/options/optimize_container_id';
    const XML_PATH_ATTRIBUTE_KEY			= 'scommerce_googletagmanagerpro/options/attribute_key';
    const XML_PATH_SPOT         			= 'scommerce_googletagmanagerpro/options/send_phone_order_transaction';
    const XML_PATH_ENHANCED_SOURCE_TEXT     = 'scommerce_googletagmanagerpro/options/admin_source';
    const XML_PATH_ENHANCED_MEDIUM_TEXT     = 'scommerce_googletagmanagerpro/options/admin_medium';
    const XML_PATH_GDPR_COOKIE_ENABLED		= 'scommerce_googletagmanagerpro/options/gdpr_cookie_enabled';
    const XML_PATH_GDPR_FORCE_DECLINE 		= 'scommerce_googletagmanagerpro/options/force_decline';
    const XML_PATH_GDPR_COOKIE_KEY			= 'scommerce_googletagmanagerpro/options/gdpr_cookie_key';
    const XML_PATH_TOTAL_INCLUDE_VAT		= 'scommerce_googletagmanagerpro/options/order_total_include_vat';
    const XML_PATH_ITEM_INCLUDE_VAT		    = 'scommerce_googletagmanagerpro/options/order_item_include_vat';
    const XML_PATH_USE_GA4_LAYOUT  		    = 'scommerce_googletagmanagerpro/options/use_ga4_layout';
    const XML_PATH_USE_UA_LAYOUT  		    = 'scommerce_googletagmanagerpro/options/use_ua_layout';
    const XML_PATH_AFFILIATION              = 'scommerce_googletagmanagerpro/options/affiliation';

    /**
     * returns whether module is enabled or not
     *
     * @return boolean
     */
    public function isEnabled($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_ENABLED, $storeId) && $this->isLicenseValid() && strlen($this->getAccountId($storeId)) && $this->hasCookie();
    }

    /**
     * returns license key administration configuration option
     *
     * @return string
     */
    public function getLicenseKey($storeId = null){
        return Mage::getStoreConfig(self::XML_PATH_LICENSE_KEY, $storeId);
    }

    /**
     * returns whether GDPR cookie check is enabled or not
     *
     * @return boolean
     */
    public function isGDPRCookieEnabled($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_GDPR_COOKIE_ENABLED, $storeId);
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function isGA4Enabled($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_USE_GA4_LAYOUT, $storeId);
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function isUAEnabled($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_USE_UA_LAYOUT, $storeId);
    }

    /**
     * Get Affiliation
     *
     * @param null $storeId
     * @return string
     */
    public function getAffiliation($storeId = null) {
        $affiliation = Mage::getStoreConfig(
            self::XML_PATH_AFFILIATION,
            $storeId
        );
        if (!$affiliation) {
            $affiliation = '';
        }
        return $affiliation;
    }

    public function getCurrency() {
        return Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    /**
     * returns force decline is on or not
     *
     * @return boolean
     */
    public function isGDPRCookieForceDeclined($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_GDPR_FORCE_DECLINE, $storeId);
    }

    /**
     * Get cookie key to check accepted cookie policy
     *
     * @return string
     */
    protected function getCookieKey($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_GDPR_COOKIE_KEY, $storeId);
    }

    /**
     * Check if has cookie with accepted cookie policy
     *
     * @return bool
     */
    protected function hasCookie()
    {
        $cookieKey = $this->getCookieKey();
        if (!$this->isGDPRCookieEnabled() || strlen($cookieKey)==0) return true;
        $cookie = (string)Mage::getModel('core/cookie')->get($cookieKey);
        if (!$this->isGDPRCookieForceDeclined()){
            if ($cookie=="0"){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            if ($cookie=="1"){
                return true;
            }
            else{
                return false;
            }
        }
    }

    /**
     * returns fb pixel id
     *
     * @return string
     */
    public function getAccountId($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_ACCOUNT_ID, $storeId);
    }

    /**
     * returns whether enhanced ecommerce is enabled or not
     *
     * @return boolean
     */
    public function isEEEnabled($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_EE_ENABLED, $storeId) && $this->isEnabled();
    }

    /**
     * returns whether enhanced conversion is enabled or not
     *
     * @return boolean
     */
    public function isEnhancedConversionEnabled($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_ENHANCED_CONVERSION_ENABLED, $storeId) && $this->isEnabled();
    }

    /**
     * returns whether transaction id is enabled or not
     *
     * @return boolean
     */
    public function isTransactionIdEnabled($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_TRANSACTION_ID_ENABLED, $storeId) && $this->isEnabled();
    }

    /**
     * returns enhanced conversion format
     * @param int $storeId Store view ID
     * @return int
     */
    public function getEnhancedConversionFormat($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_ENHANCED_CONVERSION_FORMAT, $storeId);
    }

    /**
     * returns attribute id of brand
     * @param int $storeId Store view ID
     * @return int
     */
    public function getBrandDropdown($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_ENHANCED_BRAND_DROPDOWN, $storeId);
    }

    /**
     * returns brand static text
     * @param int $storeId Store view ID
     * @return string
     */
    public function getBrandText($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_ENHANCED_BRAND_TEXT, $storeId);
    }

    /**
     * returns brand value using product or text
     * @param $product Mage_Catalog_Product
     * @return string
     */
    public function getBrand($product)
    {
        if ($attribute = $this->getBrandDropdown()){
            $data = $product->getAttributeText($attribute);
            if (is_array($data)) $data = end($data);
            if (strlen($data)==0){
                $data = $product->getData($attribute);
            }
            return $data;
        }
        return $this->getBrandText();
    }

    /**
     * returns product price
     * @param $_product Mage_Catalog_Product
     * @return float
     */
    public function getProductPrice($_product)
    {
        if ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
            $_priceModel  = $_product->getPriceModel();

            //list($_minimalPriceTax, $_maximalPriceTax) = $_priceModel->getTotalPrices($_product, null, null, false);
            list($_minimalPriceInclTax, $_maximalPriceInclTax) = $_priceModel->getTotalPrices($_product, null, $this->itemIncludedVAT(), false);

            return $_minimalPriceInclTax;
        }
        else{
            return Mage::helper("tax")->getPrice($_product, $_product->getFinalPrice(), $this->itemIncludedVAT());
        }
    }

    /**
     * returns category name
     * @param $product Mage_Catalog_Product
     * @return string
     */
    public function getProductCategoryName($_product)
    {
        $_cats = $_product->getCategoryIds();
        $_categoryId = array_pop($_cats);

        $_cat = Mage::getModel('catalog/category')->load($_categoryId);
        return $_cat->getName();
    }

    /**
     * returns category name
     * @param $quoteItem Mage_Quote_Item
     * @return string
     */
    public function getQuoteCategoryName($quoteItem)
    {
        if ($_catName = $quoteItem->getCategory()){
            return $_catName;
        }

        $_product = $quoteItem->getProduct();

        return $this->getProductCategoryName($_product);
    }

    /**
     * returns whether base order data is enabled or not
     *
     * @return boolean
     */
    public function sendBaseData($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_BASE, $storeId);
    }

    /**
     * returns whether revenue with shipping is enabled or not
     *
     * @return boolean
     */
    public function revenueWithoutShipping($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_REVENUE, $storeId);
    }

    /**
     * returns whether transaction data should go to GA on admin order creation or not
     * @param int $storeId Store view ID
     * @return boolean
     */
    public function sendPhoneOrderTransaction($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_SPOT, $storeId);
    }

    /**
     * returns source static text
     * @param int $storeId Store view ID
     * @return string
     */
    public function getSourceText($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_ENHANCED_SOURCE_TEXT, $storeId);
    }

    /**
     * returns source static text
     * @param int $storeId Store view ID
     * @return string
     */
    public function getMediumText($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_ENHANCED_MEDIUM_TEXT, $storeId);
    }


    /**
     * checks to see if the extension is enabled for advanced tagging in admin
     *
     * @return boolean
     */

    public function getDynamicRemarketingEnabled()
    {
        return Mage::getStoreConfig( self::XML_PATH_ENABLE_DYNAMIC) && $this->isEnabled();
    }

    /**
     * checks to see if google optimize is enabled or not
     *
     * @return boolean
     */

    public function isGoogleOptimizeEnabled()
    {
        return Mage::getStoreConfig( self::XML_PATH_ENABLE_OPTIMIZE) && $this->isEnabled();
    }

    /**
     * returns optimize container id
     * @param int $storeId Store view ID
     * @return string
     */
    public function getOptimizeID($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_OPTIMIZE_CONTAINER_ID, $storeId);
    }

    /**
     * returns domain cookie
     *
     * @return string
     */
    public function getCookieDomain()
    {
        $cookie = Mage::getSingleton('core/cookie');
        $domain = $cookie->getDomain();
        if (substr($domain,0,1)=="."){
            return $domain;
        }
        else{
            return '.'.$domain;
        }
    }

    /**
     * returns true when registration is confirmed
     *
     * @return bool
     */
    public function bRegistered() {
        if (Mage::getSingleton('core/session')->getRegistered()=='1'){
            Mage::getSingleton('core/session')->setRegistered('0');
            return true;
        }
        return false;
    }

    /**
     * returns true when contact us is posted
     *
     * @return string
     */
    public function bContactCompleted() {
        if (Mage::getSingleton('core/session')->getContactPost()=='1'){
            Mage::getSingleton('core/session')->setContactPost('0');
            return true;
        }
        return false;
    }

    /**
     * returns product attribute key
     *
     * @return string
     */
    public function getProductAtributeKey(){
        return (string)Mage::getStoreConfig(self::XML_PATH_ATTRIBUTE_KEY);
    }

    /**
     * returns whether license key is valid or not
     *
     * @return bool
     */
    public function isLicenseValid(){
        $sku = strtolower(str_replace('_Helper_Data','',str_replace('Scommerce_','',get_class($this))));
        return Mage::helper("scommerce_core")->isLicenseValid($this->getLicenseKey(),$sku);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function totalIncludedVAT($storeId = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_TOTAL_INCLUDE_VAT, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function itemIncludedVAT($storeId = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ITEM_INCLUDE_VAT, $storeId);
    }
}