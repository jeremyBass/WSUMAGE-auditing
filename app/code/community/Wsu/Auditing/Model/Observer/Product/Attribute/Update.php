<?php
/**
 * Observes the product attribute updates
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Model_Observer_Product_Attribute_Update extends Wsu_Auditing_Model_Observer_Log {
    const XML_PATH_LOGGER_PROD_ATTR_UPDATE = 'wsu_auditing/wsu_auditing/product_mass_update_logging';

    /**
     * Observe the catalog product attribute update before
     *
     * @param  Varien_Event_Observer $observer Observer Instance
     * @return void
     */
    public function catalogProductAttributeUpdateBefore(Varien_Event_Observer $observer) {
        if (!Mage::getStoreConfigFlag(self::XML_PATH_LOGGER_PROD_ATTR_UPDATE)) {
            return;
        }

        /* @var Wsu_Auditing_Model_History $history */
        $history = Mage::getModel('wsu_auditing/history');

        $objectType = get_class(Mage::getModel('catalog/product'));
        $content = json_encode($observer->getEvent()->getAttributesData());
        $userAgent = $this->getUserAgent();
        $ip = $this->getRemoteAddr();
        $userId = $this->getUserId();
        $userName = $this->getUserName();

        foreach ($observer->getEvent()->getProductIds() as $productId) {
            $history->setData(array(
                    'object_id' => $productId,
                    'object_type' => $objectType,
                    'content' => $content,
                    'content_diff' => '{}',
                    'user_agent' => $userAgent,
                    'ip' => $ip,
                    'user_id' => $userId,
                    'user_name' => $userName,
                    'action' => Wsu_Auditing_Helper_Data::ACTION_UPDATE,
                    'created_at' => now(),
            ));

            $history->save();
            $history->clearInstance();
        }
    }
}
