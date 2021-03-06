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
 * @subpackage		module
 * @description		Digital Signature Generation & API Services (Psuedo-legal correct binding measure)
 * @link			Farming Digital Fingerprint Signatures: https://signed.ringwould.com.au
 * @link			Heavy Hash-info Digital Fingerprint Signature: http://signed.hempembassy.net
 * @link			XOOPS SVN: https://sourceforge.net/p/xoops/svn/HEAD/tree/XoopsModules/signed/
 * @see				Release Article: http://cipher.labs.coop/portfolio/signed-identification-validations-and-signer-for-xoops/
 * @filesource
 *
 */

header('Content-type: application/json');
header('Origin: *');
header('Access-Control-Allow-Origin: *');
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'common.php';
ini_set("zlib.output_compression", 'Off');
ini_set("zlib.output_compression_level", -1);
$GLOBALS['xoopsLogger']->activated = false;
ob_end_flush();
set_time_limit(120);
if (signedCiphers::getInstance()->getHash(_URL_ROOT.date('Ymdh').session_id())==$_REQUEST['passkey']) {
	$ids = signedArrays::getInstance()->returnKeyed($_REQUEST['signature_mode'], 'getIdentificationsArray');
	$verify = signedArrays::getInstance()->returnKeyed($_REQUEST['signature_mode'], 'getValidationsArray');
	$points = 0;
	foreach($_REQUEST['identification'] as $key => $id) {
		if ($key = $id) {
			$points = $points + $ids[$key]['points'];
		}		
	}	
	if ($points>=$verify['required']['id-score']['fields'][0]){
		$values['innerhtml']['total-points'] = $points . '&nbsp;~&nbsp;<em>Required: '.$verify['required']['id-score']['fields'][0].'</em>';
		$values['disable']['submit-next'] = 'false';	
	} else {	
		$values['innerhtml']['total-points'] = $points . '&nbsp;~&nbsp;<em>Required: '.$verify['required']['id-score']['fields'][0].'</em>';
		$values['disable']['submit-next'] = 'true';	
	}
} else {
	$values['innerhtml']['total-points'] = "<font style='color: rgb(255,0,0);'>Pass Key has Timed-out please refresh the page!</font>";
	$values['disable']['submit-next'] = 'true';
}
print json_encode($values);
?>
