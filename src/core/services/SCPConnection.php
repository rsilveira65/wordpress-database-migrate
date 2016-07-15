<?php
/**
 * Created by PhpStorm.
 * User: cliente
 * Date: 14/07/16
 * Time: 20:54
 */

namespace src\core\services;

class SCPConnection
{
    private $port;
    private $host;
    private $username;
    private $password;
    private $path;

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
    }

    /**
     * Creates a SCP transfer to remote server.
     * @param $fileName
     */
    public function SCPTransfer($fileName)
    {
        @system(
            sprintf(
                'scp -r %s %s@%s:%s',
                'dump_' . $fileName,
                $this->username,
                $this->host,
                $this->path
            )
        );
    }
}