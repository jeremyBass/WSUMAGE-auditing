<?php
/**
 * Displays the logging history grid container
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Block_Adminhtml_History extends Mage_Adminhtml_Block_Widget_Grid_Container {
    /**
     * Constructor of the grid container
     */
    public function __construct() {
        $this->_blockGroup = 'wsu_auditing';
        $this->_controller = 'adminhtml_history';
        $this->_headerText = Mage::helper('wsu_auditing')->__('History');
        parent::__construct();
        $this->removeButton('add');
    }
}
