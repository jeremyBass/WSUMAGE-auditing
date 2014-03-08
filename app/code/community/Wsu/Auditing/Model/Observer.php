<?php
class Wsu_Auditing_Model_Observer {
	public function controller_action_postdispatch_api(Varien_Event_Observer $observer) {

		$enable = Mage::getStoreConfig('wsu_auditing/apilog/enablelogging');
		if (!$enable) {
			return;
		}

		$controllerAction = $observer->getControllerAction(); /* @var $controllerAction Mage_Api_IndexController */

		$logFormat = Mage::getStoreConfig('wsu_auditing/apilog/logformat');
		if (empty($logFormat)) {
			$logFormat = 'wsu_auditing: No logformat configuration found in wsu_auditing/apilog/logformat';
		}

		$replace = array(
			'###REQUESTURI###' => $controllerAction->getRequest()->getRequestUri(),
			'###CLIENTIP###' => $controllerAction->getRequest()->getClientIp(),
			'###REQUEST###' => file_get_contents('php://input'),
			'###RESPONSE###' => $controllerAction->getResponse()->getBody()
		);

		$message = str_replace(array_keys($replace), array_values($replace), $logFormat);
		$fileName = Mage::getStoreConfig('wsu_auditing/apilog/logfilename');

		Mage::log($message, null, $fileName);
	}
    public function addAuditingGridJavascriptBlock($observer){
        $controller = $observer->getAction();
        $layout = $controller->getLayout();
        $block = $layout->createBlock('adminhtml/template');
        $block->setTemplate('wsu/auditing/auditing/grid/jsblock.phtml');    
		if($layout->getBlock('js')){    
        	$layout->getBlock('js')->append($block);
		}
    }

}
