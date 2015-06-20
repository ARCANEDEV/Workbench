<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Generators\Migrations\NameParser;
use Arcanedev\Generators\Migrations\SchemaParser;
use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\Command;
use Arcanedev\Workbench\Exceptions\InvalidMigrationNameException;
use Arcanedev\Workbench\Traits\ModuleCommandTrait;
use Illuminate\Support\Str;

/**
 * Class MigrationCommand
 * @package Arcanedev\Workbench\Commands
 */
class MigrationCommand extends Command
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
    protected $signature = 'module:make-migration
                            {name : The migration name will be created.}
                            {module? : The name of module will be created.}
                            {--fields= : The specified fields table.}
                            {--plain : Create plain migration.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new migration for the specified module.';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Run the command.
     */
    public function handle()
    {
        parent::handle();

        $this->call('optimize');
    }

    /**
     * Get schema parser.
     *
     * @return SchemaParser
     */
    public function getSchemaParser()
    {
        return new SchemaParser($this->option('fields'));
    }

    /**
     * @throws InvalidMigrationNameException
     *
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $parser = new NameParser($this->argument('name'));

        if ($parser->isCreate()) {
            return Stub::create('/migration/create.stub', [
                'class'  => $this->getClass(),
                'table'  => $parser->getTable(),
                'fields' => $this->getSchemaParser()->render(),
            ]);
        }
        elseif ($parser->isAdd()) {
            return Stub::create('/migration/add.stub', [
                'class'       => $this->getClass(),
                'table'       => $parser->getTable(),
                'fields_up'   => $this->getSchemaParser()->up(),
                'fields_down' => $this->getSchemaParser()->down(),
            ]);
        }
        elseif ($parser->isDelete()) {
            return Stub::create('/migration/delete.stub', [
                'class'        => $this->getClass(),
                'table'        => $parser->getTable(),
                'fields_down'  => $this->getSchemaParser()->up(),
                'fields_up'    => $this->getSchemaParser()->down(),
            ]);
        }
        elseif ($parser->isDrop()) {
            return Stub::create('/migration/drop.stub', [
                'class'     => $this->getClass(),
                'table'     => $parser->getTable(),
                'fields'    => $this->getSchemaParser()->render(),
            ]);
        }

        throw new InvalidMigrationNameException();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path          = workbench()->getModulePath($this->getModuleName());
        $generatorPath = workbench()->config('paths.generator.migration');

        return $path . $generatorPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return date('Y_m_d_His_') . $this->getSchemaName();
    }

    /**
     * @return array|string
     */
    private function getSchemaName()
    {
        return $this->argument('name');
    }

    /**
     * @return string
     */
    private function getClassName()
    {
        return Str::studly($this->argument('name'));
    }

    public function getClass()
    {
        return $this->getClassName();
    }
}
