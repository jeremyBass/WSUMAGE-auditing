<?php
class Wsu_Auditing_Model_Resource_Auditing_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    public function _construct() {
        $this->_init('wsu_auditing/auditing');
    }
}
