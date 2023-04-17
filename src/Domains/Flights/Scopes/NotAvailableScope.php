<?php

namespace Domain\Flights\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class NotAvailableScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('status', '!=', FlightStatus::NOT_AVAILABLE);
    }
}
