<?php

namespace Eav\Eloquents;

use Eav\Definitions;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductsEntity.
 *
 */
class EntityType extends Model
{
    /**
     * Table name of EntityType
     *
     * @var string
     */
    protected $table = Definitions::ENTITY_TYPE_TABLE;

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'entity_attribute', 'entity_id', 'attribute_id');
    }
}
