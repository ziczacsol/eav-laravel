<?php

namespace Eav\Eloquents;

use App\Entities\EntityType;
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
    protected $table = 'attributes';

    public function entityTypes()
    {
        return $this->belongsToMany(EntityType::class, 'entity_attribute', 'attribute_id', 'entity_type_id');
    }
}
