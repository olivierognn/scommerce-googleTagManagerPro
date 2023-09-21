<?php
/**
 *
 * @category    Scommerce
 * @package     Scommerce_GoogleTagManagerPro
 * @author		Scommerce Mage (core@scommerce-mage.co.uk)
 */
 
class Scommerce_GoogleTagManagerPro_Model_Source_Conversion_Format
{
    public function toOptionArray()
    {
        return [
            [
                'label' => 'Global Javascript',
                'value' => 0
            ],
            [
                'label' => 'Data Layer',
                'value' => 1
            ]
        ];
    }
}