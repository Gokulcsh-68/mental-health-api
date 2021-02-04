<?php
/**
 * Created by PhpStorm.
 * User: Rubakeerthana
 * Date: 30-09-2015
 * Time: 09:19
 */
namespace App\Services;

use Illuminate\Support\Facades\Config;

class FTPService {

    /**
     * @var array Supported Protocols
     */
    private $_supportedProtocols = [
        self::PROTOCOL_FTP,
        self::PROTOCOL_SSL
    ];

    /**
     * @var Server information
     */
    protected $_protocol;
    protected $_host;
    protected $_port;

    /**
     * @var Credentials
     */
    protected $_username;
    protected $_password;

    /**
     * @var Resource
     */
    protected $_resource;
    protected $_connection;

    /**
     * protocols
     */
    CONST PROTOCOL_FTP = 'ftp';
    CONST PROTOCOL_SSL = 'ftps';
    CONST PROTOCOL_SFTP = 'sftp';

	public function __construct() {
		$this->connection();
	}

    /**
     * Decides which protocol needs to be establish
     * for making connection
     */
    public function connection() {
        $this->_connection  = Config::get('ftp.default');
        $this->_host        = Config::get('ftp.connections.' . $this->_connection . '.host');
        $this->_port        = Config::get('ftp.connections.' . $this->_connection . '.port');
        $this->_username    = Config::get('ftp.connections.' . $this->_connection . '.username');
        $this->_password    = Config::get('ftp.connections.' . $this->_connection . '.password');
        $this->_protocol    = Config::get('ftp.connections.' . $this->_connection . '.protocol');

        $this->_close();

        if (!in_array($this->_protocol, $this->_supportedProtocols)) {
            trigger_error(sprintf("Unsupported Protocols you defined : %s, The supported are (%s)", $this->_protocol, implode(",", $this->_supportedProtocols)), E_USER_ERROR);
            return;
        }

        switch ($this->_protocol) {
            case self::PROTOCOL_FTP :
                $this->_connectFTP();
                break;
            case self::PROTOCOL_SSL :
                $this->_connectSSL();
                break;
            case self::PROTOCOL_SFTP :
            default :
                trigger_error(sprintf("Unsupported Protocols you defined : %s, The supported are (%s)", $this->_protocol, implode(",", $this->_supportedProtocols)), E_USER_ERROR);
        }
    }

    /**
    * get physical path for upload
    */
    public function getPhysicalPath() {
        return Config::get('ftp.connections.' . $this->_connection . '.physical_path');
    }
    /**
    * get physical path for upload
    */
    public function getUrlPath() {
        return Config::get('ftp.connections.' . $this->_connection . '.check_url');
    }
	
    /**
     * Making an simple FTP Connection
     */
    private function _connectFTP() {
        try {
            $this->_resource = ftp_connect($this->_host, $this->_port);

        }
        catch (Exception $exception) {
            trigger_error($exception->getMessage(), E_USER_WARNING);
        }
        $this->_login();
    }

    /**
     * Making an SSL connection
     */
    private function _connectSSL() {
        try {
            $this->_resource = ftp_ssl_connect($this->_host, $this->_port);
        }
        catch (Exception $exception) {
            trigger_error($exception->getMessage(), E_USER_WARNING);
        }
        $this->_login();
    }

    /**
     * Logging IN using FTP Credentials
     */
    private function _login() {
        try {
            ftp_login($this->_resource, $this->_username, $this->_password);
             ftp_pasv($this->_resource, true);

             // ftp_chdir($this->_resource , 'cdntest.a2z.health');
              // dd(ftp_pwd($this->_resource ));exit;
        }
        catch (Exception $exception) {
            trigger_error($exception->getMessage(), E_USER_WARNING);
        }
    }

    /**
     * Check the path is parent (or) Child directory
     * @param $path
     * @return bool
     */
    private static function _isChild($path) {
        return $path != '.' && $path != '..';
    }

    /**
     * Retrieving file list from FTP
     * @param string $path
     * @return array filenames
     */
    private function _files($path = './') {
        return ftp_nlist($path);
    }

    /**
     * Check the path exists
     * @param $path
     * @return bool
     */
    private function _exists($path) {
        $path   =   rtrim($path, "/");
        $files  =   $this->_files(dirname($path));
        return in_array($path, $files);
    }

    /**
     * Creating a Directory
     * @param $path
     */
    public function makeDirectory($path) {
        ftp_mkdir($this->_resource, $path);
    }

    /**
     * upload a file into FTP
     * @param $local
     * @param $remote
     * @param null $mode
     * @return bool
     */
    public function upload($local, $remote, $mode = null) {

        if (is_null($mode)) {
            $mode = self::_transferMode($local);
        }
        try {
            
            ftp_put($this->_resource, $remote, $local, $mode);
        }
        catch (Exception $exception){

            trigger_error("Failed to upload the file, " . $exception->getMessage(), E_USER_ERROR);
          
        }
        return true;
    }

    /*
     * Tells if the given path is a directory
     */
    public function _isDirectory($path) {
         // dd( $this->_resource);exit;
        return is_dir($this->url().$path);
    }

    /*
     * Tells if the given path is a file
     */
    public function _isFile($path) {
        return is_file($this->url().'/'.$path);
    }

    /*
     * Downloads the remote file to local path
     */
    public function download($remote, $local, $mode = null) {
        // If the path is a directory, recursively uploads:
        // Create a dir
        // Create an array containing the files and dirs within the path
        // Download each resource recursively begining with the last
        if($this->_isDirectory($remote)) {
            $files = $this->_files($remote);
            @mkdir($local);
            $files = array_filter($files, function($file) {
                return self::_isChild($file);
            });
            array_map(function($file) use($local) {
                $this->download($file, $local."/".basename($file));
            }, $files);
            return;
        }

        // Define transfer mode
        if(is_null($mode)) {
            $url = $this->url().$remote;
            $mode = self::_transferMode($url);
        }

        // downloads single file
        ftp_get($this->_resource, $local, $remote, $mode);
    }

    /*
     * Removes the given file or directory from FTP
     */
    public function delete($path) {
        // if (!$this->_exists($path)) {
        //     trigger_error('Given path ' . $path . ' does not exist on FTP connection ' . $this->_connection, E_USER_WARNING);
        // }
        // if ($this->isDirectory($path)) {
        //     $this->_clean($path);
        //     ftp_rmdir($this->_resource, $path);
        //     return;
        // }
        // if()
        ftp_delete($this->_resource, $path);
    }

    /**
     * Decides transfer mode depends on file type
     * @param $file
     * @return bool|int
     */
    private static function _transferMode($file) {
        $extensionArray = [
            'am', 'asp', 'bat', 'c', 'cfm', 'cgi', 'conf',
            'cpp', 'css', 'dhtml', 'diz', 'h', 'hpp', 'htm',
            'html', 'in', 'inc', 'js', 'm4', 'mak', 'nfs',
            'nsi', 'pas', 'patch', 'php', 'php3', 'php4', 'php5',
            'phtml', 'pl', 'po', 'py', 'qmail', 'sh', 'shtml',
            'sql', 'tcl', 'tpl', 'txt', 'vbs', 'xml', 'xrc', 'csv'
        ];

        $pathInfo = pathinfo($file);
        if (isset($pathInfo['extension'])) {
            if (in_array(strtolower($pathInfo['extension']), $extensionArray)) {
                return FTP_ASCII;
            }
        }
        return FTP_BINARY;
    }

    /*
     * Returns a usable url for direct download
     * @return string
     */
    public function url() {
        return $this->_protocol . '://' . $this->_username . ':' . $this->_password . '@' . $this->_host . ':' . $this->_port . '/';
    }

    /*
     * Tells whether a directory is empty or not
     */
    private function _isEmpty($path) {
        return count($this->_files($path)) == 0;
    }

    /*
     * Removes all nodes beneath the given path
     */
    private function _clean($path) {
        while(!$this->_isEmpty($path)) {
            $files = $this->_files($path);
            $this->delete(end($files));
        }
    }

    /*
     * encapsulates all vanilla php ftp function
     */
    public function __call($method, $args) {
        $args = array_merge([$this->_resource], $args);
        return call_user_func_array($method, $args);
    }

    /**
     * Closes the ftp connection
     */
    private function _close() {
        if(!is_null($this->_resource)) {
            ftp_close($this->_resource);
            unset($this->_resource);
        }
    }

    /**
     * Closes the ftp connection
     */
    public function __destruct() {
        $this->_close();
    }
}