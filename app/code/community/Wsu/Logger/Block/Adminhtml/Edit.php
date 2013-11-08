<?php
class Wsu_Logger_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct() {
        $this->_blockGroup = 'wsu_logger';
        $this->_controller = 'adminhtml_edit';
        $this->_headerText = Mage::helper('wsu_logger')->__('Error and Warning Logs');
        parent::__construct();
        $this->_removeButton('add');
    }
}
