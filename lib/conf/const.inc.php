<?php

//Company Name
define('_product_name_','Bizsoft');
define('_company_name_','Abirami Colors');

//DB Configuration
define('DB_ENGINE','pgsql');
define('DB_NAME','live');
define('DB_USER','sathish');
define('DB_PASSWORD','');
define('DB_SCHEMA','demo');

// Include path
define('_base_path_',ini_get('include_path'));
define('_tpl_path_',str_replace('/lib/','',_base_path_).'/tpl/');
define('_xajax_reg_path_',str_replace('/lib/','',_base_path_).'/reg/');
//define('_tpl_path_','/home/sathish/bizsoft/tpl/');
define('_smarty_path_',_base_path_.'smarty/');
define('_xml_path_',_base_path_.'xml/');
define('_offset_path_','/sathish/bizsoft');

//DB Table Names
define('city_table','city');
define('columns_table','columns');
define('contacts_table','contacts');
define('country_table','country');
define('currency_table','currency');
define('customization_columns_table','customization_columns');
define('customizations_table','customizations');
define('handloan_table','handloan');
define('handloan_items_table','handloan_items');
define('orders_table','orders');
define('order_items_table','order_items');
define('products_table','products');
define('product_category_table','product_category');
define('payments_table','payments');
define('payment_mode_table','payment_mode');
define('receipt_table','receipt');
define('receipt_items_table','receipt_items');
define('receipt_tax_table','receipt_tax');
define('receipt_payment_relation_table','receipt_payment_relation');
define('relationships_table','relationships');
define('reports_table','reports');
define('sales_table','sales');
define('sales_items_table','sales_items');
define('sales_tax_table','sales_tax');
define('sales_payment_relation_table','sales_payment_relation');
define('tax_table','tax');
define('transaction_balance_table','transaction_balance');
define('transaction_details_table','transaction_details');
define('uom_table','uom');
define('users_table','users');

//DB Sequences
define('city_city_id_seq','city_city_id_seq');
define('columns_column_id_seq','columns_column_id_seq');
define('contacts_contact_id_seq','contacts_contact_id_seq');
define('country_country_id_seq','country_country_id_seq');
define('currency_currency_id_seq','currency_currency_id_seq');
define('customizations_customization_id_seq','customizations_customization_id_seq');
define('handloan_handln_id_seq','handloan_handln_id_seq');
define('handloan_items_item_id_seq','handloan_items_item_id_seq');
define('orders_order_id_seq','orders_order_id_seq');
define('order_items_item_id_seq','order_items_item_id_seq');
define('products_product_id_seq','products_product_id_seq');
define('product_category_product_category_id_seq','product_category_product_category_id_seq');
define('payment_mode_payment_mode_id_seq','payment_mode_payment_mode_id_seq');
define('payments_payment_id_seq','payments_payment_id_seq');
define('receipt_items_item_id_seq','receipt_items_item_id_seq');
define('receipt_receipt_id_seq','receipt_receipt_id_seq');
define('relationships_relationship_id_seq','relationships_relationship_id_seq');
define('reports_report_id_seq','reports_report_id_seq');
define('sales_items_item_id_seq','sales_items_item_id_seq');
define('sales_sales_id_seq','sales_sales_id_seq');
define('tax_tax_id_seq','tax_tax_id_seq');
define('transaction_details_trans_id_seq','transaction_details_trans_id_seq');

//prefined db master constants
//definitions of trans type
define('_TRANS_IN_TYPE','i');
define('_TRANS_OUT_TYPE','o');

//definitions of trans head
define('_TRANS_PUR_HEAD','pur');
define('_TRANS_SAL_HEAD','sal');
define('_TRANS_ADJ_HEAD','adj');
define('_TRANS_HDL_HEAD','hdl');
define('_TRANS_HDL_HEAD','trn');

//definitions of data status
define('_STATUS_ACT','act');
define('_STATUS_COM','com');
define('_STATUS_CAN','can');
define('_STATUS_DEL','del');

//definitions of order type
define('_ORDER_IO_TYPE','io');
define('_ORDER_PO_TYPE','po');
define('_ORDER_SO_TYPE','so');
?>
