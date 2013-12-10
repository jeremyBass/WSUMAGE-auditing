<?php
/**
 * Abstract observer class; provides some common functions for subclasses
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
abstract class Wsu_Auditing_Model_Observer_Model_Abstract {
    /**
     * @var Mage_Core_Model_Abstract
     */
    protected $_savedModel;

    /**
     * @var Wsu_Auditing_Model_History_Diff
     */
    protected $_diffModel;

    /**
     * @var Wsu_Auditing_Model_History_Data
     */
    protected $_dataModel;

    /**
     * Abstract method for retrieving the history action.
     *
     * @return int
     */
    abstract protected function getAction();

    /**
     * Handle the model_save_after and model_delete_after events
     *
     * @param Varien_Event_Observer $observer Observer Instance
     */
    public function modelAfter(Varien_Event_Observer $observer) {
        $this->storeByObserver($observer);
    }

    /**
     * Check if the data has changed.
     *
     * @return bool
     */
    protected function hasChanged() {
        return $this->_diffModel->hasChanged();
    }

    /**
     * Check if the data has changed and create a history entry if there are changes.
     *
     * @param Varien_Event_Observer $observer Observer Instance
     */
    protected function storeByObserver(Varien_Event_Observer $observer) {
        /* @var $savedModel Mage_Core_Model_Abstract */
        $savedModel = $observer->getObject();
        $this->_savedModel = $savedModel;

        if (!$this->isExcludedClass($savedModel)) {
            $this->_dataModel = Mage::getModel('wsu_auditing/history_data', $savedModel);
            $this->_diffModel = Mage::getModel('wsu_auditing/history_diff', $this->_dataModel);

            if ($this->hasChanged()) {
                $this->createHistoryForModelAction();
            }
        }
    }

    /**
     * Dispatch event for creating a history entry
     */
    private function createHistoryForModelAction() {
        $eventData = array(
             'object_id' => $this->_dataModel->getObjectId(),
             'object_type' => $this->_dataModel->getObjectType(),
             'content' => $this->_dataModel->getSerializedContent(),
             'content_diff' => $this->_diffModel->getSerializedDiff(),
             'action' => $this->getAction(),
        );

        Mage::dispatchEvent('wsu_auditing_log', $eventData);
    }

    /**
     * Check if the dispatched model has to be excluded from the logging.
     *
     * @return bool Result
     */
    private function isExcludedClass() {
        $savedModel = $this->_savedModel;

        $objectTypeExcludes = array_keys(Mage::getStoreConfig('wsu_auditing_config/exclude/object_types'));
        $objectTypeExcludesFiltered = array_filter(
            $objectTypeExcludes,
            function ($className) use ($savedModel) {
                return is_a($savedModel, $className);
            }
        );

        return (count($objectTypeExcludesFiltered) > 0);
    }
}
