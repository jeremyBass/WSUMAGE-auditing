<?php
class Wsu_Auditing_Block_Adminhtml_Stock_Movement extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'wsu_auditing';
        $this->_controller = 'adminhtml_stock_movement';
        $this->_headerText = Mage::helper('wsu_auditing')->__('Stock Movements');
        $this->_removeButton('add');
    }
    protected function _prepareLayout() {
        $this->setChild('grid', $this->getLayout()->createBlock('wsu_auditing/adminhtml_stock_movement_grid', 'stock_movements.grid'));
        return parent::_prepareLayout();
    }
    public function getHeaderCssClass() {
        return '';
    }
}