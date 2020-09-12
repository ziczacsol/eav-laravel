<?php

namespace App\Eav;

/**
 * Class AttributesTrait
 *
 */
trait AttributesTrait
{
    /**
     * Boot up Trait
     */
    public static function bootAttributesTrait()
    {
        static::addGlobalScope(app(GlobalScope::class));
        static::observe(app(EntityObserve::class));
    }

    /**
     * Return string for entity_type_code
     *
     * @return string
     */
    abstract public function entityTypeCode() : string;
}
