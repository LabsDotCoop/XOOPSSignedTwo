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
 * @subpackage		administration
 * @description		Digital Signature Generation & API Services (Psuedo-legal correct binding measure)
 * @link			Farming Digital Fingerprint Signatures: https://signed.ringwould.com.au
 * @link			Heavy Hash-info Digital Fingerprint Signature: http://signed.hempembassy.net
 * @link			XOOPS SVN: https://sourceforge.net/p/xoops/svn/HEAD/tree/XoopsModules/signed/
 * @see				Release Article: http://cipher.labs.coop/portfolio/signed-identification-validations-and-signer-for-xoops/
 * @filesource
 *
 */
	
	$path = dirname(dirname(dirname(dirname(__FILE__))));
	include_once $path . '/mainfile.php';
	
	$dirname         = basename(dirname(dirname(__FILE__)));
	$module_handler  = xoops_gethandler('module');
	$module          = $module_handler->getByDirname($dirname);
	$pathIcon32      = $module->getInfo('icons32');
	$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
	$pathLanguage    = $path . $pathModuleAdmin;
	
	if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
	    $fileinc = $pathLanguage . '/language/english/main.php';
	}
	
	include_once $fileinc;
	
	$adminmenu = array();
	
	$i = 1;
	$adminmenu[$i]['title'] = _SIGNED_MI_INDEX;
	$adminmenu[$i]['link'] = "admin/admin.php";
	$adminmenu[$i]['icon']  = $pathIcon32.'/security.png' ;
	++$i;
	$adminmenu[$i]['title'] = _SIGNED_MI_SIGNATURES;
	$adminmenu[$i]['link'] = "admin/signatures.php";
	$adminmenu[$i]['icon']  = $pathIcon32.'/identity.png' ;
	++$i;
	$adminmenu[$i]['title'] = _SIGNED_MI_EVENTS;
	$adminmenu[$i]['link'] = "admin/events.php";
	$adminmenu[$i]['icon']  = $pathIcon32.'/event.png' ;
	++$i;
	$adminmenu[$i]['title'] = _SIGNED_MI_ABOUT;
	$adminmenu[$i]['link']  = 'admin/about.php';
	$adminmenu[$i]['icon']  = $pathIcon32.'/about.png';

?>