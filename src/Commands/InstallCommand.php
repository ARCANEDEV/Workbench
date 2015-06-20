<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Json;
use Arcanedev\Workbench\Process\Installer;
use Illuminate\Console\Command;

/**
 * Class InstallCommand
 * @package Arcanedev\Workbench\Commands
 */
class InstallCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install
                            {name? : The name of module will be installed.}
                            {version? : The version of module will be installed.}
                            {--timeout? : The process timeout.}
                            {--path? : The installation path.}
                            {--type? : The type of installation.}
                            {--tree? : Install the module as a git subtree}
                            {--no-update? : Disables the automatic update of the dependencies.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the specified module by given package name (vendor/name).';

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (is_null($this->argument('name'))) {
            $this->installFromFile();

            return;
        }

        $this->install(
            $this->argument('name'),
            $this->argument('version'),
            $this->option('type'),
            $this->option('tree')
        );
    }

    /**
     * Install modules from modules.json file.
     */
    protected function installFromFile()
    {
        if ( ! file_exists($path = base_path('modules.json'))) {
            $this->error("File 'modules.json' does not exist in your project root.");

            return;
        }

        $modules      = Json::make($path);
        $dependencies = $modules->get('require', []);

        foreach ($dependencies as $module) {
            $module = collect($module);
            $this->install(
                $module->get('name'),
                $module->get('version'),
                $module->get('type')
            );
        }
    }

    /**
     * Install the specified module.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param bool   $tree
     */
    protected function install($name, $version = 'dev-master', $type = 'composer', $tree = false)
    {
        $installer = new Installer(
            $name,
            $version,
            $type ?: $this->option('type'),
            $tree ?: $this->option('tree')
        );

        $installer->setRepository($this->laravel['modules']);
        $installer->setConsole($this);

        if ($timeout = $this->option('timeout')) {
            $installer->setTimeout($timeout);
        }

        if ($path = $this->option('path')) {
            $installer->setPath($path);
        }

        $installer->run();

        if ( ! $this->option('no-update')) {
            $this->call('module:update', [
                'module' => $installer->getModuleName(),
            ]);
        }
    }
}
