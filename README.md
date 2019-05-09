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
$sshHelper->execSshCommand('mkdir TEST'); // creates a directory
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

## Tested on PHP 7.2 and Laravel 5.8
	
	
	
	
	
	
	
	