<?php


namespace Eav;


class Definitions
{
    const ATTRIBUTE_TABLE = 'attributes';
    const ENTITY_TYPE_TABLE = 'entity';
    const ENTITY_ATTRIBUTE_TABLE = 'entity_attribute';

    const DATA_TYPES = [
        'integer' => 'int',
        'string' => 'varchar',
        'text' => 'text',
        'boolean' => 'boolean',
        'datetime' => 'datetime',
        'decimal' => 'decimal',
    ];
}