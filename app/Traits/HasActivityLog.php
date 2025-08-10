<?php

namespace App\Traits;

use App\Services\ActivityLogger;

trait HasActivityLog
{
    protected static function bootHasActivityLog()
    {
        static::created(function ($model) {
            ActivityLogger::logCreate(
                $model->getTable(), 
                $model->id, 
                $model->toArray()
            );
        });

        static::updated(function ($model) {
            $oldData = $model->getOriginal();
            $newData = $model->getDirty();
            
            if (!empty($newData)) {
                ActivityLogger::logUpdate(
                    $model->getTable(),
                    $model->id,
                    $oldData,
                    $newData
                );
            }
        });

        static::deleted(function ($model) {
            ActivityLogger::logDelete(
                $model->getTable(),
                $model->id,
                $model->toArray()
            );
        });
    }

    public function logActivity($event, $changes = [], $description = null)
    {
        ActivityLogger::logCustom(
            $event,
            $this->getTable(),
            $this->id,
            $changes,
            $description
        );
    }
}