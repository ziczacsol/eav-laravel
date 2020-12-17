<?php

namespace Ziczac\EavLaravel\Eloquents;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductsEntity.
 *
 * @package namespace App\Entities;
 */
class EntityType extends Model
{
    /**
     * Table name of EntityType
     *
     * @var string
     */
    protected $table = 'entity';

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'entity_attribute', 'entity_id', 'attribute_id');
    }
}
