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
 * @subpackage		cache
 * @description		Digital Signature Generation & API Services (Psuedo-legal correct binding measure)
 * @link			Farming Digital Fingerprint Signatures: https://signed.ringwould.com.au
 * @link			Heavy Hash-info Digital Fingerprint Signature: http://signed.hempembassy.net
 * @link			XOOPS SVN: https://sourceforge.net/p/xoops/svn/HEAD/tree/XoopsModules/signed/
 * @see				Release Article: http://cipher.labs.coop/portfolio/signed-identification-validations-and-signer-for-xoops/
 * @filesource
 *
 */

defined('_PATH_ROOT') or die('Restricted access');

/**
 * File Storage engine for cache
 *
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *                                       1785 E. Sahara Avenue, Suite 490-204
 *                                       Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package cake
 * @subpackage cake.cake.libs.cache
 * @since CakePHP(tm) v 1.2.0.4933
 * @version $Revision: 10686 $
 * @modifiedby $LastChangedBy: beckmi $
 * @lastmodified $Date: 2013-01-06 14:07:24 -0500 (Sun, 06 Jan 2013) $
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * File Storage engine for cache
 *
 * @todo use the File and Folder classes (if it's not a too big performance hit)
 * @package cake
 * @subpackage cake.cake.libs.cache
 */
class signedCacheFile extends signedCacheEngine
{
    /**
     * Instance of File class
     *
     * @var object
     * @access private
     */
    var $file = null;

    /**
     * settings
     *                path = absolute path to cache directory, default => CACHE
     *                prefix = string prefix for filename, default => signed_
     *                lock = enable file locking on write, default => false
     *                serialize = serialize the data, default => false
     *
     * @var array
     * @see	 CacheEngine::__defaults
     * @access public
     */
    var $settings = array();

    /**
     * Set to true if FileEngine::init(); and FileEngine::active(); do not fail.
     *
     * @var boolean
     * @access private
     */
    var $active = false;

    /**
     * True unless FileEngine::active(); fails
     *
     * @var boolean
     * @access private
     */
    var $init = true;

    /**
     * Initialize the Cache Engine
     *
     * Called automatically by the cache frontend
     * To reinitialize the settings call Cache::engine('EngineName', [optional] settings = array());
     *
     * @param array $setting array of setting for the engine
     * @return boolean True if the engine has been successfully initialized, false if not
     * @access public
     */
    function init($settings = array())
    {
        parent::init($settings);
        $defaults = array('path' => _PATH_CACHE , 'extension' => '.php' , 'prefix' => 'signed_' , 'lock' => false , 'serialize' => false , 'duration' => 31556926);
        $this->settings = array_merge($defaults, $this->settings);
        if (!isset($this->file)) {
            include_once _PATH_ROOT . _DS_ . 'class' . _DS_ . 'file' . _DS_ . 'signedfile.php';
            $this->file = signedFile::getHandler('file', $this->settings['path'] . '/index.html', true);
        }
        $this->settings['path'] = $this->file->folder->cd($this->settings['path']);
        if (empty($this->settings['path'])) {
            return false;
        }
        return $this->active();
    }

    /**
     * Garbage collection. Permanently remove all expired and deleted data
     *
     * @return boolean True if garbage collection was succesful, false on failure
     * @access public
     */
    function gc()
    {
        return $this->clear(true);
    }

    /**
     * Write data for key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $data Data to be cached
     * @param mixed $duration How long to cache the data, in seconds
     * @return boolean True if the data was succesfully cached, false on failure
     * @access public
     */
    function write($key, $data = null, $duration = null)
    {
        if (!isset($data) || ! $this->init) {
            return false;
        }

        if ($this->setKey($key) === false) {
            return false;
        }

        if ($duration == null) {
            $duration = $this->settings['duration'];
        }
        $windows = false;
        $lineBreak = "\n";

        if (substr(PHP_OS, 0, 3) == "WIN") {
            $lineBreak = "\r\n";
            $windows = true;
        }
        $expires = time() + $duration;
        if (!empty($this->settings['serialize'])) {
            if ($windows) {
                $data = str_replace('\\', '\\\\\\\\', serialize($data));
            } else {
                $data = serialize($data);
            }
            $contents = $expires . $lineBreak . $data . $lineBreak;
        } else {
            $contents = $expires . $lineBreak . "return " . var_export($data, true) . ";" . $lineBreak;
        }

        if ($this->settings['lock']) {
            $this->file->lock = true;
        }
        $success = $this->file->write($contents);
        if ($GLOBALS['logger'] = signedLogger::getInstance())
        	$GLOBALS['logger']->logBytes(strlen($contents), 'cache-written');
        $this->file->close();
        return $success;
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     * @return mixed The cached data, or false if the data doesn't exist, has expired, or if there was an error fetching it
     * @access public
     */
    function read($key)
    {
        if ($this->setKey($key) === false || ! $this->init) {
            return false;
        }
        if ($this->settings['lock']) {
            $this->file->lock = true;
        }
        $cachetime = $this->file->read(11);

        if ($cachetime !== false && intval($cachetime) < time()) {
            $this->file->close();
            $this->file->delete();
            return false;
        }

        $data = $this->file->read(true);
        if ($GLOBALS['logger'] = signedLogger::getInstance())
        	$GLOBALS['logger']->logBytes(strlen($data), 'cache-read');
        if (!empty($data) && !empty($this->settings['serialize'])) {
            $data = stripslashes($data);
            $data = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $data);
            $data = unserialize($data);
            if (is_array($data)) {
                signedLoad::load('signedUtility');
                $data = signedUtility::recursive('stripslashes', $data);
            }
        } else if ($data && empty($this->settings['serialize'])) {
            $data = eval($data);
        }
        $this->file->close();
        return $data;
    }

    /**
     * Delete a key from the cache
     *
     * @param string $key Identifier for the data
     * @return boolean True if the value was successfully deleted, false if it didn't exist or couldn't be removed
     * @access public
     */
    function delete($key)
    {
        if ($this->setKey($key) === false || ! $this->init) {
            return false;
        }
        return $this->file->delete();
    }

    /**
     * Delete all values from the cache
     *
     * @param boolean $check Optional - only delete expired cache items
     * @return boolean True if the cache was succesfully cleared, false otherwise
     * @access public
     */
    function clear($check = true)
    {
        if (!$this->init) {
            return false;
        }
        $dir = dir($this->settings['path']);
        if ($check) {
            $now = time();
            $threshold = $now - $this->settings['duration'];
        }
        while (($entry = $dir->read()) !== false) {
            if ($this->setKey(str_replace($this->settings['prefix'], '', $entry)) === false) {
                continue;
            }
            if ($check) {
                $mtime = $this->file->lastChange();

                if ($mtime === false || $mtime > $threshold) {
                    continue;
                }

                $expires = $this->file->read(11);
                $this->file->close();

                if ($expires > $now) {
                    continue;
                }
            }
            $this->file->delete();
        }
        $dir->close();
        return true;
    }

    /**
     * Get absolute file for a given key
     *
     * @param string $key The key
     * @return mixed Absolute cache file for the given key or false if erroneous
     * @access private
     */
    function setKey($key)
    {
        $this->file->folder->cd($this->settings['path']);
        $this->file->name = $this->settings['prefix'] . $key . $this->settings['extension'];
        $this->file->handle = null;
        $this->file->info = null;
        if (!$this->file->folder->inPath($this->file->pwd(), true)) {
            return false;
        }
    }
    /**
     * Determine is cache directory is writable
     *
     * @return boolean
     * @access private
     */
    function active()
    {
        if (!$this->active && $this->init && ! is_writable($this->settings['path'])) {
            $this->init = false;
            trigger_error(sprintf('%s is not writable', $this->settings['path']), E_USER_WARNING);
        } else {
            $this->active = true;
        }
        return true;
    }
}

?>