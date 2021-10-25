<?php

namespace Infrastructure\Database\Traits;

use Uuid;

trait Uuids
{

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if(!$model->{$model->getKeyName()}) {
                $model->{$model->getKeyName()} = strtoupper(Uuid::generate());
            }
        });
    }
}