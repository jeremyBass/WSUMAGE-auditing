<?php
$installer = $this;
$installer->startSetup();
$table = $installer->getConnection()
	->newTable($installer->getTable('wsu_logger/logger'))
	->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity' 	=> true,
		'unsigned' 	=> true,
		'nullable' 	=> false,
		'primary' 	=> true
	), 'Id')
	->addColumn('admin_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array(
		'nullable' 	=> false,
		'default' 	=> "0"
	), 'Priority')
	->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array(
		'nullable' 	=> false,
		'default' 	=> "0"
	), 'Priority')
	->addColumn('timestamp', Varien_Db_Ddl_Table::TYPE_CHAR, 25, array(
		'nullable' 	=> false
	), 'Timestamp')
	->addColumn('message', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable' 	=> false
	), 'Message')
	->addColumn('trace', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable' 	=> true
	), 'Stacktrace')
	->addColumn('priority', Varien_Db_Ddl_Table::TYPE_INTEGER, 2, array(
		'nullable' 	=> false
	), 'Priority')
	->addColumn('priority_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
		'nullable' 	=> false
	), 'Priority Name')
	->addColumn('file', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable' 	=> false
	), 'File');
$installer->getConnection()->createTable($table);







$tableDownloadLog = $installer->getTable('wsu_download_log');
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






$installer->endSetup();
