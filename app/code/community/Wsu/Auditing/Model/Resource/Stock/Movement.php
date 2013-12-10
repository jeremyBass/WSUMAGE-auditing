<?php
class Wsu_Auditing_Model_Resource_Stock_Movement extends Mage_Core_Model_Resource_Db_Abstract {
    protected function _construct() {
        $this->_init('wsu_auditing/stock_movement', 'movement_id');
    }
    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        if (!$object->getCreatedAt()) {
            $object->setCreatedAt($this->formatDate(time()));
        }
        return parent::_beforeSave($object);
    }
    public function insertStocksMovements($stocksMovements) {
        $this->_getWriteAdapter()->insertMultiple($this->getMainTable(), $stocksMovements);
        return $this;
    }
    public function getProductsIdBySku($skus) {
        $select = $this->getReadConnection()->select()->from($this->getTable('catalog/product'), array(
            'audit_id'
        ))->where('sku IN (?)', (array) $skus);
        return $this->getReadConnection()->fetchCol($select);
    }
}