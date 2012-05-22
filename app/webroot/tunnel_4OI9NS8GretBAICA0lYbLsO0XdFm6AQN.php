<?php
/**************************************************************************
    Goincloud Http Tunnel
    Copyright 2011-2012, Goincloud Team
    http://goincloud.com 
***************************************************************************/

define('PASSWORD', '9T9YtvYdjQ0GF1Tc7pAaYUO4tWnlrg4g');
define('TUNNEL_VERSION', '0.0.9');
define('DS', '/');
define('DEBUG', false);

if (!isset($_REQUEST['password']) || !defined('PASSWORD') ||  strlen(PASSWORD) <= 0 
|| $_REQUEST['password'] != md5(PASSWORD)) {
    die('wrong_password');
}

date_default_timezone_set('Europe/London');

if (DEBUG) {
	error_reporting(1);	
	m_log($_REQUEST);
} else {
	error_reporting(0);
}

function m_log($msg) {
    if (!is_string($msg)) {
    	$msg = print_r($msg, true);
	}
	$today = date("d.m.Y");
	$filename = "log.txt";
	$fd = fopen($filename, "a");
	$str = "[" . date("d/m/Y h:i:s", mktime()) . "] " . $msg;
	fwrite($fd, $str . PHP_EOL);
	fclose($fd);
}

if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

if ($_REQUEST['version'] !== TUNNEL_VERSION && $_REQUEST['command'] !== 'autoupdate') {
	die('version_mismatch');
}

class M_Exception extends Exception {
    protected $params;
    public function __construct($message = null, $params = array())
    {
        $this->params = $params;
        parent::__construct($message, 0);
    }
    
    public function getParams() {
        return $this->params;
    }    
}

function m_check_connection($path) {
    m_readdir($path);
     if (! file_exists($path)) {
	return array('path' => $_SERVER['DOCUMENT_ROOT']);
     }
    return true;
}

function m_readdir($path) {
	$result = array();
    if (! file_exists($path)) {
		$path2 = $_SERVER['DOCUMENT_ROOT'];
		if (file_exists($path2)) {
			$path = $path2;
		} else {
			throw new M_Exception('CANT_FIND_FOLDER', array('path' => $path));
		}
	}
	if (!is_dir($path)) {
		throw new M_Exception('ITS_NOT_FOLDER', array('path' => $path));
	}		
	if ($handle = @opendir($path)) {
		while (false !== ($name = readdir($handle))) {
			if ($name == '.' || $name == '..') {
				continue;
			}
			$item = array(
				'name' => $name,
				'path' => $path == DS? DS.$name : $path.DS.$name,
			);
			$item['is_folder'] = is_dir($item['path']);
			$result[] = $item;
		}
		closedir($handle);
	} else {
		throw new M_Exception('CANT_OPEN_FOLDER', array('path' => $path));
	}
	return $result;
}

function m_file_get_contents($path) {
	if (! file_exists($path)) {
		throw new M_Exception('CANT_FIND_FILE', array('path' => $path));
	}
	
	if (!is_file($path)) {
		throw new M_Exception('ITS_NOT_FILE', array('path' => $path));
	}	
	$result = @file_get_contents($path);
 	if ($result === false){
		throw new M_Exception('CANT_OPEN_FILE', array('path' => $path));
	}
	return $result;
}

function m_rename($path, $new_path) {
	if (! file_exists($path)) {
		throw new M_Exception('CANT_FIND_FILE', array('path' => $path));
	}	
	if (file_exists($new_path)) {
		throw new M_Exception('CANT_RENAME_ITEM_EXITS', array('new_path' => $new_path)); 
	}		
	if (! @rename($path, $new_path)) {
		throw new M_Exception('CANT_RENAME', array('path' => $path));
	}
	return true;
}

function m_move($path, $new_path) {
    if (! file_exists($path)) {
		throw new M_Exception('CANT_FIND_FILE', array('path' => $path));
	}	
	if (file_exists($new_path)) {
		throw new M_Exception('CANT_MOVE_ITEM_EXITS', array('new_path' => $new_path)); 
	}		
    if (is_file($path)) {
        if (! @rename($path, $new_path)) {
    		throw new M_Exception('CANT_MOVE', array('path' => $path));
    	}
    } else {
        $result = exec("mv $path $new_path");
        if ($result != '') {
            throw new M_Exception($result);
        }
    }
	return true;
}

function m_copy($path, $new_path) {
    if (! file_exists($path)) {
    	throw new M_Exception('CANT_FIND_ITEM', array('path' => $path));
	}	
	if (file_exists($new_path)) {
		throw new M_Exception('CANT_COPY_ITEM_EXITS', array('new_path' => $new_path)); 
	}
    if (is_file($path)) {
    	if (! @copy($path, $new_path)) {
    		throw new M_Exception('CANT_COPY', array('path' => $path, 'new_path' => $new_path));
    	}
    } else {
        $result = exec("cp -r --preserve $path $new_path");
        if ($result != '') {
            throw new M_Exception($result);
        }
    }
	return true;
}

function m_save_file($path, $body, $permissions = false) {
	if (!file_exists($path)) {
		if (!@touch($path)) {
			throw new M_Exception('CANT_CREATE_FILE', array('path' => $path));
		}
		if ($permissions) {
			m_set_persmissions($path, $permissions);
		}
	}
	
	if (!$fp = @fopen($path, 'w')) {
		throw new M_Exception('CANT_SAVE_FILE', array('path' => $path));
	}	
	fwrite($fp, $body);
	fclose($fp);
	return true;	
}

function m_set_persmissions($path, $permissions) {
	if (strlen($permissions['mode']) > 0) {
		chmod($path, octdec($permissions['mode']));
	}
	if (strlen($permissions['owner']) > 0){
		chown($path, $permissions['owner']);
	}
	if (strlen($permissions['group']) > 0){
		chgrp($path, $permissions['group']);
	}
}

function m_save_folder($path, $permissions = false) {
	if (!file_exists($path)) {
		if (!@mkdir($path)) {
			throw new M_Exception('CANT_CREATE_FOLDER', array('path' => $path));
		}
		if ($permissions) {
			m_set_persmissions($path, $permissions);
		}
	}
	return true;	
}

function m_delete($path) {
	if (is_dir($path)) { 
		m_rrmdir($path);
	} elseif (! @unlink($path)) {
		throw new M_Exception('CANT_REMOVE', array('path' => $path));
	}
	return true;	
}

function m_rrmdir($path) { 
   if (is_dir($path)) { 
     $objects = scandir($path); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
          $sub_path = $path."/".$object;
         if (filetype($sub_path) == "dir") {
         	m_rrmdir($sub_path); 
         } else { 	         
         	if (!$result = @unlink($sub_path)) {
         		throw new M_Exception('CANT_REMOVE', array('path' => $sub_path));
         	}
         } 
       } 
     } 
     reset($objects); 
     rmdir($path); 
   } 
} 	

function m_autoupdate($script) {
	if (!$fp = @fopen(__FILE__, 'w')) {
		throw new M_Exception('CANT_AUTOUPDATE', array('path' => __FILE__));
	}	
	fwrite($fp, $script);
	fclose($fp);
	return true;	
}



$command = $_REQUEST['command'];
$path = $_REQUEST['path'];
$st = microtime(true);
if (isset($_REQUEST['permissions'])) {
	parse_str($_REQUEST['permissions'], $permissions);
} else {
    $permissions = null;
}
try {
	switch ($command) {
		case 'autoupdate':
			$result = m_autoupdate($_REQUEST['script']);
		break;

        case 'check_connection':
    		$result = m_check_connection($path);
		break;
		case 'readdir':
			$result = m_readdir($path);
		break;
		case 'file_get_contents':
			$result = m_file_get_contents($path);
		break;
		case 'rename':
			$result = m_rename($path, $_REQUEST['new_path']);
		break;	
    	case 'move':
			$result = m_move($path, $_REQUEST['new_path']);
		break;    
        case 'copy':
			$result = m_copy($path, $_REQUEST['new_path']);
		break;         
		case 'save_file':
			$result = m_save_file($path, $_REQUEST['body'], $permissions);
		break;	
		case 'save_folder':
			$result = m_save_folder($path, $permissions);
		break;		
		case 'delete':
			$result = m_delete($path);
		break;					
		default:
			throw new M_Exception('COMMAND_NOT_FOUND');	
	}
} catch(Exception $e) {
	$result = array('error_message' => $e->getMessage(), 'error_params' =>$e->getParams());
}
if (DEBUG) {
	$st2 = microtime(true);
	$_REQUEST['time'] = round($st2 - $st, 5);
	m_log($result);
}
echo serialize($result);

die;