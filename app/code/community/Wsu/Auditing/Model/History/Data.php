<?php
/**
 * History Data Model
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Model_History_Data {
    /**
     * @var Mage_Core_Model_Abstract
     */
    protected $_savedModel;

    /**
     * Init the saved model
     *
     * @param Mage_Core_Model_Abstract $savedModel Model which is to be saved
     */
    public function __construct(Mage_Core_Model_Abstract $savedModel) {
        $this->_savedModel = $savedModel;
    }

    /**
     * Retrieve the serialized content
     *
     * @return string Serialized Content
     */
    public function getSerializedContent() {
        return json_encode($this->getContent());
    }

    /**
     * Retrieve the content of the saved model
     *
     * @return array Content
     */
    public function getContent() {
        // have to re-load the model as based on database datatypes the format of values changes
        $className = get_class($this->_savedModel);
        $model = new $className;
        $model->setStoreId($this->_savedModel->getStoreId());
        $model->load($this->_savedModel->getId());

        return $this->filterObligatoryFields($model->getData());
    }

    /**
     * Remove the obligatory fields from the data
     *
     * @param  array $data Data
     * @return array Filtered Data
     */
    protected function filterObligatoryFields($data) {
        $fields = array_keys(Mage::getStoreConfig('wsu_auditing_config/exclude/fields'));
        foreach ($fields as $field) {
            unset($data[$field]);
        }

        return $data;
    }

    /**
     * Retrieve the original content of the saved model
     *
     * @return array Data
     */
    public function getOrigContent() {
        $data = $this->_savedModel->getOrigData();
        return $this->filterObligatoryFields($data);
    }

    /**
     * Retrieve the object type of the saved model
     *
     * @return string Object Type
     */
    public function getObjectType() {
        return get_class($this->_savedModel);
    }

    /**
     * Retrieve the object id of the saved model
     *
     * @return int Object ID
     */
    public function getObjectId() {
        return $this->_savedModel->getId();
    }
}
