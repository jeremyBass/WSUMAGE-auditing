<?php
class Wsu_Auditing_Model_Stock_Observer {
    public function addStockMovementsTab() {
        $layout = Mage::getSingleton('core/layout');
        $block  = $layout->getBlock('product_tabs');
        if ($block) {
            $block->addTab('stock_movements', array(
                'after' => 'inventory',
                'label' => Mage::helper('wsu_auditing')->__('Stock Movements'),
                'content' => $layout->createBlock('wsu_auditing/adminhtml_stock_movement_grid')->toHtml()
            ));
        }
    }
    public function cancelOrderItem($observer) {
        $item     = $observer->getEvent()->getItem();
        $children = $item->getChildrenItems();
        $qty      = $item->getQtyOrdered() - max($item->getQtyShipped(), $item->getQtyInvoiced()) - $item->getQtyCanceled();
        if ($item->getId() && ($productId = $item->getProductId()) && empty($children) && $qty) {
            Mage::getSingleton('cataloginventory/stock')->backItemQty($productId, $qty);
            $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($item->getProductId());
            $this->insertStockMovement($stockItem, sprintf('Product restocked after order cancellation (order: %s)', $item->getOrder()->getIncrementId()));
        }
        return $this;
    }
    public function catalogProductImportFinishBefore($observer) {
        $productIds = array();
        $adapter    = $observer->getEvent()->getAdapter();
        $resource   = Mage::getResourceModel('wsu_auditing/stock_movement');
        if ($adapter instanceof Mage_Catalog_Model_Convert_Adapter_Product) {
            $productIds = $adapter->getAffectedEntityIds();
        } else {
            Mage_ImportExport_Model_Import::getDataSourceModel()->getIterator()->rewind();
            $skus = array();
            while ($bunch = $adapter->getNextBunch()) {
                foreach ($bunch as $rowData) {
                    if (null !== $rowData['sku']) {
                        $skus[] = $rowData['sku'];
                    }
                }
            }
            if (!empty($skus)) {
                $productIds = $resource->getProductsIdBySku($skus);
            }
        }
        if (!empty($productIds)) {
            $stock           = Mage::getSingleton('cataloginventory/stock');
            $stocks          = Mage::getResourceModel('cataloginventory/stock')->getProductsStock($stock, $productIds);
            $stocksMovements = array();
            $datetime        = Varien_Date::formatDate(time());
            foreach ($stocks as $stockData) {
                $stocksMovements[] = array(
                    'item_id' => $stockData['item_id'],
                    'user' => Mage::helper('storeutilities/users')->_getUsername(),
                    'user_id' => Mage::helper('storeutilities/users')->_getUserId(),
                    'qty' => $stockData['qty'],
                    'is_in_stock' => (int) $stockData['is_in_stock'],
                    'message' => 'Product import',
                    'created_at' => $datetime
                );
            }
            if (!empty($stocksMovements)) {
                $resource->insertStocksMovements($stocksMovements);
            }
        }
    }
    public function checkoutAllSubmitAfter($observer) {
        if ($observer->getEvent()->hasOrders()) {
            $orders = $observer->getEvent()->getOrders();
        } else {
            $orders = array(
                $observer->getEvent()->getOrder()
            );
        }
        $stockItems = array();
        foreach ($orders as $order) {
            foreach ($order->getAllItems() as $orderItem) {
                if ($orderItem->getQtyOrdered()) {
                    $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($orderItem->getProductId());
                    if (!isset($stockItems[$stockItem->getId()])) {
                        $stockItems[$stockItem->getId()] = array(
                            'item' => $stockItem,
                            'orders' => array(
                                $order->getIncrementId()
                            )
                        );
                    } else {
                        $stockItems[$stockItem->getId()]['orders'][] = $order->getIncrementId();
                    }
                }
            }
        }
        if (!empty($stockItems)) {
            foreach ($stockItems as $data) {
                $this->insertStockMovement($data['item'], sprintf('Product ordered (order%s: %s)', count($data['orders']) > 1 ? 's' : '', implode(', ', $data['orders'])));
            }
        }
    }
    public function insertStockMovement(Mage_CatalogInventory_Model_Stock_Item $stockItem, $message = '') {
        Mage::getModel('wsu_auditing/stock_movement')
			->setItemId($stockItem->getId())
			->setUser(Mage::helper('storeutilities/users')->_getUsername())
			->setUserId(Mage::helper('storeutilities/users')->_getUserId())
			->setIsAdmin((int) Mage::getSingleton('admin/session')->isLoggedIn())
			->setQty($stockItem->getQty())
			->setIsInStock((int) $stockItem->getIsInStock())
			->setMessage($message)
			->save();
        Mage::getModel('catalog/product')
			->load($stockItem->getProductId())
			->cleanCache();
    }
    public function saveStockItemAfter($observer) {
        $stockItem = $observer->getEvent()->getItem();
        if (!$stockItem->getStockStatusChangedAutomaticallyFlag() || $stockItem->getOriginalInventoryQty() != $stockItem->getQty()) {
            if (!$message = $stockItem->getSaveMovementMessage()) {
                if (Mage::getSingleton('api/session')->getSessionId()) {
                    $message = 'Stock saved from Magento API';
                } else {
                    $message = 'Stock saved manually';
                }
            }
            $this->insertStockMovement($stockItem, $message);
        }
    }
    public function stockRevertProductsSale($observer) {
        $items = $observer->getEvent()->getItems();
        foreach ($items as $productId => $item) {
            $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
            if ($stockItem->getId()) {
                $message = 'Product restocked';
                if ($creditMemo = Mage::registry('current_creditmemo')) {
                    $message = sprintf('Product restocked after credit memo creation (credit memo: %s)', $creditMemo->getIncrementId());
                }
                $this->insertStockMovement($stockItem, $message);
            }
        }
    }
}
