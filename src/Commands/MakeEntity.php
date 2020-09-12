<?php

namespace Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
        dd($name);

        if ( !is_null($this->option('types'))) {
            $this->types = explode(',', $this->option('types'));
        }

        // Create model
        $this->call('eav:model', ['name' => $name]);

        // Create controller
        $this->call('make:controller', [
            'name'    => "{$name}Controller",
            '--model' => "Entities/{$name}",
            '--api'   => true,
        ]);

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
