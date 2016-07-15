#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use src\core\MigrateCommand;

$application = new Application();
$application->add(new MigrateCommand());
$application->run();