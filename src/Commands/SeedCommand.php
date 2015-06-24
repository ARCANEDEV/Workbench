<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;

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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:seed
                            {module? : The name of module will be used.}
                            {--class= : The class name of the root seeder}
                            {--db= : The database connection to seed.}';

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
    public function handle()
    {
        $module = str_studly($this->argument('module')) ?: $this->getModuleName();

        if ($module) {
            if (workbench()->has($module)) {
                $this->dbSeed($module);

                $this->info("Module [$module] seeded.");
            }
            else {
                $this->error("Module [$module] does not exists.");
            }

            return;
        }

        foreach (workbench()->all() as $name) {
            $this->dbSeed($name);
        }

        $this->info('All modules seeded.');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Seed the specified module.
     *
     * @param  string  $name
     *
     * @return array
     */
    private function dbSeed($name)
    {
        $params = [
            '--class' => $this->option('class') ?: $this->getSeederName($name),
        ];

        if ($option = $this->option('db')) {
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
    private function getSeederName($name)
    {
        $name      = str_studly($name);
        $namespace = workbench()->config('namespace');

        return $namespace . '\\' . $name . '\Database\Seeders\\' . $name . 'DatabaseSeeder';
    }
}
