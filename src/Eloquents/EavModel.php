<?php

namespace Eav\Eloquents;

use Eav\AttributesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class EavModel
 * The base class for model can extends to using EAV
 */
class EavModel extends Model
{
    use AttributesTrait;

    /**
     * Create
     *
     * @param array $attributes
     */
    public function create(array $attributes)
    {
        DB::beginTransaction();

        try {
            $this->fill($attributes);
            $entity_id = DB::table($this->table)->insertGetId($this->getAttributes());

            $this->getEavAttributes()->each(function ($attribute) use ($attributes, $entity_id) {
                if ( !empty($attributes[$attribute->attribute_code])) {
                    $value = $attributes[$attribute->attribute_code];

                    // Store file and return link
                    if ($attribute->frontend_input == 'file') {
                        $value = Storage::putFile('images', $value);
                    }

                    // Store attribute type
                    DB::table($this->table . '_' . $attribute->backend_type)
                        ->insert([
                            'attribute_id' => $attribute->id,
                            'entity_id'    => $entity_id,
                            // TODO: get store id when create entity attribute
                            // 'store_id'     => $this->getStore(),
                            'value'        => $value,
                        ]);
                }
            });
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return false;
        }

        return $this->find($entity_id);
    }

    /**
     * Implement abstract function
     * to set entity_type_code
     *
     * @return string
     */
    public function entityTypeCode(): string
    {
        return "";
    }

    /**
     * Get attributes
     *
     * @return mixed
     */
    public function getEavAttributes()
    {
        $entityTypeCode = $this->entityTypeCode();

        return Attribute::whereHas('entityTypes', function (Builder $query) use ($entityTypeCode) {
            $query->where('entity_type_code', $entityTypeCode);
        })->where('status', 1)->get();
    }

    public function getAttributes()
    {
        if (empty($this->getAttribute(static::CREATED_AT))) {
            $this->attributes['created_at'] = Carbon::now();
        }

        if (empty($this->getAttribute(static::UPDATED_AT))) {
            $this->attributes['updated_at'] = Carbon::now();
        }

        return parent::getAttributes();
    }

    public function fetch($attributes, $id)
    {
        DB::beginTransaction();

        try {
            $entity = $this->find($id);

            $entity->fill($attributes)->save();

            $this->getEavAttributes()->each(function ($attribute) use ($attributes, $entity) {
                if ( !empty($attributes[$attribute->attribute_code])) {
                    $value = $attributes[$attribute->attribute_code];

                    // Store file and return link
                    if ($attribute->frontend_input == 'file') {
                        $value = Storage::putFile('images', $value);
                    }

                    // Store attribute type
                    DB::table($this->table . '_' . $attribute->backend_type)
                        ->updateOrInsert(
                            [
                                'attribute_id' => $attribute->id,
                                'entity_id'    => $entity->id,
                            ],
                            [
                                'value' => $value,
                            ]
                        );
                }
            });
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return false;
        }

        return $this->find($id);
    }
}
