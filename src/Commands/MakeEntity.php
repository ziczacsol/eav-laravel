<?php

namespace Ziczac\EavLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class MakeEntity extends Command
{
    protected $types = [
        'int',
        'varchar',
        'text',
        'boolean',
        'datetime',
        'decimal',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eav:entity {name : The name of entity}
                            {--types= : The types of entity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ( !is_null($this->option('types'))) {
            $this->types = explode(',', $this->option('types'));
        }

        // Create model
        $this->call('eav:model', ['name' => $name]);

        // Create controller
        $this->call('make:controller', [
            'name'    => "{$name}Controller",
            '--model' => "{$name}",
            '--api'   => true,
        ]);

        // Check and create attributes table
        if (!Schema::hasTable('attributes')) {
            $this->call('eav:migration', [
                'name'   => 'create_attribute_entity_table',
                '--type' => 'attribute'
            ]);

            $this->call('make:model', ['name' => 'Attribute']);
        }

        // Check and create entity type table
        if (!Schema::hasTable('entity_type')) {
            $this->call('eav:migration', [
                'name'   => 'create_entity_type_table',
                '--type' => 'entity_type'
            ]);

            $this->call('make:model', ['name' => 'Entity']);
        }

        // Check and create entity attributes table
        if (!Schema::hasTable('entity_attribute')) {
            $this->call('eav:migration', [
                'name'   => 'create_entity_attribute_table',
                '--type' => 'entity_attribute'
            ]);

            $this->call('make:model', ['name' => 'EntityAttribute']);
        }

        // Create migration of model
        $this->call('eav:migration', ['name' => 'create_' . Str::snake($name) . '_entity_table']);

        foreach ($this->types as $type) {
            $migrationName = 'create_' . Str::snake($name) . '_entity_' . $type . '_table';
            $this->call(
                'eav:migration',
                [
                    'name'   => $migrationName,
                    '--type' => $type
                ]
            );
        }
    }
}
