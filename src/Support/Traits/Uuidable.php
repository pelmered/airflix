<?php
namespace Support\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

trait Uuidable
{
    public static function find($value, $columns = ['*']): ?self
    {
        if ($value instanceof static) {
            return $value->refresh();
        }

        if (Uuid::isValid($value)) {
            return static::findByUuid($value, $columns);
        }

        if (is_array($value) || $value instanceof Arrayable) {
            return (new static())->findMany($value, $columns);
        }

        return (new static())->find($value, $columns);
    }

    public static function findByUuid($uuid, $columns = ['*'], $withTrashed = false): ?self
    {
        return Cache::remember(self::getTableName().'_'.$uuid, 60, function () use ($withTrashed, $uuid, $columns) {
            return self::where(static::uuidColumn(), '=', $uuid)
                ->when($withTrashed, function ($query) {
                    return $query->withTrashed();
                })
                ->first($columns);
        });
    }

    public static function getTableName(): string
    {
        return (new static())->getTable();
    }

    /**
     * The name of the column that should be used for the UUID.
     *
     * @return string
     */
    public static function uuidColumn()
    {
        return (new static())->primaryKey ?? 'uuid';
    }

    public function getIdAttribute()
    {
        if (array_key_exists(static::uuidColumn(), $this->attributes)) {
            return $this->attributes[static::uuidColumn()];
        }

        return null;
    }

    /**
     * Scope queries to find by UUID.
     *
     * @param  Builder  $query
     * @param  string   $uuid
     *
     * @return Builder
     */
    public function scopeWhereUuid($query, $uuid): Builder
    {
        /*
        if ($this->hasCast(static::uuidColumn())) {
            $uuid = Str::uuid()->fromString($uuid)->getBytes();
        }
        */
        return $query->where(static::uuidColumn(), $uuid);
    }

    public function getKeyName()
    {
        return static::uuidColumn();
    }

    public function getKeyType()
    {
        return 'string';
    }

    protected static function bootUuidable(): void
    {
        static::saving(function (Model $model): void {
            if (! $model->{$model->getKeyName()}) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return mixed
     */
    protected function castAttribute($key, $value): mixed
    {
        if ($key === static::uuidColumn() && ! is_null($value)) {
            //return Str::uuid()->fromBytes($value)->toString();
            return (string) $value;
        }

        return parent::castAttribute($key, $value);
    }
}
