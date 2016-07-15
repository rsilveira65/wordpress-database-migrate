<?php
/**
 * Created by rsilveira.
 * User: rsilveira
 * Date: 14/07/16
 * Time: 19:32
 */

namespace src\core\services;

class SSHConnection
{
    private $port;
    private $host;
    private $username;
    private $password;
    private $connection;
    private $path;
    private $database;
    private $database_pass;

    /**
     * SSHConnection constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->port     = (int) $config->get('remote.port');
        $this->host     = $config->get('remote.host');
        $this->username = $config->get('remote.user');
        $this->password = $config->get('remote.pass');
        $this->path     = $config->get('remote.path');
        $this->database = $config->get('remote-database.database');
        $this->database_pass = $config->get('remote-database.pass');
    }

    /**
     * Creates a ssh command to remote server to import the database dump.
     * @param $fileName
     * @return resource
     */
    public function SSHCommand($fileName, $output)
    {
        $this->connection = ssh2_connect($this->host, $this->port);

        if (!ssh2_auth_password($this->connection, $this->username, $this->password)) {
            $output->writeln('Authentication Failed');
            die;
        }

        $output->writeln('Authentication Successful!');

        $cmd = sprintf(
            'mysql -u %s %s < %s -p"%s"',
            $this->username,
            $this->database,
            $this->path . '/dump_' . $fileName . '/' . $fileName . '.sql',
            $this->database_pass
        );

       @ssh2_exec($this->connection, $cmd);
    }
}
