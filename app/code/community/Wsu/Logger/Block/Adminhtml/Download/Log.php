<?php
class Wsu_Logger_Block_Adminhtml_Download_Log extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'wsu_logger';
        $this->_controller = 'adminhtml_download_log';
        $this->_headerText = Mage::helper('wsu_logger')->__('Product Download logs');
        $this->_removeButton('add');
    }
    protected function _prepareLayout() {
        $this->setChild('grid', $this->getLayout()->createBlock('wsu_logger/adminhtml_download_log_grid', 'product_downloads.grid'));
        return parent::_prepareLayout();
    }
    public function getHeaderCssClass() {
        return '';
    }
}