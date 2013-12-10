<?php
/**
 * Observes Model Delete
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Model_Observer_Model_Delete extends Wsu_Auditing_Model_Observer_Model_Abstract {
    /**
     * Retrieve the current action id
     *
     * @return int Action ID
     */
    protected function getAction() {
        return Wsu_Auditing_Helper_Data::ACTION_DELETE;
    }
}
