<?php
//@todo needs to refactor this big time
$installer = $this;
$installer->startSetup();

$auditing_table = $installer->getTable('wsu_auditing/auditing');
$installer->getConnection()->dropTable($auditing_table);
$installer->run("
    CREATE TABLE `{$auditing_table}` (
        `audit_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `admin_id` INT(10) UNSIGNED DEFAULT NULL,
		`user_agent` TEXT NOT NULL,
		`customer_id` INT(10) UNSIGNED DEFAULT NULL,
		`timestamp` DATETIME NOT NULL default '0000-00-00 00:00:00',
		`message` TEXT NOT NULL,
		`trace` TEXT NOT NULL,
		`priority` INT(2) NOT NULL,
		`priority_name` VARCHAR(32) NOT NULL,
        `file` TEXT NOT NULL
    ) ENGINE=INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Err/Warn log';
");

$history_table = $installer->getTable('wsu_auditing/history');
$installer->getConnection()->dropTable($history_table);
$installer->run("
    CREATE TABLE `{$history_table}` (
        `history_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT(10) UNSIGNED DEFAULT NULL,
		`user_name` VARCHAR(32) NOT NULL,
		`user_agent` TEXT NOT NULL,
		`created_at` DATETIME NOT NULL default '0000-00-00 00:00:00',
		`content` TEXT NOT NULL,
		`content_diff` TEXT NOT NULL,
		`object_id` INT(10) UNSIGNED DEFAULT NULL,
		`object_type` VARCHAR(255) NOT NULL,
		`action` INT(2) NOT NULL,
        `ip` VARCHAR(32) NOT NULL
    ) ENGINE=INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Product stock log';
");

$installer->getConnection()->addIndex(
    $installer->getTable('wsu_auditing/history'),
    $installer->getConnection()->getIndexName(
        $installer->getTable('wsu_auditing/history'),
        array(
             'object_type', 'object_id'
        ),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array(
         'object_type', 'object_id'
    ),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);




$tableDownloadLog = $installer->getTable('wsu_download_log');
$installer->getConnection()->dropTable($tableDownloadLog);
$installer->run("
    DROP TABLE IF EXISTS `{$tableDownloadLog}`;
    CREATE TABLE `{$tableDownloadLog}` (
        `log_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `file` VARCHAR(255) NOT NULL,
        `product_id` INT(10) UNSIGNED DEFAULT NULL,
        `customer_id` INT(10) UNSIGNED DEFAULT NULL,
        `http_user_agent` VARCHAR(255) DEFAULT NULL,
        `remote_addr` VARCHAR(60) DEFAULT NULL,
        `country` VARCHAR(255) DEFAULT NULL,
        `region` VARCHAR(255) DEFAULT NULL,
        `city` VARCHAR(255) DEFAULT NULL,
        `created_at` DATETIME NOT NULL default '0000-00-00 00:00:00'
    ) ENGINE=INNODB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Product downloads log';
");


$tableMovement  = $installer->getTable('wsu_stock_movement');
$tableItem      = $installer->getTable('cataloginventory_stock_item');
$tableUser      = $installer->getTable('admin/user');

$installer->getConnection()->dropTable($tableMovement);
$installer->run("
	DROP TABLE IF EXISTS {$tableMovement};
	CREATE TABLE {$tableMovement} (
		`movement_id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`item_id` INT( 10 ) UNSIGNED NOT NULL ,
		`order_id` varchar(255) NOT NULL DEFAULT '',
		`user` varchar(255) NOT NULL DEFAULT '',
		`user_id` INT(10) UNSIGNED DEFAULT NULL,
		`qty` DECIMAL( 12, 4 ) NOT NULL default '0',
		`is_in_stock` TINYINT( 1 ) UNSIGNED NOT NULL default '0',
		`message` TEXT DEFAULT NULL,
		`is_admin` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
		`created_at` DATETIME NOT NULL default '0000-00-00 00:00:00',
	INDEX ( `item_id` )
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Product stock movment log';
");

$installer->getConnection()->addConstraint('FK_STOCK_MOVEMENT_ITEM', $tableMovement, 'item_id', $tableItem, 'item_id');

$installer->endSetup();
