<?php
class Wsu_Auditing_Model_Resource_Download_Log extends Mage_Core_Model_Resource_Db_Abstract {
    protected function _construct() {
        $this->_init('wsu_auditing/download_log', 'log_id');
    }
    protected function _prepareDataForSave(Mage_Core_Model_Abstract $object) {
        $currentTime = Varien_Date::now();
        if ((!$object->getId() || $object->isObjectNew()) && !$object->getCreatedAt()) {
            $object->setCreatedAt($currentTime);
        }
        $data = parent::_prepareDataForSave($object);
        return $data;
    }
}