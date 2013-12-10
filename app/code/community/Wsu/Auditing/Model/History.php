<?php
/**
 * History Model
 *
 * @method Wsu_Auditing_Model_Resource_History getResource()
 * @method Wsu_Auditing_Model_Resource_History _getResource()
 * @method int getObjectId()
 * @method string getObjectType()
 * @method string getContent()
 * @method string getContentDiff()
 * @method int getAction()
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Model_History extends Mage_Core_Model_Abstract {
    /**
     * Inits the resource model and resource collection model
     */
    protected function _construct() {
        $this->_init('wsu_auditing/history');
    }

    /**
     * Retrieve the original model
     *
     * @return Mage_Core_Model_Abstract
     */
    public function getOriginalModel() {
        $objectType = $this->getObjectType();

        /* @var Mage_Core_Model_Abstract $model */
        $model = new $objectType;
        $content = $this->getDecodedContent();
        if (isset($content['store_id'])) {
            $model->setStoreId($content['store_id']);
        }
        $model->load($this->getObjectId());

        return $model;
    }

    /**
     * Retrieve the decoded content diff
     *
     * @return array Decoded Content Diff
     */
    public function getDecodedContentDiff() {
        return json_decode($this->getContentDiff(), true);
    }

    /**
     * Retrieve the decoded content
     *
     * @return array Decoded Content
     */
    private function getDecodedContent() {
        return json_decode($this->getContent(), true);
    }

    /**
     * Check if the history action is an update action.
     *
     * @return bool Result
     */
    public function isUpdate() {
        return ($this->getAction() == Wsu_Auditing_Helper_Data::ACTION_UPDATE);
    }

    /**
     * Check if the history action is an delete action.
     *
     * @return bool
     */
    public function isDelete() {
        return ($this->getAction() == Wsu_Auditing_Helper_Data::ACTION_DELETE);
    }
}
