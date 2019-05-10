# PHP SSH helper to executing commands on server via SSH protocol

Library provides simply using of SSH under PHP. Choose the best usage option

## Pre requirements
 * PHP installed
 * composer installed
 * php-ssh2 installed (#sudo apt-get install php-ssh2)
 
## Installation

**composer require assghard/php-ssh-helper --prefer-dist**

## How to use
Add library to use: **use Assghard\SSH\SSHhelper;**

```php
$sshConfig = [
        'host' => '192.168.1.12', // server IP address
        'port' => 22, // SSH port
        'user' => 'root', // SSH username
        'password' => '12345' // SSH user password
];

$sshHelper = new SSHhelper($sshConfig); // do connect

$sshHelper->execSshCommand('mkdir testDir');
$sshHelper->ssh_chmod('testDir', 0777);
$sshHelper->execSshCommand('touch ./testDir/test.txt');
$sshHelper->ssh_copy_file('./testDir/test.txt', '/var/www/html/test/TEST.txt');
$sshHelper->ssh_delete_file('./testDir/test.txt');
$sshHelper->ssh_remove_dir('./testDir');

$sshHelper->disconnect();
```

### If you use Laravel 5+ you can add an alias in /config/app.php file
```php
'aliases' => [
...
'SSHhelper' => Assghard\SSH\SSHhelper::class,
...
],
```

And in controller:
```php
$sshConfig = [
        'host' => '192.168.1.12', // server IP address
        'port' => 22, // SSH port
        'user' => 'root', // SSH username
        'password' => '12345' // SSH user password
];

$sshHelper = new \SSHhelper($sshConfig);
$sshHelper->execSshCommand('mkdir TEST');
$sshHelper->disconnect();
```

### Dependency injection

Add library to use: **use Assghard\SSH\SSHhelper;**

```php
private $ssh;
public function __construct(SSHhelper $ssh) {
    $this->ssh = $ssh;
}

...
$sshConfig = [
        'host' => '192.168.1.12', // server IP address
        'port' => 22, // SSH port
        'user' => 'root', // SSH username
        'password' => '12345' // SSH user password
];
$this->ssh->setConfig($sshConfig);
$this->ssh->execSshCommand('mkdir TEST');
$this->ssh->disconnect();
...
```

## Method list
 * **setConfig()** - Setter which sets a server SSH configuration to helper
 * **connect()** - Do ssh2_connect to the remote server via SSH and set a connection stream. Method is using inside others methods.
 * **disconnect()** - Do disconnect and free resources
 * **execSshCommand()** - General execution method. Executes commands on server via SSH connection
 * ssh_chmod() - Change remote folder permissions
 * ssh_copy_file() - Copy file on remote server from sourceFile location to destinationFile
 * ssh_delete_file() - Deletes remote file (NOT a folder)
 * ssh_remove_dir() - Removes remote folder

## Tested on PHP 7.2 and Laravel 5.8
	
	
	
	
	
	
	
	