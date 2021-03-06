<?php namespace Arcanedev\Workbench\Commands;

use Arcanedev\Generators\Migrations\NameParser;
use Arcanedev\Generators\Migrations\SchemaParser;
use Arcanedev\Support\Stub;
use Arcanedev\Workbench\Bases\BenchCommand;
use Arcanedev\Workbench\Exceptions\InvalidMigrationNameException;

/**
 * Class     MakeMigrationCommand
 *
 * @package  Arcanedev\Workbench\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MakeMigrationCommand extends BenchCommand
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
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get template contents.
     *
     * @throws InvalidMigrationNameException
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $parser = new NameParser($this->getStringArg('name'));

        if ($parser->isCreate()) {
            return Stub::create('/migration/create.stub', [
                'class'  => $this->getClass(),
                'table'  => $parser->getTable(),
                'fields' => $this->getSchemaParser()->render(),
            ])->render();
        }
        elseif ($parser->isAdd()) {
            return Stub::create('/migration/add.stub', [
                'class'       => $this->getClass(),
                'table'       => $parser->getTable(),
                'fields_up'   => $this->getSchemaParser()->up(),
                'fields_down' => $this->getSchemaParser()->down(),
            ])->render();
        }
        elseif ($parser->isDelete()) {
            return Stub::create('/migration/delete.stub', [
                'class'        => $this->getClass(),
                'table'        => $parser->getTable(),
                'fields_down'  => $this->getSchemaParser()->up(),
                'fields_up'    => $this->getSchemaParser()->down(),
            ])->render();
        }
        elseif ($parser->isDrop()) {
            return Stub::create('/migration/drop.stub', [
                'class'     => $this->getClass(),
                'table'     => $parser->getTable(),
                'fields'    => $this->getSchemaParser()->render(),
            ])->render();
        }

        throw new InvalidMigrationNameException();
    }

    /**
     * Get the destination file path.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function getDestinationFilePath($name = '')
    {
        return parent::getDestinationFilePath('migration');
    }

    /**
     * Get class name.
     *
     * @return string
     */
    protected function getClass()
    {
        return str_studly($this->getStringArg('name'));
    }

    /**
     * Get studly file name
     *
     * @return string
     */
    protected function getFileName()
    {
        return date('Y_m_d_His_') . $this->getSchemaName();
    }

    /**
     * Get schema parser.
     *
     * @return SchemaParser
     */
    private function getSchemaParser()
    {
        return new SchemaParser($this->getStringOption('fields'));
    }

    /**
     * Get schema name
     *
     * @return string
     */
    private function getSchemaName()
    {
        return $this->getStringArg('name');
    }

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
}

