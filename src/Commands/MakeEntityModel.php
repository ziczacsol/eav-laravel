<?php

namespace App\Eav\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;


class MakeEntityModel extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'eav:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make entity';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    protected function getStub()
    {
        return __DIR__ . '/Stubs/model.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Entities';
    }

    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            [
                '$ENTITY',
                '$TABLE_NAME'
            ],
            [
                strtolower(Str::kebab($this->argument('name'))),
                strtolower(Str::snake($this->argument('name') . 'Entity')),
            ],
            $stub
        );

        return $this;
    }
}
