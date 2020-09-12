<?php

namespace App\Eav;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Eav\Eloquents\Attribute;

/**
 * Class EavModel
 */
class EavModel
{
    /**
     * Connector character Convention
     *
     * @var string
     */
    private $connector;

    public function __construct()
    {
        $this->connector = '_entity_';
    }

    /**
     * Get attributes of model with value
     *
     * @param Illuminate\Database\Eloquent\Model
     *
     * @return array
     */
    public function getAttributesValue(Model $model) : array
    {
        $attributes     = $model->getEavAttributes()->toArray();
        $data           = [];

        foreach($attributes as $attribute){
            $value = $this->getValue($model, $attribute);

            $data[$attribute['attribute_code']] = $value;
        }

        return $data;
    }

    /**
     * Get value of an atrribute
     *
     * @param Illuminate\Database\Eloquent\Model
     * @param array
     *
     * @return mixed
     */
    protected function getValue(Model $model, array $attribute)
    {
        $table  = join($this->connector, [$model->entityTypeCode(), $attribute['backend_type']]);
        $result = DB::table($table)
                    ->where('entity_id', $model->id)
                    ->where('attribute_id', $attribute['id'])
                    ->first();

        if($result){
            return $result->value;
        }

        return null;
    }
}
