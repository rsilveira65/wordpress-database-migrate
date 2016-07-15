<?php
namespace src\core;
/**
 * Created by PhpStorm.
 * User: cliente
 * Date: 14/07/16
 * Time: 18:28
 */

use src\core\services\Database;
use src\core\services\SSHConnection;
use src\core\services\SCPConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use src\core\services\Configuration;


class MigrateCommand extends Command
{
    protected function configure()
    {
        $this->setName('run:migrate')
            ->setDescription('Wordpress Database Migrate')
                ->setHelp('The command <info>run:migrate</info> will do the magic');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('+-+-+-+-+-+-+-+-+-+ +-+-+-+-+-+-+-+-+ +-+-+-+-+-+-+-+');
        $output->writeln('|W|o|r|d|p|r|e|s|s| |D|a|t|a|b|a|s|e| |M|i|g|r|a|t|e|');
        $output->writeln('+-+-+-+-+-+-+-+-+-+ +-+-+-+-+-+-+-+-+ +-+-+-+-+-+-+-+');

        $config = new Configuration();

//        $output->writeln('Replacing Local URLs');
        $database = new Database($config);
//        $database->changeURLs();

        $output->writeln('Creating database dump');
        $database->CreatDatabaseDump();

        $output->writeln('Coping database to remote server');
        $conSCP = new SCPConnection($config);
        $conSCP->SCPTransfer($database->getFileName());

        $output->writeln('Importing the database dump to mysql');
        $conSSH = new SSHConnection($config);
        $conSSH->SSHCommand($database->getFileName(), $output);

        $output->writeln('Done!');

    }
}
