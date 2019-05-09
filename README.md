$sshHelper = new SSHhelper(['host' => '192.168.1.12', 'port' => 22, 'user' => 'root', 'password' => '12345']);
$sshHelper->execSshCommand('reboot');
$sshHelper->disconnect();