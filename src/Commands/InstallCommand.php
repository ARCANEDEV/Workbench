<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Support\Json;
use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Process\Installer;

/**
 * Class     InstallCommand
 *
 * @package  Arcanedev\Workbench\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
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
                            {--type= : The type of installation.}
                            {--tree : Install the module as a git subtree}
                            {--path= : The installation path.}
                            {--timeout= : The process timeout.}
                            {--no-update : Disables the automatic update of the dependencies.}';

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
        if ($name = $this->getStringArg('name')) {
            $this->installFromFile();
        }
        else {
            $this->install(
                $name,
                $this->getStringArg('version'),
                $this->getStringOption('type'),
                $this->getBoolOption('tree')
            );
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Install modules from modules.json file.
     */
    private function installFromFile()
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
    private function install($name, $version = 'dev-master', $type = 'composer', $tree = false)
    {
        $installer = new Installer(
            $name,
            $version,
            $type ?: $this->getStringOption('type'),
            $tree ?: $this->getBoolOption('tree')
        );

        $installer->setRepository(workbench());
        $installer->setConsole($this);

        if ($timeout = $this->option('timeout')) {
            $installer->setTimeout((int) $timeout);
        }

        if ($path = $this->getStringOption('path')) {
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
