<?php
/**
 * Chronolabs Digital Signature Generation & API Services (Psuedo-legal correct binding measure)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license			General Software Licence (http://labs.coop/briefs/legal/general-software-license/10,3.html)
 * @license			End User License (http://labs.coop/briefs/legal/end-user-license/11,3.html)
 * @license			Privacy and Mooching Policy (http://labs.coop/briefs/legal/privacy-and-mooching-policy/22,3.html)
 * @license			General Public Licence 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @category		signed
 * @since			2.1.9
 * @version			2.2.0
 * @author			Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
 * @author          Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
 * @subpackage		class
 * @description		Digital Signature Generation & API Services (Psuedo-legal correct binding measure)
 * @link			Farming Digital Fingerprint Signatures: https://signed.ringwould.com.au
 * @link			Heavy Hash-info Digital Fingerprint Signature: http://signed.hempembassy.net
 * @link			XOOPS SVN: https://sourceforge.net/p/xoops/svn/HEAD/tree/XoopsModules/signed/
 * @see				Release Article: http://cipher.labs.coop/portfolio/signed-identification-validations-and-signer-for-xoops/
 * @filesource
 *
 */

function xoops_module_update_signed(&$module, $oldversion = null)
{
	
	if ($oldversion<=220)
		$GLOBALS['xoopsDB']->queryF("CREATE TABLE `" . $GLOBALS['xoopsDB']->prefix('signed_keiyes') . "` (
									  `keiyeid`      mediumint(16) unsigned  NOT NULL auto_increment,
									  `typal`   	enum('serial', 'xml', 'json', 'raw') NOT NULL default 'raw',
									  `path`      	varchar(200)            NOT NULL default '',
									  `filename`   	varchar(200)            NOT NULL default '',
									  `seal-md5`    varchar(32)             NOT NULL default '',
									  `open-md5`    varchar(32)             NOT NULL default '',
									  `algorithm`   varchar(48)             NOT NULL default '',
									  `cipher`     	varchar(48)             NOT NULL default '',
									  `key`     	tinytext,
									  `last-algorithm`   varchar(48)             NOT NULL default '',
									  `last-cipher`     	varchar(48)             NOT NULL default '',
									  `last-key`     	tinytext,
									  `bytes`   	int(24) unsigned     	NOT NULL default '0',
									  `created`   	int(13) unsigned     	NOT NULL default '0',
									  `accessed`   	int(13) unsigned     	NOT NULL default '0',
									  PRIMARY KEY  (`keiyeid`),
									  KEY `indexer` (`path`(14), `filename`(14), `seal-md5`(12))
									) ENGINE=INNODB;");

	return true;
}