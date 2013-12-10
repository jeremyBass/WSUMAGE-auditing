<?php
/**
 * History controller
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Adminhtml_HistoryController extends Mage_Adminhtml_Controller_Action {
    /**
     * Inits the layout, the active menu tab and the breadcrumbs
     *
     * @return Wsu_Auditing_Adminhtml_HistoryController
     */
    protected function _initAction() {
        $this->loadLayout();
        $this->_setActiveMenu('wsu_auditing/history');
        $this->_addBreadcrumb(
            Mage::helper('wsu_auditing')->__('Admin Monitoring'),
            Mage::helper('wsu_auditing')->__('History')
        );

        return $this;
    }

    /**
     * Shows the history grid
     */
    public function indexAction()  {
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Reverts a history entry
     */
    public function revertAction() {
        /* @var $history Wsu_Auditing_Model_History */
        $history = Mage::getModel('wsu_auditing/history')->load($this->getRequest()->getParam('id'));

        if (!$history->getId()) {
            $model = $history->getOriginalModel();
            $model->addData($history->getDecodedContentDiff());
            $model->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__(
                    'Revert of %1$s with id %2$d successful',
                    $history->getObjectType(),
                    $history->getObjectId()
                )
            );
        }

        $this->_redirect('*/*/index');
    }
}
