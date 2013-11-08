<?php
class Wsu_Logger_Model_Log_Writer_Stream extends Zend_Log_Writer_Stream {
    /* wsu logger file path */
    private static $_flfp;
    public function __construct($streamOrUrl, $mode = NULL) {
        self::$_flfp = $streamOrUrl;
        return parent::__construct($streamOrUrl, $mode);
    }
    protected function _write($event) {		
        $logger = Mage::getModel('wsu_logger/logger');
        $logger->setTimestamp($event['timestamp']);
		
		$userId=0;
		$user = Mage::getSingleton('admin/session');
		if($user->isLoggedIn()) {
			$userId = $user->getUser()->getUserId();
		}
		if($userId>0){
			$logger->setAdmin_id($userId);
		}else{
			$user=Mage::getSingleton('customer/session');
			if($user->isLoggedIn()) {
				$customerData = $user->getCustomer();
				$userId=$customerData->getId();
				if($userId>0){
					$logger->setCustomer_id($userId);
				}
			 }	
		}
        $logger->setMessage($event['message']);
		if(isset($event['trace'])){
			$logger->setTrace($event['trace']);
		}else{
			$logger->setTrace(mageDebugBacktrace(true,false,false));
		}
        $logger->setPriority($event['priority']);
        $logger->setPriorityName($event['priorityName']);
        if (is_string(self::$_flfp)) {
            $logger->setFile(self::$_flfp);
        }
        try {
            $logger->save();
        }
        catch (Exception $e) {
            //echo $e->getMessage(); exit;
            /* Silently die... */
        }
        /* Now pass the execution to original parent code */
        return parent::_write($event);
    }
}
