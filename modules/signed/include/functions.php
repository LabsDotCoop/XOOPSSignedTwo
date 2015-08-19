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
 * @subpackage		functions
 * @description		Digital Signature Generation & API Services (Psuedo-legal correct binding measure)
 * @link			Farming Digital Fingerprint Signatures: https://signed.ringwould.com.au
 * @link			Heavy Hash-info Digital Fingerprint Signature: http://signed.hempembassy.net
 * @link			XOOPS SVN: https://sourceforge.net/p/xoops/svn/HEAD/tree/XoopsModules/signed/
 * @see				Release Article: http://cipher.labs.coop/portfolio/signed-identification-validations-and-signer-for-xoops/
 * @filesource
 *
 */


	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'xmlarray.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'xmlwrapper.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'cache' . _DS_ . 'signedcache.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedlists.php';

	/**
	 * Loads signed Objectivity
	 */
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedapi.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedarrays.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedcanvas.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedciphers.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedlists.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedlogger.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedpackages.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedprocesses.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedprompts.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedsecurity.php';
	require_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'signedstorage.php';
	
	
	/**
	 * gets Instances of signed Objectivity
	 */
	global $security, $io;
	$GLOBALS['security'] = signedSecurity::getInstance();
	$GLOBALS['io'] = signedStorage::getInstance(_SIGNED_STORAGE);
	
	/**
	 * signed language loader wrapper
	 *
	 * Temporay solution, not encouraged to use
	 *
	 * @param   string  $name       Name of language file to be loaded, without extension
	 * @param   string  $domain     Module dirname; global language file will be loaded if $domain is set to 'global' or not specified
	 * @param   string  $language   Language to be loaded, current language content will be loaded if not specified
	 * @return  boolean
	 * @todo    expand domain to multiple categories, e.g. module:system, framework:filter, etc.
	 *
	 */
	function signed_loadLanguage($name, $language = null)
	{
		/**
		 * We must check later for an empty value. As signed_getOption could be empty
		 */
		$name = trim($name);
		if (empty($name)) {
			return false;
		} 
		$language = (empty($language) ? constant('_SIGNED_CONFIG_LANGUAGE') : $language);
		$path = _PATH_ROOT . _DS_ . 'language';
		if (!file_exists($fileinc = $path . _DS_ . $language . _DS_ . $name . ".php" )) {
			if (!file_exists( $fileinc = $path . _DS_ . 'english' . _DS_ . $name . ".php" )) {
				return false;
			}
		}
		$ret = include_once $fileinc;
		return $ret;
	}
	
	/**
	 * signed_getMailer()
	 *
	 * @return
	 */
	function &signed_getSMSer()
	{
		static $smser;
		if (is_object($smser)) {
			return $smser;
		}
		include_once _PATH_ROOT . '/class/signedmobile.php';
		if (file_exists($file = _PATH_ROOT . '/language/' . constant('_SIGNED_CONFIG_LANGUAGE') . '/signedmobilelocal.php')) {
			include_once $file;
		} else if (file_exists($file = _PATH_ROOT . '/language/english/signedmobilelocal.php')) {
			include_once $file;
		}
		unset($smser);
		if (class_exists('signedMobileLocal')) {
			$smser = new signedMobileLocal();
		} else {
			$smser = new signedMobile();
		}
		return $smser;
	}
	
	
	/**
	 * signed_getMailer()
	 *
	 * @return
	 */
	function &signed_getMailer()
	{
		static $mailer;
		global $configurations;
		if (is_object($mailer)) {
			return $mailer;
		}
		include_once _PATH_ROOT . '/class/signedmailer.php';
		if (file_exists($file = _PATH_ROOT . '/language/' . constant('_SIGNED_CONFIG_LANGUAGE') . '/signedmailerlocal.php')) {
			include_once $file;
		} else if (file_exists($file = _PATH_ROOT . '/language/english/signedmailerlocal.php')) {
			include_once $file;
		}
		unset($mailer);
		if (class_exists('signedMailerLocal')) {
			$mailer = new signedMailerLocal();
		} else {
			$mailer = new signedMailer();
		}
		return $mailer;
	}
	
	/**
	 * Function to get the base domain name from a URL.
	 * credit for this function should goto Phosphorus and Lime, it is released under LGPL.
	 *
	 * @param string $url the URL to be stripped.
	 * @return string
	 * @fixed
	 */
	function signed_getBaseDomain($url, $debug = 0)
	{
		$base_domain = '';
		$url = strtolower($url);
		
		if ($G_TLD = signedCache::read('dms_realms_list'))
		{
			$G_TLD = array_keys(unserialize(signedArrays::getFileContents("http://strata.labs.coop/v1/strata/serial.api")));
			if (empty($G_TLD))
				$G_TLD = array_keys(unserialize(signedArrays::getFileContents("https://strata.ringwould.com.au/v1/strata/serial.api")));
				if (empty($G_TLD))
					$G_TLD = array_keys(unserialize(signedArrays::getFileContents("http://strata.ringwould.com.au/v1/strata/serial.api")));
			signedCache::write('dms_realms_list', $G_TLD, 3600*24*mt(3.75,11));
		}
		if ($C_TLD = signedCache::read('fallout_realms_list'))
		{
				$C_TLD = array_keys(unserialize(signedArrays::getFileContents("http://strata.labs.coop/v1/fallout/serial.api")));
				if (empty($C_TLD))
					$C_TLD = array_keys(unserialize(signedArrays::getFileContents("https://strata.ringwould.com.au/v1/fallout/serial.api")));
					if (empty($C_TLD))
						$C_TLD = array_keys(unserialize(signedArrays::getFileContents("http://strata.ringwould.com.au/v1/fallout/serial.api")));
				signedCache::read('fallout_realms_list', $C_TLD, 3600*24*mt(3.75,11));
		}
	
		// get domain
		if (!$full_domain = signed_getUrlDomain($url)) {
			return $base_domain;
		}
	
		// break up domain, reverse
		$DOMAIN = explode('.', $full_domain);
		if ($debug) {
			//print_r($DOMAIN);
		}
		$DOMAIN = array_reverse($DOMAIN);
		if ($debug) {
			//print_r($DOMAIN);
		}
		// first check for ip address
		if (count($DOMAIN) == 4 && is_numeric($DOMAIN[0]) && is_numeric($DOMAIN[3])) {
			return $full_domain;
		}
	
		// if only 2 domain parts, that must be our domain
		if (count($DOMAIN) <= 2) {
			return $full_domain;
		}
	
		/*
		 finally, with 3+ domain parts: obviously D0 is tld now,
		if D0 = ctld and D1 = gtld, we might have something like com.uk so,
		if D0 = ctld && D1 = gtld && D2 != 'www', domain = D2.D1.D0 else if D0 = ctld && D1 = gtld && D2 == 'www',
		domain = D1.D0 else domain = D1.D0 - these rules are simplified below.
		*/
		if (in_array($DOMAIN[0], $C_TLD) && in_array($DOMAIN[1], $G_TLD) && $DOMAIN[2] != 'www') {
			$full_domain = $DOMAIN[2] . '.' . $DOMAIN[1] . '.' . $DOMAIN[0];
		} else {
			$full_domain = $DOMAIN[1] . '.' . $DOMAIN[0];
		}
		// did we succeed?
		return $full_domain;
	}
	
	function signed_getUrlDomain($url)
	{
		$domain = '';
		$_URL = parse_url($url);
	
		if (!empty($_URL) || !empty($_URL['host'])) {
			$domain = $_URL['host'];
		}
		return $domain;
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function makeAlphaCount($num = 0)
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedArrays::getInstance()->makeAlphaCount($num);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @param unknown $array
	 */
	function signed_trimExplode($array = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedArrays::getInstance()->trimExplode($array);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function collapseArray($array = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedArrays::getInstance()->collapseArray($array);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function returnKeyed($key = '', $function = "")
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedArrays::getInstance()->returnKeyed($key, $function);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function returnKey($key = '', $function = "")
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedArrays::getInstance()->returnKey($key, $function);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @param string $src
	 * @param string $content
	 * @param string $name
	 * @param unknown $attributes
	 */
	function addScript($src = '', $content = '', $name = '', $attributes = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCanvas::getInstance()->addScript($src, $content, $name, $attributes);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @param string $src
	 * @param string $content
	 * @param string $name
	 * @param unknown $attributes
	 */
	function addStylesheet($src = '', $content = '', $name = '', $attributes = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCanvas::getInstance()->addStylesheet($src, $content, $name, $attributes);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @param unknown $rel
	 * @param string $href
	 * @param unknown $attributes
	 * @param string $name
	 */
	function addLink($rel, $href = '', $attributes = array(), $name = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCanvas::getInstance()->addLink($rel, $href, $attributes, $name);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_goBufferPrompt($prompt = '', $step = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCanvas::getInstance()->goBufferPrompt($prompt, $step);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getSignature($serial = '', $code = '', $certificate = '', $name = '', $email = '', $date = '', $needsverified = false)
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedAPI::getInstance()->getSignature($serial, $code, $certificate, $name, $email, $date, $needsverified);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_verifyAPIFields($type = 'sign', $data = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedAPI::getInstance()->verifyAPIFields($type, $data);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_saveEnsigmentPackage($serial = '', $code = '', $certificate = '', $package = array(), $verify = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->saveEnsigmentPackage($serial, $code, $certificate, $package, $verify);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_saveEditedEnsigmentPackage($serial = '', $package = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->saveEditedEnsigmentPackage($serial, $package);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_lodgeCallbackSessions($serial = '', $type = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->lodgeCallbackSessions($serial, $type);
	}
		
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_sendSignatureEmail($serial = '') 
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->sendSignatureEmail($serial);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_sendSMSMobileValidations($mobiles = array(), $serial = '', $package = array()) 
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->sendSMSMobileValidations($mobiles, $serial, $package);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_sendEmailAddressValidations($emails = array(), $serial = '', $package = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->sendEmailAddressValidations($emails, $serial, $package);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getVerifiableMobiles($package = array(), $validation = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->getVerifiableMobiles($package, $validation);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getVerifiableEmails($package = array(), $validation = array()){
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->getVerifiableEmails($package, $validation);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_extractField($package = array(), $field = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->extractField($package, $field);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_sealPackage($type = 'personal') {
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->sealPackage($type);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_sealEditedPackage($type = 'personal') {
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPackages::getInstance()->sealEditedPackage($type);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_checkBans()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedSecurity::getInstance()->checkBans();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_checkForBans($value = '', $type = 'email')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedSecurity::getInstance()->checkForBans($value, $type);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getBannedHostnames()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedSecurity::getInstance()->getBannedHostnames();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getBannedIP()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedSecurity::getInstance()->getBannedIP();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function getHostCode($host = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedSecurity::getInstance()->getHostCode($host);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function getSignatureCode($mode, $package = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedSecurity::getInstance()->getSignatureCode($mode, $package);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_doesRequestMakeExpiry($requests)
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPrompts::getInstance()->doesRequestMakeExpiry($requests);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getStepsLeftPrompt($prompt = '', $step = '') {
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPrompts::getInstance()->getStepsLeftPrompt($prompt, $step);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getRequestStepsInPrompt($prompt = '', $serial = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPrompts::getInstance()->getRequestStepsInPrompt($prompt, $serial);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getStepsInPrompt($prompt = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPrompts::getInstance()->getStepsInPrompt($prompt);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getNextStepInPrompt($prompt = '', $steps = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPrompts::getInstance()->getNextStepInPrompt($prompt, $steps);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_goVerifyAndPackagePrompt($prompt = '', $step = '', $mode = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPrompts::getInstance()->goVerifyAndPackagePrompt($prompt, $step, $mode);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_goReset()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPrompts::getInstance()->goReset();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getFormHandler($form = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPrompts::getInstance()->getFormHandler($form);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_goVerifyForm($mode = '', $form = '', $step = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedPrompts::getInstance()->goVerifyForm($mode, $form, $step);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @param string $data
	 * @param string $method
	 */
	function fingerprint($data = '', $method = 'md5')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCiphers::getInstance()->fingerprint($data, $method);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_extractServiceKey($code = '', $certicate = '', $verificationkey = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCiphers::getInstance()->extractServiceKey($code, $certicate, $verificationkey);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_generateSignatureKey($signature = array(), $data = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCiphers::getInstance()->generateSignatureKey($signature, $data);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_shortenURL($url = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCiphers::getInstance()->shortenURL($url);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getURL($hash = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCiphers::getInstance()->getURL($hash);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * Version: LABSCOOP-DS v1.0.3
	 */
	function getSignatureCertificate($mode, $package = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCiphers::getInstance()->getSignatureCertificate($mode, $package);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function getHash($data = '', $type = 'md5', $length = 44, $seed = -1)
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCiphers::getInstance()->getHash($data, $type, $length, $seed);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getSalt($keys = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedCiphers::getInstance()->getSalt($keys);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getLanguages()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getLanguages();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getAPICalls()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getAPICalls();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getEmailTemplateTypes()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getEmailTemplateTypes();
	}
		
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getSignatures()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getSignatures();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getSites()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getSites();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getCcEmails()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getCcEmails();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getBccEmails()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getBccEmails();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getSaltsArray($keys = array())
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getSaltsArray($keys);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getFieldnamesArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getFieldnamesArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 */
	function signed_getLanguageFiles($path)
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getLanguageFiles($path);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getLanguageArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getLanguageArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getProcessesArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getProcessesArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getFieldDescriptions()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getFieldDescriptions();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getEnumeratorsArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getEnumeratorsArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getClassArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getClassArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getRequestPromptsArray($serial = '')
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getRequestPromptsArray($serial);
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getPromptsArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getPromptsArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getProvidedArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getProvidedArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getIdentificationsArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getIdentificationsArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getRequestStatesArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getRequestStatesArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getDimensionsArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getDimensionsArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getFieldsArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getFieldsArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getValidationsArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getValidationsArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @return array
	 */
	function signed_getLanguageFilesArray()
	{
		trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
		return signedProcesses::getInstance()->getLanguageFilesArray();
	}
	
	/**
	 * @deprecated	Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 *
	 * Get client IP
	 *
	 * Adapted from PMA_getIp() [phpmyadmin project]
	 *
	 * @param bool $asString requiring integer or dotted string
	 * @return mixed string or integer value for the IP
	 */
	if (!function_exists('getIP'))
	{
		function getIP($asString = false)
		{
			trigger_error("Deprecated Function Used: " . __FUNCTION__ . " in " . basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . basename(__FILE__) . ' on line ' . __LINE__ . ' :: Trace ~ '); echo "<pre>"; //print_r(debug_backtrace()); echo "</pre>";
			return signedSecurity::getInstance()->getIP($asString);
		}
	}
	
?>