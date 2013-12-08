<?php
class Wsu_Logger_Model_Observer {
	public function controller_action_postdispatch_api(Varien_Event_Observer $observer) {

		$enable = Mage::getStoreConfig('wsu_logger/apilog/enablelogging');
		if (!$enable) {
			return;
		}

		$controllerAction = $observer->getControllerAction(); /* @var $controllerAction Mage_Api_IndexController */

		$logFormat = Mage::getStoreConfig('wsu_logger/apilog/logformat');
		if (empty($logFormat)) {
			$logFormat = 'wsu_logger: No logformat configuration found in wsu_logger/apilog/logformat';
		}

		$replace = array(
			'###REQUESTURI###' => $controllerAction->getRequest()->getRequestUri(),
			'###CLIENTIP###' => $controllerAction->getRequest()->getClientIp(),
			'###REQUEST###' => file_get_contents('php://input'),
			'###RESPONSE###' => $controllerAction->getResponse()->getBody()
		);

		$message = str_replace(array_keys($replace), array_values($replace), $logFormat);
		$fileName = Mage::getStoreConfig('wsu_logger/apilog/logfilename');

		Mage::log($message, null, $fileName);
	}
    public function addLoggerGridJavascriptBlock($observer){
        $controller = $observer->getAction();
        $layout = $controller->getLayout();
        $block = $layout->createBlock('adminhtml/template');
        $block->setTemplate('wsu/logger/logger/grid/jsblock.phtml');        
        $layout->getBlock('js')->append($block);
    }

}
