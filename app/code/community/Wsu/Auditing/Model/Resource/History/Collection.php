<?php
/**
 * History Collection Resource Model
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Model_Resource_History_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    /**
     * Inits the model and resource model for the current collection
     */
    protected function _construct() {
        $this->_init('wsu_auditing/history');
    }
}
