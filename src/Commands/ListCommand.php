<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Entities\Module;

/**
 * Class ListCommand
 * @package Arcanedev\Workbench\Commands
 */
class ListCommand extends Command
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
    protected $signature = 'module:list
                            {--only= : Types of modules will be displayed.}
                            {--dir=asc : The direction of ordering.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show list of all modules.';

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
        $this->table(['Name', 'Status', 'Order', 'Path'], $this->getRows());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get table rows.
     *
     * @return array
     */
    protected function getRows()
    {
        return array_map(function (Module $module) {
            return [
                $module->getStudlyName(),
                $module->enabled() ? 'Enabled' : 'Disabled',
                $module->get('order'),
                $module->getPath(),
            ];
        }, $this->getModules());
    }

    /**
     * Get modules.
     *
     * @return array
     */
    private function getModules()
    {
        $workbench = workbench();

        if ( ! $this->option('only')) {
            return workbench()->all();
        }

        $choice = $this->anticipate('Select only modules that are ?', [
            'enabled', 'disabled', 'ordered'
        ]);

        switch ($choice) {
            case 'enabled':
                return $workbench->getByStatus(1);
            // no break

            case 'disabled':
                return $workbench->getByStatus(0);
            // no break

            case 'ordered':
            default:
                return $workbench->getOrdered($this->getStringOption('dir'));
            // no break
        }
    }
}
