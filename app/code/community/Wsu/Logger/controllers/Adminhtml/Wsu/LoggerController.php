<?php
class Wsu_Logger_Adminhtml_Wsu_LoggerController extends Mage_Adminhtml_Controller_Action {
    public function indexAction() {
        $this->loadLayout()->_setActiveMenu('system/tools/wsu_logger');
        $this->_addContent($this->getLayout()->createBlock('wsu_logger/adminhtml_edit'));
        $this->renderLayout();
    }
    public function gridAction() {
        $this->getResponse()->setBody($this->getLayout()->createBlock('wsu_logger/adminhtml_edit_grid')->toHtml());
    }
}
