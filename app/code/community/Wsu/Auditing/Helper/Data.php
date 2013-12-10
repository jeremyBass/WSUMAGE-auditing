<?php
class Wsu_Auditing_Helper_Data extends Mage_Core_Helper_Data {
    const SCOPE_GLOBAL = 0;
    const SCOPE_WEBSITE = 1;
    const SCOPE_STORE = 2;
    const SCOPE_STORE_VIEW = 3;

    const ACTION_INSERT = 1;
    const ACTION_UPDATE = 2;
    const ACTION_DELETE = 3;
	
	
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
        $log    = Mage::getModel('wsu_auditing/download_log')->addData($ipInfo)->setTitle($title)->setFile($file)->setProductId($productId)->setCustomerId($customerId)->setHttpUserAgent(Mage::helper('core/http')->getHttpUserAgent())->save();
        return $log;
    }
	
	


    /**
     * Retrieve the attribute type by provided class name
     *
     * @param  string $className Class Name
     * @return string|bool Attribute Type or false
     */
    public function getAttributeTypeByClassName($className) {
        /** @var Mage_Eav_Model_Resource_Entity_Type_Collection $typesCollection */
        $typesCollection = Mage::getModel('eav/entity_type')->getCollection();
        foreach ($typesCollection->getItems() as $type) {
            /* @var Mage_Eav_Model_Entity_Type $type */
            if ($type->getEntityModel() && Mage::getModel($type->getEntityModel()) instanceof $className) {
                return $type->getEntityTypeCode();
            }
        }

        return false;
    }

    /**
     * Retrieve the attribute name by provided class name and attribute code
     *
     * @param  string $className     Class Name
     * @param  string $attributeCode Attribute Code
     * @return string Attribute Name
     */
    public function getAttributeNameByTypeAndCode($className, $attributeCode) {
        $attributeName = $attributeCode;

        $type = $this->getAttributeTypeByClassName($className);
        if ($type) {
            /* @var Mage_Eav_Model_Entity_Attribute $attribute */
            $attribute = Mage::getModel('eav/entity_attribute')->loadByCode($type, $attributeCode);
            if ($attribute->getFrontendLabel()) {
                $attributeName = $attribute->getFrontendLabel();
            }
        }

        return $attributeName;
    }
	
	
}
