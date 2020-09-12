<?php

namespace App\Eav;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;

class GlobalScope implements Scope
{
    /**
     * Connector character Convention
     *
     * @var string
     */
    private $connector;

    /**
     * Origin query
     *
     * @var Illuminate\Database\Query\Builder
     */
    protected $originQuery;

    /**
     * Origin columns
     *
     * @var array
     */
    protected $columns;

    /**
     * Origin where conditions
     *
     * @var array
     */
    protected $wheres;

    /**
     * Flag for build query
     *
     * @var bool
     */
    protected $isSelectAll;

    public function __construct()
    {
        $this->connector = '_entity_';
    }

    /**
     * Apply global scope query
     *
     * @param Illuminate\Database\Eloquent\Builder
     * @param Illuminate\Database\Eloquent\Model
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        $this->originQuery = $builder->getQuery();
        $this->columns     = $this->originQuery->columns;
        $this->wheres      = $this->originQuery->wheres;
        $this->isSelectAll = empty($this->columns);

        $builder->setQuery($this->getAttributesValues($model));
    }

    protected function getAttributesValues(Model $model) : Query
    {
        $newQuery = $this->originQuery->cloneWithout(['columns','wheres']);

        if ($this->isSelectAll) $newQuery->selectRaw($model->getTable().'.*');

        $attributes     = $model->getEavAttributes();

        foreach($attributes as $attribute) {
            $this->joinRelationShip($newQuery, $attribute, $model)->addWhereCondidtions($newQuery, $attribute);
        }

        // Where condition with origin column from entity
        // after unset in addWhereConditions
        $table = $model->getTable();
        if(!empty($this->wheres)) {
            foreach($this->wheres as $where) {
                $column = explode('.', $where['column']);
                if (!empty($column) && $column[0] === $table) {
                    $newQuery->where($where['column'], $where['operator'], $where['value']);
                } else {
                    $newQuery->where($table.'.'.$where['column'], $where['operator'], $where['value']);
                }
            }
        }

        return $newQuery;
    }

    /**
     * Join related tables and select as attribute
     *
     * @param Illuminate\Database\Query\Builder as Query
     * @param Illuminate\Database\Eloquent\Model
     * @param Illuminate\Database\Eloquent\Model
     *
     * @return this
     */
    protected function joinRelationShip(Query $query, Model $attribute, Model $model)
    {
        $attributeCode = $attribute->attribute_code;
        $table         = join($this->connector, [$model->entityTypeCode(), $attribute->backend_type]);
        $alias         = 'tmp_' . $attributeCode;

        $query->leftJoin($table . ' as ' . $alias, function($join) use ($alias, $model, $attribute) {
            $join->on($alias.'.entity_id', $model->getTable().'.id')
                    ->where($alias.'.'.'attribute_id', $attribute->id);
        })->selectRaw($alias.'.value as '.$attributeCode);

        return $this;
    }

    /**
     * Where from attributes
     *
     * @param Illuminate\Database\Query\Builder as Query
     * @param Illuminate\Database\Eloquent\Model
     *
     * @return this
     */
    protected function addWhereCondidtions(Query $query, $attribute)
    {
        $attributeCode = $attribute->attribute_code;
        $alias         = 'tmp_' . $attributeCode;

        if(!empty($this->wheres)) {
            $index = array_search($attributeCode, array_column($this->wheres, 'column'));

            if($index !== false) {
                $query->where($alias.'.value', $this->wheres[$index]['operator'], $this->wheres[$index]['value']);
                unset($this->wheres[$index]);
            }
        }

        return $this;
    }
}
