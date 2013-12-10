<?php
class Wsu_Auditing_Model_Log_Writer_Stream extends Zend_Log_Writer_Stream {
    /* wsu auditing file path */
    private static $_flfp;
    public function __construct($streamOrUrl, $mode = NULL) {
        self::$_flfp = $streamOrUrl;
        return parent::__construct($streamOrUrl, $mode);
    }
    protected function _write($event) {		
        $auditing = Mage::getModel('wsu_auditing/auditing');
        $auditing->setTimestamp($event['timestamp']);
		
		$userId=0;
		$user = Mage::getSingleton('admin/session');
		if($user->isLoggedIn()) {
			$userId = $user->getUser()->getUserId();
		}
		if($userId>0){
			$auditing->setAdmin_id($userId);
		}else{
			$user=Mage::getSingleton('customer/session');
			if($user->isLoggedIn()) {
				$customerData = $user->getCustomer();
				$userId=$customerData->getId();
				if($userId>0){
					$auditing->setCustomer_id($userId);
				}
			 }	
		}
        $auditing->setMessage($event['message']);
		if(isset($event['trace'])){
			$auditing->setTrace($event['trace']);
		}else{
			$auditing->setTrace(mageDebugBacktrace(true,false,false));
		}
        $auditing->setPriority($event['priority']);
        $auditing->setPriorityName($event['priorityName']);
        if (is_string(self::$_flfp)) {
            $auditing->setFile(self::$_flfp);
        }
        try {
            $auditing->save();
        }
        catch (Exception $e) {
            //echo $e->getMessage(); exit;
            /* Silently die... */
        }
        /* Now pass the execution to original parent code */
        return parent::_write($event);
    }
}
