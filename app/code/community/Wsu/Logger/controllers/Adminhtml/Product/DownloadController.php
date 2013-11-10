<?php
class Wsu_Logger_Adminhtml_Product_DownloadController extends Mage_Adminhtml_Controller_Action {
    public function listAction() {
        $this->_title($this->__('Catalog'))->_title(Mage::helper('wsu_logger')->__('Product Download logs'));
        $this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('wsu_logger/adminhtml_download_log'));
        $this->_setActiveMenu('catalog/product_downloads');
        $this->renderLayout();
    }
}