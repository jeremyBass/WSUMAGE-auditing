<?php
class Wsu_Logger_Model_Resource_Download_Log_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    public function _construct() {
        $this->_init('wsu_logger/download_log');
    }
    public function joinProduct() {
        $this->getSelect()->joinLeft(array(
            'product' => $this->getTable('catalog/product')
        ), 'main_table.product_id = product.entity_id', array(
            'sku' => 'product.sku'
        ));
        return $this;
    }
}