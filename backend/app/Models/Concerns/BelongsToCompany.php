<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany(): void
    {
        static::creating(function ($model) {
            if (!$model->company_id && app()->bound('company_id')) {
                $model->company_id = app('company_id');
            }
        });

        static::addGlobalScope('company', function (Builder $builder) {
            if (app()->bound('company_id')) {
                $builder->where(
                    $builder->getModel()->getTable() . '.company_id',
                    app('company_id')
                );
            }
        });
    }
}