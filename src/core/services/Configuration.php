<?php
/**
 * Created by rsilveira.
 * User: rsilveira
 * Date: 14/07/16
 * Time: 19:14
 */

namespace src\core\services;

use Symfony\Component\Yaml\Yaml;

class Configuration
{
    protected $parameters;

    /**
     * Config constructor.
     */
    public function __construct(){
        $this->parameters = Yaml::parse(
            file_get_contents('/Library/WebServer/Documents/wordpress-database-migrate/src/config/parameters.yml'
            )
        );
    }

    /**
     * @param $index
     * @return bool
     */
    public function get($index){
        $indexes = explode('.', $index);

        return $this->recursive($indexes, $this->parameters);
    }

    /**
     * @param $indexes
     * @param $parameters
     * @return bool
     */
    protected function recursive($indexes, $parameters){
        $index = array_shift($indexes);

        if (!isset($parameters[$index])) {
            return false;
        }

        if (!count($indexes)) {
            return $parameters[$index];
        }

        return $this->recursive($indexes, $parameters[$index]);
    }
}
