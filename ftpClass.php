<?php

//This ftpClass.php file needs external phpseclib library to function. so we need to install that via composer
//composer require phpseclib/phpseclib:^2.0
require 'vendor/autoload.php';

//This ftpClass.php file needs external phpseclib library to function. so we need to install that via composer
//composer require phpseclib/phpseclib:^2.0
use phpseclib\Net\SFTP;

class FileTransfer {
    private $connection;
    private $isSftp;
    private $host;
    private $port;
    private $username;
    private $password;

    public function __construct($host, $username, $password, $isSftp = false, $port = null) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->isSftp = $isSftp;
        //test URL settings for renewgrid ftp
        //$this->port = $port ? $port : ($isSftp ? 2222 : 2121);
        $this->port = $port ? $port : ($isSftp ? 22 : 21);
    }

    public function connect() {
        if ($this->isSftp) {
            $this->connection = new SFTP($this->host, $this->port);
            if (!$this->connection->login($this->username, $this->password)) {
                throw new Exception('Could not authenticate on SFTP server');
            }
        } else {
            $this->connection = ftp_connect($this->host, $this->port);
            if (!$this->connection) {
                throw new Exception('Could not connect to FTP server');
            }
            if (!ftp_login($this->connection, $this->username, $this->password)) {
                throw new Exception('Could not authenticate on FTP server');
            }
            ftp_pasv($this->connection, true); // Enable passive mode
        }
    }

    public function uploadFile($localFile, $remoteFile) {
        if ($this->isSftp) {
            if (!$this->connection->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
                throw new Exception("Could not upload file: $localFile to $remoteFile");
            }
        } else {
            if (!ftp_put($this->connection, $remoteFile, $localFile, FTP_BINARY)) {
                throw new Exception("Could not upload file: $localFile to $remoteFile");
            }
        }
    }

    public function close() {
        if ($this->isSftp) {
            if ($this->connection) {
                $this->connection->disconnect();
            }
        } else {
            if ($this->connection) {
                ftp_close($this->connection);
            }
        }
    }
}


?>
