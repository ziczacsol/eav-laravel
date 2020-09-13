<?php

namespace Eav\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Support\Composer;


class MakeEntityMigration extends MigrateMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eav:migration {name : The name of the migration}
        {--type= : Type of entity}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make migration for entity';

    /**
     * The migration creator instance.
     *
     * @var MigrationEntityCreator
     */
    protected $creator;

    /**
     * The Composer instance.
     *
     * @var Composer
     */
    protected $composer;

    /**
     * Create a new migration install command instance.
     *
     * @param MigrationEntityCreator $creator
     * @param Composer $composer
     */
    public function __construct(MigrationEntityCreator $creator, Composer $composer)
    {
        parent::__construct($creator, $composer);
    }

    public function handle()
    {
        $this->creator->setEntityType($this->input->getOption('type'));
        parent::handle();
    }
}
