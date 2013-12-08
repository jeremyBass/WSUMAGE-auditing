<?php
class Wsu_Logger_Block_Adminhtml_Edit_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    public function __construct() {
        parent::__construct();
        $this->setId('wsu_logger');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }
    protected function _prepareCollection() {
        $collection = Mage::getModel('wsu_logger/logger')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
    protected function _prepareColumns() {
        $this->addColumn('priority_names', array(
            'header' => Mage::helper('wsu_logger')->__('Code'),
            'index' => 'priority_name',
            'type' => 'text'
        ));
        $this->addColumn('priorities', array(
            'header' => Mage::helper('wsu_logger')->__('Level'),
            'index' => 'priority',
            'type' => 'text'
        ));
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('wsu_logger')->__('ID'),
            'sortable' => true,
            'index' => 'entity_id'
        ));
        $this->addColumn('timestamp', array(
            'header' => Mage::helper('wsu_logger')->__('Timestamp'),
            'index' => 'timestamp',
            'type' => 'text',
            'width' => '170px'
        ));
        $this->addColumn('message', array(
            'header' => Mage::helper('wsu_logger')->__('Message'),
            'index' => 'message',
            'type' => 'text'
        ));
		
		//Note this become one and just do a color dif on the table.  IE just a class diff
        $this->addColumn('customer_id', array(
            'header' => Mage::helper('wsu_logger')->__('Customer'),
            'index' => 'customer_id',
            'type' => 'text'
        ));
        $this->addColumn('admin_id', array(
            'header' => Mage::helper('wsu_logger')->__('Admin User'),
            'index' => 'admin_id',
            'type' => 'text'
        ));
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('wsu_logger')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('wsu_logger')->__('full log'),
                        'url'       => array('base'=> '*/*/fulllogjson'),
						'field'     => 'log_id'
                    )
                ),
				'class'		=> 'getFullLog',
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
        return parent::_prepareColumns();
    }
    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array(
            '_current' => true
        ));
    }
}
