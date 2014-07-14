<?php


//@todo needs to refactor this big time


$installer = $this;
$installer->startSetup();

$tableDownloadLog = $installer->getTable('wsu_auditing/auditing');
$installer->run(" DROP TABLE IF EXISTS `{$tableDownloadLog}`; ");

$table = $installer->getConnection()
	->newTable($installer->getTable('wsu_auditing/auditing'))
	->addColumn('audit_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
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



$logTable = $installer->getConnection()->newTable($installer->getTable('wsu_auditing/history'))
    ->addColumn( 'history_id', Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
             'identity' => true,
             'unsigned' => true,
             'nullable' => false,
             'primary'  => true,
        ),
        'Primary key of the log entry'
    )
    ->addColumn( 'data', Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(),
        'data of the changed entity'
    )
    ->addColumn( 'user_id', Varien_Db_Ddl_Table::TYPE_INTEGER,
        1,
        array(
             'unsigned' => true,
             'nullable' => true,
             'default'  => null,
        ),
        'user_id of the admin user'
    )
    ->addColumn( 'user_name', Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(),
        'username of the admin user - to know which user it was after deletion'
    )
    ->addColumn( 'ip', Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(),
        'ip of the admin user'
    )
    ->addColumn( 'created_at', Varien_Db_Ddl_Table::TYPE_DATETIME,
        null,
        array(),
        'created at'
    )
    ->addColumn( 'user_agent', Varien_Db_Ddl_Table::TYPE_TEXT,
        null,
        array(),
        'user agent used by user'
    )
    ->addColumn( 'action', Varien_Db_Ddl_Table::TYPE_INTEGER,
        1,
        array(
             'unsigned' => true,
             'nullable' => false,
        ),
        'action which is performed on the object'
    )
    ->addColumn( 'object_type', Varien_Db_Ddl_Table::TYPE_TEXT,
        255,
        array(),
        'class name of the changed type'
    )
    ->addColumn( 'object_id', Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
             'unsigned' => true,
             'nullable' => false,
        ),
        'id of the changed type'
    );

$installer->getConnection()->createTable($logTable);

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

$installer->getConnection()->changeColumn(
    $installer->getTable('wsu_auditing/history'),
    'data',
    'content',
    array(
         'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
         'size'    => null,
         'comment' => 'data of changed entity'
    )
);

$installer->getConnection()->addColumn(
    $installer->getTable('wsu_auditing/history'),
    'content_diff',
    array(
         'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
         'size'    => null,
         'comment' => 'changed data of entity'
    )
);

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


$tableMovement  = $installer->getTable('wsu_stock_movement');
$tableItem      = $installer->getTable('cataloginventory_stock_item');
$tableUser      = $installer->getTable('admin/user');

$installer->run("
DROP TABLE IF EXISTS {$tableMovement};
CREATE TABLE {$tableMovement} (
`movement_id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`item_id` INT( 10 ) UNSIGNED NOT NULL ,
`order_id` varchar(255) NOT NULL DEFAULT '',
`user` varchar(255) NOT NULL DEFAULT '',
`user_id` mediumint(9) unsigned DEFAULT NULL,
`qty` DECIMAL( 12, 4 ) NOT NULL default '0',
`is_in_stock` TINYINT( 1 ) UNSIGNED NOT NULL default '0',
`message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`created_at` DATETIME NOT NULL ,
INDEX ( `item_id` )
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
");

$installer->getConnection()->addConstraint('FK_STOCK_MOVEMENT_ITEM', $tableMovement, 'item_id', $tableItem, 'item_id');
//$installer->getConnection()->addConstraint('FK_STOCK_MOVEMENT_USER', $tableMovement, 'user_id', $tableUser, 'user_id', 'SET NULL');
//$installer->getConnection()->dropForeignKey($tableMovement, 'FK_STOCK_MOVEMENT_USER');
$installer->run("
    ALTER TABLE `{$tableMovement}`
        ADD COLUMN `is_admin` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `user_id`;
");

$installer->endSetup();
