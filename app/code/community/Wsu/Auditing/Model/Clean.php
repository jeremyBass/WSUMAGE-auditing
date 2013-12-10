<?php
/**
 * Cleans the history after a configurable amount of time.
 *
 * @category Wsu
 * @package  Wsu_Auditing
 * @author   Wsu Team <team@wsu.com>
 */
class Wsu_Auditing_Model_Clean {
    const XML_PATH_ADMINMONITORING_INTERVAL = 'wsu_auditing/wsu_auditing/interval';
    const XML_PATH_ADMINMONITORING_CLEAN_ENABLED = 'wsu_auditing/wsu_auditing/enable_cleaning';

    /**
     * Cronjob method for cleaning the database table.
     *
     * @return Wsu_Auditing_Model_Clean
     */
    public function scheduledCleanAuditing() {
        if (!Mage::getStoreConfigFlag(self::XML_PATH_ADMINMONITORING_CLEAN_ENABLED)) {
            return $this;
        }

        try {
            $this->clean();
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this;
    }

    /**
     * Clean the database table for the given interval.
     *
     * @return Wsu_Auditing_Model_Clean
     */
    public function clean() {
        if (!Mage::getStoreConfig(self::XML_PATH_ADMINMONITORING_CLEAN_ENABLED)
            || !Mage::getStoreConfigFlag(self::XML_PATH_ADMINMONITORING_INTERVAL)
        ) {
            return $this;
        }

        $interval = Mage::getStoreConfig(self::XML_PATH_ADMINMONITORING_INTERVAL);

        /* @var $adminMonitoringCollection Wsu_Auditing_Model_Resource_History_Collection */
        $adminMonitoringCollection = Mage::getModel('wsu_auditing/history')
            ->getCollection()
            ->addFieldToFilter(
                'created_at',
                array(
                    'lt' => new Zend_Db_Expr("DATE_SUB('" . now() . "', INTERVAL " . (int) $interval . " DAY)")
                )
            );

        foreach ($adminMonitoringCollection as $history) {
            $history->delete();
        }

        return $this;
    }
}
