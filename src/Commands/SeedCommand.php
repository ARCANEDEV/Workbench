<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Traits\ModuleCommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class SeedCommand
 * @package Arcanedev\Workbench\Commands
 */
class SeedCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use ModuleCommandTrait;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run database seeder from the specified module or from all modules.';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     */
    public function fire()
    {
        $this->module = $this->laravel['modules'];
        $module = Str::studly($this->argument('module')) ?: $this->getModuleName();

        if ($module) {
            if (workbench()->has($module)) {
                $this->dbseed($module);

                $this->info("Module [$module] seeded.");
            }
            else {
                $this->error("Module [$module] does not exists.");
            }

            return;
        }

        foreach (workbench()->all() as $name) {
            $this->dbseed($name);
        }

        $this->info('All modules seeded.');
    }

    /**
     * Seed the specified module.
     *
     * @param  string  $name
     *
     * @return array
     */
    protected function dbseed($name)
    {
        $params = [
            '--class' => $this->option('class') ?: $this->getSeederName($name),
        ];

        if ($option = $this->option('database')) {
            $params['--database'] = $option;
        }

        $this->call('db:seed', $params);
    }

    /**
     * Get master database seeder name for the specified module.
     *
     * @param string $name
     *
     * @return string
     */
    public function getSeederName($name)
    {
        $name      = Str::studly($name);
        $namespace = workbench()->config('namespace');

        return $namespace . '\\' . $name . '\Database\Seeders\\' . $name . 'DatabaseSeeder';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder', null],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed.'],
        ];
    }
}
