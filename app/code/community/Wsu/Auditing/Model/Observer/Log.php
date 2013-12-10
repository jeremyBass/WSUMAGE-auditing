<?php
/**
 * Logging model
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Model_Observer_Log {
    /**
     * Log the data for the given observer model.
     *
     * @param Varien_Event_Observer $observer Observer Instance
     */
    public function log(Varien_Event_Observer $observer) {
        /* @var $history Wsu_Auditing_Model_History */
        $history = Mage::getModel('wsu_auditing/history');
        $history->setData(array(
                 'object_id'    => $observer->getObjectId(),
                 'object_type'  => $observer->getObjectType(),
                 'content'      => $observer->getContent(),
                 'content_diff' => $observer->getContentDiff(),
                 'user_agent'   => $this->getUserAgent(),
                 'ip'           => $this->getRemoteAddr(),
                 'user_id'      => $this->getUserId(),
                 'user_name'    => $this->getUserName(),
                 'action'       => $observer->getAction(),
                 'created_at'   => now(),
        ));

        $history->save();
    }

    /**
     * Retrieve the current admin user id
     *
     * @return int User ID
     */
    public function getUserId() {
        if ($this->getUser()) {
            $userId = $this->getUser()->getUserId();
        } else {
            $userId = 0;
        }

        return $userId;
    }

    /**
     * Retrieve the current admin user name
     *
     * @return string User Name
     */
    public function getUserName() {
        if ($this->getUser()) {
            $userName = $this->getUser()->getUsername();
        } else {
            $userName = '';
        }

        return $userName;
    }

    /**
     * Retrieve the current admin user
     *
     * @return Mage_Admin_Model_User
     */
    public function getUser() {
        /* @var $session Mage_Admin_Model_Session */
        $session = Mage::getSingleton('admin/session');
        return $session->getUser();
    }

    /**
     * Retrieve the user agent of the current user.
     *
     * @return string User Agent
     */
    public function getUserAgent() {
        return (isset($_SERVER['HTTP_USER_AGENT']) ? (string) $_SERVER['HTTP_USER_AGENT'] : '');
    }

    /**
     * Retrieve the remote address of the current user.
     *
     * @return string IPv4|long
     */
    public function getRemoteAddr() {
        return Mage::helper('core/http')->getRemoteAddr();
    }
}
