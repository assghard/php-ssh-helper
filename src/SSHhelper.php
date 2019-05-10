<?php
/**
 * PHP SSH helper
 * 
 * @package assghard/assghard/php-ssh-helper
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

    public function setConfig($configuration){
        $this->config = $configuration;
    }

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

    public function disconnect() {
        ssh2_disconnect($this->stream);
        unset($this->stream);

        ssh2_disconnect($this->connection);
        unset($this->connection);
    }

    /**
     * General execution method. Executes command on server via SSH connection
     *
     * @param string $command
     * @return object $stream
     */
    public function execSshCommand($command) {
        try {
            $this->connect();
            $this->stream = ssh2_exec($this->connection, $command);

            return $this->stream;
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }


    /*
     * ========================================================
     * Additional methods
     * ========================================================
     */
    public function ssh_chmod($folder, $mod = 0755) {
        try {
            ssh2_sftp_chmod($this->stream, $folder, $mod);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function ssh_copy_file($remoteFile, $localFile) {
        try {
            copy("ssh2.sftp://{$this->stream}/{$remoteFile}", $localFile);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function ssh_unlink_file($remoteFile) {
        try {
            ssh2_sftp_unlink($this->stream, $remoteFile);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function ssh_remove_dir($remoteDir) {
        try {
            ssh2_sftp_rmdir($this->stream, $remoteDir);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
