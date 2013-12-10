<?php 
/**
 * Displays the revert link in the history if applicable
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Block_Adminhtml_History_Grid_Revert extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
    /**
     * Renders the given column
     *
     * @param  Varien_Object $row Column Object
     * @throws Exception
     * @return string Rendered column
     */
    public function render(Varien_Object $row) {
        if ($row instanceof Wsu_Auditing_Model_History) {
            if ($row->isUpdate() && $row->getDecodedContentDiff()) {
                return '<a href="' . $this->getUrl('*/*/revert', array('id' => $row->getId())) . '">Revert</a>';
            }
        } else {
            throw new Exception('block is only compatible to Wsu_Auditing_Model_History');
        }
    }
}
