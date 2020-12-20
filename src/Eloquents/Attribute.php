<?php

namespace Eav\Eloquents;

use Eav\Eloquents\EntityType;
use Eav\Definitions;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductsEntity.
 *
 * @package namespace App\Entities;
 */
class Attribute extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = Definitions::ATTRIBUTE_TABLE;

    public function entityTypes()
    {
        return $this->belongsToMany(EntityType::class, 'entity_attribute', 'attribute_id', 'entity_id');
    }
}
