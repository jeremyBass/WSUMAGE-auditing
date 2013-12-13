<?php
class Wsu_Auditing_Model_Resource_History extends Mage_Core_Model_Resource_Db_Abstract {
    /**
     * Inits the main table and the id field name
     */
    protected function _construct() {
        $this->_init('wsu_auditing/history', 'history_id');
    }
}
