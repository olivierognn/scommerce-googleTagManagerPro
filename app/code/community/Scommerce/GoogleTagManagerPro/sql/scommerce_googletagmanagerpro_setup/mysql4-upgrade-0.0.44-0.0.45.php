<?php
$installer = $this;

$installer->startSetup();

$sales_setup =  new Mage_Sales_Model_Mysql4_Setup('sales_setup');

$attribute  = array(
    'type'          => 'varchar',
    'label'         => 'Tracking list',
    'default'       => '',
    'visible'       => false,
    'required'      => false,
    'user_defined'  => true,
    'searchable'    => false,
    'filterable'    => false,
    'comparable'    => false );

$installer->getConnection()->addColumn(
    $installer->getTable('sales_flat_quote_item'),
    'tracking_list',
    'varchar(255) NULL DEFAULT NULL'
);

$sales_setup->addAttribute('quote_item', 'tracking_list', $attribute);

$installer->endSetup();