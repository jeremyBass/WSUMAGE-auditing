<?php
class Wsu_Logger_Adminhtml_Stock_MovementController extends Mage_Adminhtml_Controller_Action {
    public function listAction() {
        $this->_title($this->__('Catalog'))->_title(Mage::helper('wsu_logger')->__('Stock Movements'));
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('wsu_logger/adminhtml_stock_movement'));
        $this->_setActiveMenu('catalog/stock_movements');
        $this->renderLayout();
    }
}