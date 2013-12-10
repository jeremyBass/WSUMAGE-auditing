<?php
class Wsu_Auditing_Block_Adminhtml_Download_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    public function __construct() {
        parent::__construct();
        $this->setId('downloadLogGrid');
        $this->setDefaultSort('log_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setVarNameFilter('download_log_filter');
    }
    protected function _prepareCollection() {
        $collection = Mage::getModel('wsu_auditing/download_log')->getCollection()->joinProduct();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns() {
        $this->addColumn('log_id', array(
            'header' => Mage::helper('wsu_auditing')->__('Log Id'),
            'index' => 'log_id',
            'type' => 'number'
        ));
        $this->addColumn('title', array(
            'header' => Mage::helper('wsu_auditing')->__('Title'),
            'index' => 'title',
            'type' => 'text'
        ));
        $this->addColumn('file', array(
            'header' => Mage::helper('wsu_auditing')->__('File'),
            'index' => 'file',
            'type' => 'text',
            'frame_callback' => array(
                $this,
                'decorateFile'
            )
        ));
        $this->addColumn('sku', array(
            'header' => Mage::helper('wsu_auditing')->__('SKU'),
            'index' => 'sku',
            'filter_index' => 'product.sku',
            'type' => 'text',
            'frame_callback' => array(
                $this,
                'decorateSku'
            )
        ));
        $this->addColumn('http_user_agent', array(
            'header' => Mage::helper('wsu_auditing')->__('User Agent'),
            'index' => 'http_user_agent',
            'type' => 'text'
        ));
        $this->addColumn('remote_addr', array(
            'header' => Mage::helper('wsu_auditing')->__('IP'),
            'index' => 'remote_addr',
            'type' => 'text',
            'width' => '80px'
        ));
        $this->addColumn('country', array(
            'header' => Mage::helper('wsu_auditing')->__('Country'),
            'index' => 'country',
            'type' => 'text'
        ));
        $this->addColumn('region', array(
            'header' => Mage::helper('wsu_auditing')->__('Region'),
            'index' => 'region',
            'type' => 'text'
        ));
        $this->addColumn('city', array(
            'header' => Mage::helper('wsu_auditing')->__('City'),
            'index' => 'city',
            'type' => 'text'
        ));
        $this->addColumn('created_at', array(
            'header' => Mage::helper('wsu_auditing')->__('Date'),
            'align' => 'right',
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '180px'
        ));
        return parent::_prepareColumns();
    }
    public function decorateFile($value) {
        return basename($value);
    }
    public function decorateSku($value, $row) {
        $html = sprintf('<a href="%s" title="%s">%s</a>', $this->getUrl('adminhtml/catalog_product/edit', array(
            'id' => $row->getProductId()
        )), Mage::helper('wsu_auditing')->__('Edit Product'), $value);
        return $html;
    }
}