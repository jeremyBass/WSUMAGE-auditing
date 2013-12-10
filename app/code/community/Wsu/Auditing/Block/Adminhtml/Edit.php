<?php
class Wsu_Auditing_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct() {
        $this->_blockGroup = 'wsu_auditing';
        $this->_controller = 'adminhtml_edit';
        $this->_headerText = Mage::helper('wsu_auditing')->__('Error and Warning Logs');
        parent::__construct();
        $this->_removeButton('add');
    }
}
