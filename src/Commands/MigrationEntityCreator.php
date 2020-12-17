<?php

namespace Ziczac\EavLaravel\Commands;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Filesystem\Filesystem;

class MigrationEntityCreator extends MigrationCreator
{
    const ENTITY_VALUE = [
        'int'      => '$table->integer(\'value\');',
        'varchar'  => '$table->string(\'value\', 255);',
        'text'     => '$table->text(\'value\');',
        'boolean'  => '$table->boolean(\'value\');',
        'datetime' => '$table->dateTime(\'value\', 0);',
        'decimal'  => '$table->decimal(\'value\', 8, 2);',
    ];

    /**
     * Entity type
     *
     * @var $type
     */
    protected $type;

    public function __construct(Filesystem $filesystem, $customPath = null)
    {
        // FIXME https://github.com/laravel/framework/issues/31978
        parent::__construct($filesystem, $customPath);
    }

    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function stubPath()
    {
        return __DIR__ . '/Stubs/Migration';
    }

    /**
     * Get the migration stub file.
     *
     * @param string|null $table
     * @param bool $create
     *
     * @return string
     */
    protected function getStub($table, $create)
    {
        if (is_null($table)) {
            return $this->files->get($this->stubPath() . '/blank.stub');
        }

        if ($this->type) {
            if ($this->type == 'attribute') {
                $stub = 'create-attribute.stub';
            } elseif ($this->type == 'entity_type') {
                $stub = 'create-entity-type-table.stub';
            } elseif ($this->type == 'entity_attribute') {
                $stub = 'create-entity-attribute-table.stub';
            } else {
                $stub = 'create-entity-type.stub';
            }
        } else {
            $stub = $create ? 'create-entity.stub' : 'update-entity.stub';
        }

        return $this->files->get($this->stubPath() . "/{$stub}");
    }

    public function setEntityType($type)
    {
        $this->type = $type;
    }

    public function populateStub($name, $stub, $table)
    {
        if ( !is_null($this->type)) {
            $stub = str_replace('$VALUE', $this->getEntityValue(), $stub);
        }

        return parent::populateStub($name, $stub, $table);
    }

    private function getEntityValue()
    {
        return self::ENTITY_VALUE[$this->type] ?? '';
    }
}
