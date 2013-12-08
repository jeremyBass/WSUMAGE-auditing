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
	
	public function fulllogjsonAction(){
		$callback = $this->getRequest()->getParam('callback');
		$logId = $this->getRequest()->getParam('log_id');
		$log = Mage::getModel('wsu_logger/logger')->getCollection();
		$l = $log->addFieldToFilter('entity_id',$logId)->getFirstItem();
		$logObj = array(
			'entity_id'=>$l->getEntityid(),
			'admin_id'=>$l->getAdminid(),
			'customer_id'=>$l->getCustomerid(),
			'timestamp'=>$l->getTimestamp(),
			'message'=>$l->getMessage(),
			'trace'=>$l->getTrace(),
			'priority'=>$l->getPriority(),
			'priority_name'=>$l->getPriorityname(),
			'file'=>$l->getFile()
		);
		$jsonData = json_encode($logObj);
		$this->getResponse()->setHeader('Content-type', 'application/json');
		$this->getResponse()->setBody("".$callback."(".$jsonData.")");
	}
}
