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
        try {
            if(empty($this->connection)) {
                $this->connection = ssh2_connect($this->config['host'], $this->config['port']);
                ssh2_auth_password($this->connection, $this->config['user'], $this->config['password']);
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
     * Executes command on server via SSH connection
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
}
