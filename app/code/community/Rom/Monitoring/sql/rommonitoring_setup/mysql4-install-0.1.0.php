<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('rommonitoring_log')};
CREATE TABLE {$this->getTable('rommonitoring_log')} (
  `id` smallint(6) NOT NULL auto_increment,
  `range_key` varchar(255) NOT NULL default '',
  `order_amount` int(11) NOT NULL default '1',
  `execution_day` date default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Rom Monitoring Log';
");

$installer->endSetup();
