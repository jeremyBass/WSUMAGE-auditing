<?php
class Wsu_Logger_Helper_Data extends Mage_Core_Helper_Data {
    public function getIpInfo() {
        $ip     = Mage::helper('core/http')->getRemoteAddr();
        $ipInfo = json_decode(@file_get_contents('http://ip-api.com/json/' . $ip), true);
        return array(
            'remote_addr' => $ip,
            'country' => @$ipInfo['country'],
            'region' => @$ipInfo['regionName'],
            'city' => @$ipInfo['city']
        );
    }
    public function getLinkTitle($link) {
        $resource = Mage::getSingleton('core/resource');
        $read     = $resource->getConnection('core_read');
        $select   = $read->select()->from($read->getTableName('downloadable_link_title'), array(
            'title'
        ))->where('link_id = ?', (int) $link->getId())->where('store_id = 0');
        $title    = $read->fetchOne($select);
        return $title ? $title : '';
    }
    public function log($title, $file, $productId = null, $customerId = null) {
        $ipInfo = $this->getIpInfo();
        $log    = Mage::getModel('wsu_logger/download_log')->addData($ipInfo)->setTitle($title)->setFile($file)->setProductId($productId)->setCustomerId($customerId)->setHttpUserAgent(Mage::helper('core/http')->getHttpUserAgent())->save();
        return $log;
    }
}
