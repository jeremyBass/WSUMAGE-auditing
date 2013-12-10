<?php
/**
 * History Diff Model
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Model_History_Diff {
    /**
     * @var Wsu_Auditing_Model_History_Data
     */
    protected $_dataModel;

    /**
     * Init the data model
     *
     * @param Wsu_Auditing_Model_History_Data $dataModel History Data Model
     */
    public function __construct(Wsu_Auditing_Model_History_Data $dataModel) {
        $this->_dataModel = $dataModel;
    }

    /**
     * Check if the data has changed.
     *
     * @return bool Result
     */
    public function hasChanged() {
        return ($this->_dataModel->getContent() != $this->_dataModel->getOrigContent());
    }

    /**
     * Generate an object diff of the original content and the actual content.
     *
     * @return array Diff Array
     */
    private function getObjectDiff() {
        $dataOld = $this->_dataModel->getOrigContent();
        if (is_array($dataOld)) {
            $dataNew = $this->_dataModel->getContent();
            $dataDiff = array();
            foreach ($dataOld as $key => $oldValue) {
                // compare objects serialized
                if (isset($dataNew[$key])
                    && (json_encode($oldValue) != json_encode($dataNew[$key]))
                ) {
                    $dataDiff[$key] = $oldValue;
                }
            }

            return $dataDiff;
        } else {
            return array();
        }
    }

    /**
     * Retrieve the serialized diff for the current data model.
     *
     * @return string Serialized Diff
     */
    public function getSerializedDiff() {
        $dataDiff = $this->getObjectDiff();
        return json_encode($dataDiff);
    }
}
