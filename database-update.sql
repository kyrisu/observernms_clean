ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedBytes_input` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedBytes_input_prev` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedBytes_input_delta` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedBytes_input_rate` int(11) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedBytes_output` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedBytes_output_prev` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedBytes_output_delta` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedBytes_output_rate` int(11) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedPkts_input` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedPkts_input_prev` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedPkts_input_delta` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedPkts_input_rate` int(11) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedPkts_output` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedPkts_output_prev` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedPkts_output_delta` bigint(20) default NULL;
ALTER TABLE `mac_accounting` ADD `cipMacHCSwitchedPkts_output_rate` int(11) default NULL;
ALTER TABLE `mac_accounting` ADD `poll_time` int(11) default NULL;
ALTER TABLE `mac_accounting` ADD `poll_prev` int(11) default NULL;
ALTER TABLE `mac_accounting` ADD `poll_period` int(11) default NULL;
ALTER TABLE `interfaces` ADD `ifInUcastPkts` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifInUcastPkts_prev` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifInUcastPkts_delta` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifInUcastPkts_rate` int(11) default NULL;
ALTER TABLE `interfaces` ADD `ifOutUcastPkts` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifOutUcastPkts_prev` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifOutUcastPkts_delta` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifOutUcastPkts_rate` int(11) default NULL;
ALTER TABLE `interfaces` ADD `ifInErrors` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifInErrors_prev` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifInErrors_delta` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifInErrors_rate` int(11) default NULL;
ALTER TABLE `interfaces` ADD `ifOutErrors` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifOutErrors_prev` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifOutErrors_delta` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifOutErrors_rate` int(11) default NULL;
ALTER TABLE `interfaces` ADD `ifInOctets` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifInOctets_prev` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifInOctets_delta` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifInOctets_rate` int(11) default NULL;
ALTER TABLE `interfaces` ADD `ifOutOctets` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifOutOctets_prev` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifOutOctets_delta` bigint(20) default NULL;
ALTER TABLE `interfaces` ADD `ifOutOctets_rate` int(11) default NULL;
ALTER TABLE `interfaces` ADD `poll_time` int(11) default NULL;
ALTER TABLE `interfaces` ADD `poll_prev` int(11) default NULL;
ALTER TABLE `interfaces` ADD `poll_period` int(11) default NULL;
ALTER TABLE `interfaces` ADD `pagpOperationMode` VARCHAR( 32 ) NULL ;
ALTER TABLE `interfaces` ADD `pagpPortState` VARCHAR( 16 ) NULL ;
ALTER TABLE `interfaces` ADD `pagpPartnerDeviceId` VARCHAR( 48 ) NULL ;
ALTER TABLE `interfaces` ADD `pagpPartnerLearnMethod` VARCHAR( 16 ) NULL ;
ALTER TABLE `interfaces` ADD `pagpPartnerIfIndex` INT NULL ;
ALTER TABLE `interfaces` ADD `pagpPartnerGroupIfIndex` INT NULL ;
ALTER TABLE `interfaces` ADD `pagpPartnerDeviceName` VARCHAR( 128 ) NULL ;
ALTER TABLE `interfaces` ADD `pagpEthcOperationMode` VARCHAR( 16 ) NULL ;
ALTER TABLE `interfaces` ADD `pagpDeviceId` VARCHAR( 48 ) NULL ;
ALTER TABLE `interfaces` ADD `pagpGroupIfIndex` INT NULL ;
ALTER TABLE `interfaces` ADD `ifPromiscuousMode` VARCHAR( 12 )  NULL DEFAULT NULL AFTER `ifSpeed`;
ALTER TABLE `interfaces` ADD `ifConnectorPresent` VARCHAR( 12 ) NULL DEFAULT NULL AFTER `ifSpeed`;
ALTER TABLE `interfaces` ADD `ifName` VARCHAR( 64 )  NULL DEFAULT NULL AFTER `ifDescr`;
ALTER TABLE `interfaces` ADD `portName` VARCHAR( 128 ) NULL DEFAULT NULL AFTER `ifName`;
ALTER TABLE `interfaces` ADD `ifHighSpeed` BIGINT ( 20 ) NULL DEFAULT NULL AFTER `ifSpeed`;
ALTER TABLE `interfaces` DROP `in_rate`;
ALTER TABLE `interfaces` DROP `out_rate`;
ALTER TABLE `interfaces` DROP `in_errors`;
ALTER TABLE `interfaces` DROP `out_errors`;
CREATE TABLE IF NOT EXISTS `cmpMemPool` (  `cmp_id` int(11) NOT NULL auto_increment,  `Index` varchar(8) NOT NULL,  `cmpName` varchar(32) NOT NULL,  `cmpValid` varchar(8) NOT NULL,  `device_id` int(11) NOT NULL,  `cmpUsed` int(11) NOT NULL,  `cmpFree` int(11) NOT NULL,  `cmpLargestFree` int(11) NOT NULL,  `cmpAlternate` tinyint(4) default NULL,  PRIMARY KEY  (`cmp_id`),  KEY `device_id` (`device_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `hrDevice` ( `hrDevice_id` int(11) NOT NULL auto_increment,  `device_id` int(11) NOT NULL,  `hrDeviceIndex` int(11) NOT NULL,  `hrDeviceDescr` text NOT NULL,  `hrDeviceType` text NOT NULL,  `hrDeviceErrors` int(11) NOT NULL,  `hrDeviceStatus` text NOT NULL,  `hrProcessorLoad` tinyint(4) default NULL,  PRIMARY KEY  (`hrDevice_id`),  KEY `device_id` (`device_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
ALTER TABLE `entPhysical` ADD  `entPhysicalHardwareRev` VARCHAR( 16 ) NULL AFTER  `entPhysicalName` ,ADD  `entPhysicalFirmwareRev` VARCHAR( 16 ) NULL AFTER  `entPhysicalHardwareRev` ,ADD  `entPhysicalSoftwareRev` VARCHAR( 16 ) NULL AFTER  `entPhysicalFirmwareRev` ,ADD  `entPhysicalAlias` VARCHAR( 32 ) NULL AFTER  `entPhysicalSoftwareRev` ,ADD  `entPhysicalAssetID` VARCHAR( 32 ) NULL AFTER  `entPhysicalAlias` ,ADD  `entPhysicalIsFRU` VARCHAR( 8 ) NULL AFTER  `entPhysicalAssetID`;
ALTER TABLE `devices` ADD `last_discovered` timestamp NULL DEFAULT NULL AFTER `last_polled`;
ALTER TABLE `devices` CHANGE  `lastchange`  `uptime` BIGINT NULL DEFAULT NULL;
ALTER TABLE `storage` ADD `hrStorageType` VARCHAR( 32 ) NULL DEFAULT NULL AFTER `hrStorageIndex`;
ALTER TABLE `devices` MODIFY `type` varchar(8) DEFAULT 'unknown';
ALTER TABLE `devices` CHANGE `os` `os` VARCHAR( 32 ) NULL DEFAULT NULL;
ALTER TABLE `temperature` ADD `temp_precision` INT(11) NULL DEFAULT '1';
UPDATE temperature SET temp_precision=10 WHERE temp_tenths=1;
ALTER TABLE `temperature` DROP `temp_tenths`;
CREATE TABLE IF NOT EXISTS `dbSchema` ( `revision` int(11) NOT NULL default '0', PRIMARY KEY (`revision`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;
ALTER TABLE `storage` ADD `storage_perc_warn` INT(11) NULL DEFAULT '60';
CREATE TABLE `voltage` (
  `volt_id` int(11) NOT NULL auto_increment,
  `volt_host` int(11) NOT NULL default '0',
  `volt_oid` varchar(64) NOT NULL,
  `volt_descr` varchar(32) NOT NULL default '',
  `volt_precision` int(11) NOT NULL default '1',
  `volt_current` int(11) NOT NULL default '0',
  `volt_limit` int(11) NOT NULL default '60',
  PRIMARY KEY  (`volt_id`),
  KEY `volt_host` (`volt_host`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
CREATE TABLE `fanspeed` (
  `fan_id` int(11) NOT NULL auto_increment,
  `fan_host` int(11) NOT NULL default '0',
  `fan_oid` varchar(64) NOT NULL,
  `fan_descr` varchar(32) NOT NULL default '',
  `fan_precision` int(11) NOT NULL default '1',
  `fan_current` int(11) NOT NULL default '0',
  `fan_limit` int(11) NOT NULL default '60',
  PRIMARY KEY  (`fan_id`),
  KEY `fan_host` (`fan_host`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
