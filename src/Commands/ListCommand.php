<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Workbench\Entities\Module;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

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
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:list';

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
    public function fire()
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
        $rows = array_map(function (Module $module) {
            return [
                $module->getStudlyName(),
                $module->enabled() ? 'Enabled' : 'Disabled',
                $module->get('order'),
                $module->getPath(),
            ];
        }, $this->getModules());

        return $rows;
    }

    protected function getModules()
    {
        switch ($this->option('only')) {
            case 'enabled':
                return workbench()->getByStatus(1);
                // no break

            case 'disabled':
                return workbench()->getByStatus(0);
                // no break

            case 'ordered':
                return workbench()->getOrdered($this->option('direction'));
                // no break

            default:
                return workbench()->all();
                // no break
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['only', null, InputOption::VALUE_OPTIONAL, 'Types of modules will be displayed.', null],
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
        ];
    }
}
