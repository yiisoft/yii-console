<?php
namespace Yiisoft\Yii\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Serve extends Command
{
    // const EXIT_CODE_NO_DOCUMENT_ROOT = 2;
    // const EXIT_CODE_NO_ROUTING_FILE = 3;
    // const EXIT_CODE_ADDRESS_TAKEN_BY_ANOTHER_SERVER = 4;
    // const EXIT_CODE_ADDRESS_TAKEN_BY_ANOTHER_PROCESS = 5;

    private const DEFAULT_PORT = 8080;
    private const DEFAULT_DOCROOT = 'public';

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'serve';

    public function configure()
    {
        $this
            ->setDescription('Runs PHP built-in web server')
            ->setHelp('In order to access server from remote machines use 0.0.0.0:8000. That is especially useful when running server in a virtual machine.')
            ->addArgument('address', InputArgument::OPTIONAL, 'Host to serve at', 'localhost')
            ->addOption('port', 'p', InputOption::VALUE_OPTIONAL, 'Port to serve at', self::DEFAULT_PORT)
            ->addOption('docroot', 't', InputOption::VALUE_OPTIONAL, 'Document root to serve from', self::DEFAULT_DOCROOT)
            ->addOption('router', 'r', InputOption::VALUE_OPTIONAL, 'Path to router script');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $address = $input->getArgument('address');

        $port = $input->getOption('port');
        $docroot = $input->getOption('docroot');
        $router = $input->getOption('router');

        $documentRoot = getcwd() . '/' . $docroot; // TODO: can we do it better?

        if (strpos($address, ':') === false) {
            $address = $address . ':' . $port;
        }

        if (!is_dir($documentRoot)) {
            $io->error("Document root \"$documentRoot\" does not exist.");
            return;
            //return self::EXIT_CODE_NO_DOCUMENT_ROOT;
        }

        if ($this->isAddressTaken($address)) {
            $output->writeLn("http://$address is taken by another process.\n", Console::FG_RED);
            return;
            //return self::EXIT_CODE_ADDRESS_TAKEN_BY_ANOTHER_PROCESS;
        }

        if ($router !== null && !file_exists($router)) {
            $output->writeLn("Routing file \"$router\" does not exist.\n", Console::FG_RED);
            return;
            //return self::EXIT_CODE_NO_ROUTING_FILE;
        }

        $output->writeLn("Server started on http://{$address}/\n");
        $output->writeLn("Document root is \"{$documentRoot}\"\n");
        if ($router) {
            $output->writeLn("Routing file is \"$router\"\n");
        }
        $output->writeLn("Quit the server with CTRL-C or COMMAND-C.\n");

        passthru('"' . PHP_BINARY . '"' . " -S {$address} -t \"{$documentRoot}\" $router");
    }

    /**
     * @param string $address server address
     * @return bool if address is already in use
     */
    protected function isAddressTaken($address)
    {
        [$hostname, $port] = explode(':', $address);
        $fp = @fsockopen($hostname, $port, $errno, $errstr, 3);
        if ($fp === false) {
            return false;
        }
        fclose($fp);
        return true;
    }
}
