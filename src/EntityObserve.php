<?php

namespace Eav;

use Eav\EavModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;

/**
 * Class EntityObserve
 */
class EntityObserve
{
    /**
     * @var Eav\EavModel
     */
    private static $eavModel;

    /**
     * @var Illuminate\Contracts\Events\Dispatcher
     */
    private $dispatcher;

    public function __construct(EavModel $eavModel, Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        static::$eavModel  = $eavModel;
    }

}
