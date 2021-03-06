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
 * @subpackage		module
 * @description		Digital Signature Generation & API Services (Psuedo-legal correct binding measure)
 * @link			Farming Digital Fingerprint Signatures: https://signed.ringwould.com.au
 * @link			Heavy Hash-info Digital Fingerprint Signature: http://signed.hempembassy.net
 * @link			XOOPS SVN: https://sourceforge.net/p/xoops/svn/HEAD/tree/XoopsModules/signed/
 * @see				Release Article: http://cipher.labs.coop/portfolio/signed-identification-validations-and-signer-for-xoops/
 * @filesource
 *
 */
	
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'mainfile.php');
	
	// Shortcut Constants
	$GLOBALS['errors'] = array();
	define('_DS_', DIRECTORY_SEPARATOR);
	define('_SIGNED_VALIDATION_FUNCTION', 'goValidateField');
	define('_PHP_TIMEZONE', $_SESSION["signed"]['configurations']['php_timezone']);
	if (!defined('_YEARS_NUMBEROF'))
		define('_YEARS_NUMBEROF', $_SESSION["signed"]['configurations']['years_numberof']);
	
	// Identification Watermarking Settings
	define('_SIGNED_WATERMARK_GIF', $_SESSION["signed"]['configurations']['watermark_gif']);
	define('_SIGNED_WATERMARK_WEIGHT', $_SESSION["signed"]['configurations']['watermark_weight']);
	define('_SIGNED_WATERMARK_NUMBERTOSCALE', $_SESSION["signed"]['configurations']['watermark_scale']);
	define('_SIGNED_WATERMARK_MINIMUM_OPACITY', $_SESSION["signed"]['configurations']['watermark_min_opacity']);
	define('_SIGNED_WATERMARK_MAXIMUM_OPACITY', $_SESSION["signed"]['configurations']['watermark_min_opacity']);
	
	// Error logging and Reporting
	define('_SIGNED_ERRORS_LOGGED', $_SESSION["signed"]['configurations']['errors_logged']);
	define('_SIGNED_ERRORS_DISPLAYED', $_SESSION["signed"]['configurations']['errors_displayed']);
	define('_SIGNED_ERRORS_REPORTING', $_SESSION["signed"]['configurations']['errors_reporting']);
	
	//Service Discoverability
	define('_SIGNED_DISCOVERABLE', $_SESSION["signed"]['configurations']['discoverable']);
	
	// Storage Mechanisms
	define('_SIGNED_STORAGE', $_SESSION["signed"]['configurations']['storage']);
	define('_SIGNED_LOGS_STORAGE', $_SESSION["signed"]['configurations']['storage_logs']);
	define('_SIGNED_SIGNED_STORAGE', $_SESSION["signed"]['configurations']['storage_signed']);
	define('_SIGNED_RESOURCES_STORAGE', $_SESSION["signed"]['configurations']['storage_resources']);
	define('_SIGNED_URLS_STORAGE', $_SESSION["signed"]['configurations']['storage_urls']);
	
	// Profile Image Sizes
	define('_SIGNED_LOGO_WIDTH', $_SESSION["signed"]['configurations']['logo_width']);
	define('_SIGNED_LOGO_HEIGHT', $_SESSION["signed"]['configurations']['logo_height']);
	define('_SIGNED_PHOTO_WIDTH', $_SESSION["signed"]['configurations']['photo_width']);
	define('_SIGNED_PHOTO_HEIGHT', $_SESSION["signed"]['configurations']['photo_height']);
	
	// API & API FUNCTION ENABLE/DISABLE
	define('_SIGNED_API_FORMAT', $_SESSION["signed"]['configurations']['api_format']);
	define('_SIGNED_API_ENABLED', $_SESSION["signed"]['configurations']['api_enabled']);
	define('_SIGNED_API_FUNCTION_SIGN', $_SESSION["signed"]['configurations']['api_sign']);
	define('_SIGNED_API_FUNCTION_VERIFY', $_SESSION["signed"]['configurations']['api_verify']);
	define('_SIGNED_API_FUNCTION_VERIFICATION', $_SESSION["signed"]['configurations']['api_verification']);
	define('_SIGNED_API_FUNCTION_SITES', $_SESSION["signed"]['configurations']['api_sites']);
	define('_SIGNED_API_FUNCTION_LANGUAGES', $_SESSION["signed"]['configurations']['api_languages']);
	define('_SIGNED_API_FUNCTION_CLASSES', $_SESSION["signed"]['configurations']['api_classes']);
	define('_SIGNED_API_FUNCTION_DESCRIPTIONS', $_SESSION["signed"]['configurations']['api_descriptions']);
	define('_SIGNED_API_FUNCTION_ENUMERATORS', $_SESSION["signed"]['configurations']['api_enumerators']);
	define('_SIGNED_API_FUNCTION_FIELDS', $_SESSION["signed"]['configurations']['api_fields']);
	define('_SIGNED_API_FUNCTION_FIELDTYPES', $_SESSION["signed"]['configurations']['api_fieldtypes']);
	define('_SIGNED_API_FUNCTION_IDENTIFICATIONS', $_SESSION["signed"]['configurations']['api_identifications']);
	define('_SIGNED_API_FUNCTION_PROMPTS', $_SESSION["signed"]['configurations']['api_prompts']);
	define('_SIGNED_API_FUNCTION_PROVIDERS', $_SESSION["signed"]['configurations']['api_providers']);
	define('_SIGNED_API_FUNCTION_SIGNATURES', $_SESSION["signed"]['configurations']['api_signatures']);
	define('_SIGNED_API_FUNCTION_VALIDATIONS', $_SESSION["signed"]['configurations']['api_validations']);
	define('_SIGNED_API_FUNCTION_PROCESSES', $_SESSION["signed"]['configurations']['api_processes']);
	define('_SIGNED_API_FUNCTION_LANGUAGE', $_SESSION["signed"]['configurations']['api_language']);
	define('_SIGNED_API_FUNCTION_STATES', $_SESSION["signed"]['configurations']['api_states']);
	define('_SIGNED_API_FUNCTION_REQUEST', $_SESSION["signed"]['configurations']['api_request']);
	define('_SIGNED_API_FUNCTION_BANNED', $_SESSION["signed"]['configurations']['api_banned']);
	
	// Banning Constants
	define('_SIGNED_BANNING_COOKIE', $_SESSION["signed"]['configurations']['banning_cookie']);
	
	//Versioning
	define('_SIGNED_VERSION', '2.2 RC');
	define('_SIGNED_SYSTEM_TYPE', 'Digital-Signature-Signed2');	
	define('_SIGNED_SYSTEM_KEY', 'signed-self-labs-coop');
	define('_SIGNED_API_VERSION', '1.1');
	
	// Path Constants
	define('_PATH_DATA', XOOPS_VAR_PATH . _DS_ . 'signed');
	define('_PATH_LIBB', XOOPS_PATH . _DS_ . 'modules' . _DS_ . 'signed');
	define('_PATH_LOGS', _PATH_DATA . _DS_ . 'logs');
	define('_PATH_LOGS_DELIVERY', _PATH_LOGS . _DS_ . 'delivery');
	define('_PATH_LOGS_EMAILS', _PATH_LOGS . _DS_ . 'emails');
	define('_PATH_LOGS_EMAILS_REMINDERS', _PATH_LOGS_EMAILS . _DS_ . 'reminders');
	define('_PATH_LOGS_EMAILS_NOTICES', _PATH_LOGS_EMAILS . _DS_ . 'notices');
	define('_PATH_LOGS_EMAILS_DELIVERY', _PATH_LOGS_EMAILS . _DS_ . 'delivery');
	define('_PATH_LOGS_EMAILS_VERIFY', _PATH_LOGS_EMAILS . _DS_ . 'vertify');
	define('_PATH_LOGS_ERRORS', _PATH_LOGS . _DS_ . 'errors');
	define('_PATH_LOGS_EXECUTION', _PATH_LOGS . _DS_ . 'execution');
	define('_PATH_LOGS_POLLING', _PATH_LOGS . _DS_ . 'polling');
	define('_PATH_URLS', _PATH_DATA . _DS_ . 'urls');
	define('_PATH_CACHE', XOOPS_VAR_PATH . _DS_ . 'caches' . _DS_ . 'signed');
	define('_PATH_CALENDAR', _PATH_DATA . _DS_ . 'calendar');
	define('_PATH_CALENDAR_EXPIRES', _PATH_CALENDAR . _DS_ . 'expires');
	define('_PATH_CALENDAR_EXPIRY', _PATH_CALENDAR . _DS_ . 'expiry');
	define('_PATH_CALENDAR_GENERATED', _PATH_CALENDAR . _DS_ . 'generated');
	define('_PATH_PATHWAYS', _PATH_DATA . _DS_ . 'pathways');
	define('_PATH_PATHWAYS_CERTIFICATES', _PATH_PATHWAYS . _DS_ . 'certificate');
	define('_PATH_PATHWAYS_CODES', _PATH_PATHWAYS . _DS_ . 'codes');
	define('_PATH_PATHWAYS_SIGNED', _PATH_PATHWAYS . _DS_ . 'signed');
	define('_PATH_PATHWAYS_REQUEST', _PATH_PATHWAYS . _DS_ . 'request');
	define('_PATH_PATHWAYS_EMAILS', _PATH_PATHWAYS . _DS_ . 'emails');
	define('_PATH_PATHWAYS_NAMES', _PATH_PATHWAYS . _DS_ . 'names');
	define('_PATH_PATHWAYS_DATES', _PATH_PATHWAYS . _DS_ . 'dates');
	define('_PATH_PATHWAYS_SERIALS', _PATH_PATHWAYS . _DS_ . 'serials');
	define('_PATH_REPO', _PATH_DATA . _DS_ . 'respository');
	define('_PATH_REPO_CERTIFICATES', _PATH_REPO . _DS_ . 'certificates');
	define('_PATH_REPO_SIGNATURES', _PATH_REPO . _DS_ . 'signatures');
	define('_PATH_REPO_SIGNED', _PATH_REPO . _DS_ . 'signed');
	define('_PATH_REPO_VALIDATION', _PATH_REPO . _DS_ . 'validation');
	define('_PATH_UPLOADS', _PATH_DATA . _DS_ . 'uploads');
	define('_PATH_PROCESSES', _PATH_LIBB . _DS_ . 'processes');
	define('_PATH_TEMPLATES', XOOPS_ROOT_PATH . _DS_ . 'modules' . _DS_ . 'signed' . _DS_ . 'templates');
	define('_PATH_ROOT', XOOPS_ROOT_PATH . _DS_ . 'modules' . _DS_ . 'signed');
	
	// URL Constants
	define('_URL_PROT', XOOPS_PROT);
	if ($_SESSION["signed"]['configurations']['htaccess'] == false)
	{
		define('_URL_ROOT', XOOPS_URL . '/modules/signed');
		define('_URL_API', _URL_ROOT . '/api');
		define('_URL_CSS', _URL_ROOT . '/css');
		define('_URL_JS', _URL_ROOT . '/js');
		define('_URL_FONTS', _URL_ROOT . '/fonts');
		define('_URL_URLS', _URL_ROOT . '/go');
		define('_URL_IMAGES', _URL_ROOT . '/image');
		define('_URL_ICONS', _URL_IMAGES . '/icons');
	} else {
		define('_URL_ROOT', XOOPS_URL . '/' . $_SESSION["signed"]['configurations']['htaccess_path']);
		define('_URL_API', $_SESSION["signed"]['configurations']['htaccess_url_api']);
		define('_URL_CSS',  XOOPS_URL . '/modules/signed/css');
		define('_URL_JS',  XOOPS_URL . '/modules/signed/js');
		define('_URL_FONTS',  XOOPS_URL . '/modules/signed/fonts');
		define('_URL_URLS',  XOOPS_URL . '/modules/signed/go');
		define('_URL_IMAGES',  XOOPS_URL . '/modules/signed/image');
		define('_URL_ICONS', _URL_IMAGES . '/icons');
	}
	
?>	
