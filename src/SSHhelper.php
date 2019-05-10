<?php
/**
 * PHP SSH helper contains a set of helpful methods which makes working with SSH under PHP more easily
 *
 * @package assghard/php-ssh-helper
 * @author Ivan Dublianski (assghard) <ivan.dublianski@gmail.com>
 */
namespace Assghard\SSH;

class SSHhelper {

    private $config;
    private $connection;
    private $stream;

    public function __construct($config = []) {
        $this->config = $config;
    }
    public function __destruct() {
        $this->disconnect();
    }

    /**
     * Setter which sets a server SSH configuration to helper
     *
     * @param array $configuration Associative array (key-value) should contains SSH host, port, user, password as array keys and data as values
     * @return void
     */
    public function setConfig($configuration){
        $this->config = $configuration;
    }

    /**
     * Do ssh2_connect to the remote server via SSH and
     * set $this->connection and $this->stream params
     *
     * @return void
     */
    private function connect() {
        if(!function_exists('ssh2_connect')) {
            die('Function function_exists not found. You can not use ssh2 here');
        }

        try {
            if(empty($this->connection)) {
                $this->connection = ssh2_connect($this->config['host'], $this->config['port']);
                ssh2_auth_password($this->connection, $this->config['user'], $this->config['password']);

                $this->stream = ssh2_sftp($this->connection);
            }
        } catch (\Exception $exc) {
            echo $exc->getMessage().PHP_EOL;
        }
    }

    /**
     * Disconnects SSH stream and frees resources
     *
     * @return void
     */
    public function disconnect() {
        if(isset($this->stream) && !empty($this->stream)){
            ssh2_disconnect($this->stream);
            unset($this->stream);
        }

        if(isset($this->connection) && !empty($this->connection)){
            ssh2_disconnect($this->connection);
            unset($this->connection);
        }
    }

    /**
     * General execution method. Executes commands on server via SSH connection
     *
     * @param string $command
     * @return object $stream
     */
    public function execSshCommand($command) {
        try {
            $this->connect();
            ssh2_exec($this->connection, $command);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }


    /*
     * ========================================================
     * Additional methods
     * ========================================================
     */

     /**
      * Change remote folder permissions
      *
      * @param string $folder Absolute (or relative) path to folder
      * @param integer $mod Folder permissions with zero at the begining
      * @return void
      */
    public function ssh_chmod($folder, $mod = 0755) {
        try {
            $this->connect();
            ssh2_sftp_chmod($this->stream, $folder, $mod);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Copy file on remote server from sourceFile location to destinationFile
     *
     * @param string $sourceFile Absolute path to source file with filename and extension
     * @param [type] $destinationFile Absolute path to destination file with filename and extension
     * @return void
     */
    public function ssh_copy_file($sourceFile, $destinationFile) {
        try {
            $this->connect();
            copy("ssh2.sftp://{$this->stream}/{$sourceFile}", $destinationFile);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Deletes remote file (NOT a folder)
     *
     * @param string $remoteFile Absolute path to file which should be deleted
     * @return void
     */
    public function ssh_delete_file($remoteFile) {
        try {
            $this->connect();
            ssh2_sftp_unlink($this->stream, $remoteFile);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Removes remote folder
     *
     * @param string $remoteDir Absolute path to folder which should be deleted
     * @return void
     */
    public function ssh_remove_dir($remoteDir) {
        try {
            $this->connect();
            ssh2_sftp_rmdir($this->stream, $remoteDir);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
