<?php
/**
 * Created by rsilveira.
 * User: rsilveira
 * Date: 14/07/16
 * Time: 19:33
 */

namespace src\core\services;

class Database
{
    private $user;
    private $password;
    private $database;
    private $old_url;
    private $new_url;
    private $host;
    private $fileName;

    /**
     * Database constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->host      = $config->get('local-database.host');
        $this->user      = $config->get('local-database.user');
        $this->password  = $config->get('local-database.pass');
        $this->old_url   = $config->get('local-database.url-wordpress');
        $this->new_url   = $config->get('remote-database.url-wordpress');
        $this->database  = $config->get('local-database.database');
    }

    /**
     * Method that creates a database dump.
     */
    public function CreatDatabaseDump()
    {
        $this->createFolder();

        @system(
            sprintf(
                'mysqldump -h %s -u %s %s  > %s/%s -p%s',
                $this->host,
                $this->user,
                $this->database,
                'dump_' . $this->fileName,
                $this->fileName . '.sql',
                $this->password
            )
        );
    }

    /**
     * Method that replaces all Wordpress URL's (old x new) from local database.
     */
    public function changeURLs()
    {
        $databaseInfo  = "mysql:host=$this->host;";
        $databaseInfo .= "dbname=$this->database;";
        $databaseInfo .= "charset=UTF8;";

        try {
            $pdo = new \PDO($databaseInfo, $this->user, $this->password);
        } catch (\PDOException $e) {
            print $e->getMessage();
            die();
        }

        $queries = [
            "UPDATE wp_posts SET guid = replace(guid, '%s', '%s')",
            "UPDATE wp_posts SET post_content = replace(post_content, '%s', '%s')",
            "UPDATE wp_links SET link_url = replace(link_url, '%s', '%s')",
            "UPDATE wp_links SET link_image = replace(link_image, '%s', '%s')",
            "UPDATE wp_postmeta SET meta_value = replace(meta_value, '%s', '%s')",
            "UPDATE wp_usermeta SET meta_value = replace(meta_value, '%s', '%s')",
            "UPDATE wp_options SET option_value = replace(option_value, '%s', '%s')"
        ];

        foreach ($queries as $query) {
            $pdo->exec(sprintf($query, $this->old_url, $this->new_url));
        }
    }

    /**
     * Method that creates a folder.
     */
    private function createFolder()
    {
        $date = new \DateTime();
        $this->fileName = $date->format('Y_m_d_H_i');

        mkdir('dump_' . $this->fileName);
    }

    /**
     * Returns the dump file name.
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }
}