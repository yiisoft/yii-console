<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yiisoft\Yii\Console\ExitCode;

use function explode;
use function fclose;
use function file_exists;
use function fsockopen;
use function is_dir;
use function passthru;

#[AsCommand('serve', 'Runs PHP built-in web server')]
final class Serve extends Command
{
    public const EXIT_CODE_NO_DOCUMENT_ROOT = 2;
    public const EXIT_CODE_NO_ROUTING_FILE = 3;
    public const EXIT_CODE_ADDRESS_TAKEN_BY_ANOTHER_PROCESS = 5;

    private string $defaultAddress;
    private string $defaultPort;
    private string $defaultDocroot;
    private string $defaultRouter;
    private int $defaultWorkers;

    /**
     * @psalm-param array{
     *     address?:non-empty-string,
     *     port?:non-empty-string,
     *     docroot?:string,
     *     router?:string,
     *     workers?:int|string
     * } $options
     */
    public function __construct(private ?string $appRootPath = null, ?array $options = [])
    {
        $this->defaultAddress = $options['address'] ?? '127.0.0.1';
        $this->defaultPort = $options['port'] ?? '8080';
        $this->defaultDocroot = $options['docroot'] ?? 'public';
        $this->defaultRouter = $options['router'] ?? 'public/index.php';
        $this->defaultWorkers = (int) ($options['workers'] ?? 2);

        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->setHelp(
                'In order to access server from remote machines use 0.0.0.0:8000. That is especially useful when running server in a virtual machine.'
            )
            ->addArgument('address', InputArgument::OPTIONAL, 'Host to serve at', $this->defaultAddress)
            ->addOption('port', 'p', InputOption::VALUE_OPTIONAL, 'Port to serve at', $this->defaultPort)
            ->addOption(
                'docroot',
                't',
                InputOption::VALUE_OPTIONAL,
                'Document root to serve from',
                $this->defaultDocroot
            )
            ->addOption('router', 'r', InputOption::VALUE_OPTIONAL, 'Path to router script', $this->defaultRouter)
            ->addOption(
                'workers',
                'w',
                InputOption::VALUE_OPTIONAL,
                'Workers number the server will start with',
                $this->defaultWorkers
            )
            ->addOption('env', 'e', InputOption::VALUE_OPTIONAL, 'It is only used for testing.')
            ->addOption('open', 'o', InputOption::VALUE_OPTIONAL, 'Opens the serving server in the default browser.')
            ->addOption('xdebug', 'x', InputOption::VALUE_OPTIONAL, 'Enables XDEBUG session.', false);
    }

    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if ($input->mustSuggestArgumentValuesFor('address')) {
            $suggestions->suggestValues(['localhost', '127.0.0.1', '0.0.0.0']);
            return;
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Yii3 Development Server');
        $io->writeln('https://yiiframework.com' . "\n");

        /** @var string $address */
        $address = $input->getArgument('address');

        /** @var string $router */
        $router = $input->getOption('router');
        $workers = (int) $input->getOption('workers');

        /** @var string $port */
        $port = $input->getOption('port');

        /** @var string $docroot */
        $docroot = $input->getOption('docroot');

        if ($router === $this->defaultRouter && !file_exists($this->defaultRouter)) {
            $io->warning(
                'Default router "' . $this->defaultRouter . '" does not exist. Serving without router. URLs with dots may fail.'
            );
            $router = null;
        }

        /** @var string $env */
        $env = $input->getOption('env');

        $documentRoot = $this->getRootPath() . DIRECTORY_SEPARATOR . $docroot;

        if (!str_contains($address, ':')) {
            $address .= ':' . $port;
        }

        if (!is_dir($documentRoot)) {
            $io->error("Document root \"$documentRoot\" does not exist.");
            return self::EXIT_CODE_NO_DOCUMENT_ROOT;
        }

        if ($this->isAddressTaken($address)) {
            $io->error("http://$address is taken by another process.");
            return self::EXIT_CODE_ADDRESS_TAKEN_BY_ANOTHER_PROCESS;
        }

        if ($router !== null && !file_exists($router)) {
            $io->error("Routing file \"$router\" does not exist.");
            return self::EXIT_CODE_NO_ROUTING_FILE;
        }

        $command = [];

        $isLinux = DIRECTORY_SEPARATOR !== '\\';

        if ($isLinux) {
            $command[] = 'PHP_CLI_SERVER_WORKERS=' . $workers;
        }

        $xDebugInstalled = extension_loaded('xdebug');
        $xDebugEnabled = $isLinux && $xDebugInstalled && $input->hasOption('xdebug') && $input->getOption('xdebug') === null;

        if ($xDebugEnabled) {
            $command[] = 'XDEBUG_MODE=debug XDEBUG_TRIGGER=yes';
        }
        $outputTable = [];
        $outputTable[] = ['PHP', PHP_VERSION];
        $outputTable[] = [
            'xDebug',
            $xDebugInstalled ? sprintf(
                '%s, %s',
                phpversion('xdebug'),
                $xDebugEnabled ? '<info> Enabled </>' : '<error> Disabled </>',
            ) : '<error>Not installed</>',
            '--xdebug',
        ];
        $outputTable[] = ['Workers', $isLinux ? $workers : 'Not supported', '--workers, -w'];
        $outputTable[] = ['Address', $address];
        $outputTable[] = ['Document root', $documentRoot, '--docroot, -t'];
        $outputTable[] = ($router ? ['Routing file', $router, '--router, -r'] : []);

        $io->table(['Configuration', null, 'Options'], $outputTable);

        $command[] = '"' . PHP_BINARY . '"' . " -S $address -t \"$documentRoot\" $router";
        $command = implode(' ', $command);

        $output->writeln([
            'Executing: ',
            sprintf('<info>%s</>', $command),
        ], OutputInterface::VERBOSITY_VERBOSE);

        $io->success('Quit the server with CTRL-C or COMMAND-C.');

        if ($env === 'test') {
            return ExitCode::OK;
        }

        $openInBrowser = $input->hasOption('open') && $input->getOption('open') === null;

        if ($openInBrowser) {
            passthru('open http://' . $address);
        }
        passthru($command, $result);

        return $result;
    }

    /**
     * @param string $address The server address.
     *
     * @return bool If address is already in use.
     */
    private function isAddressTaken(string $address): bool
    {
        [$hostname, $port] = explode(':', $address);
        $fp = @fsockopen($hostname, (int) $port, $errno, $errstr, 3);

        if ($fp === false) {
            return false;
        }

        fclose($fp);
        return true;
    }

    private function getRootPath(): string
    {
        if ($this->appRootPath !== null) {
            return rtrim($this->appRootPath, DIRECTORY_SEPARATOR);
        }

        return getcwd();
    }
}
