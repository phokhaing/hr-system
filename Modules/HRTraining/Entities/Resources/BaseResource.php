<?php

namespace App\Entities;

use Illuminate\Http\Resources\Json\Resource;

class BaseResource extends Resource
{
    /**
     * @param string $attribute
     * @param null $object
     * @return mixed
     */
    public function locale(string $attribute, $object = null)
    {
        $attribute = $attribute.'_'.app('translator')->getLocale();

        if ($object) {
            return $object->$attribute;
        }
        return $this->$attribute;
    }
}