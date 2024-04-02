<?php

namespace nailfor\Couchbase\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;
use nailfor\Couchbase\Eloquent\Relations\BelongsTo;
use nailfor\Couchbase\Query\QueryBuilder;

/**
 * Elasticsearch.
 *
 */
class Model extends BaseModel
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'couchbase';

    protected $primaryKey = '_id';

    public function newEloquentBuilder($query): Builder
    {
        /** @var QueryBuilder $query */
        return new Builder($query);
    }

    protected function newRelatedInstance($class)
    {
        return new $class;
    }

    public function newFromBuilder($attributes = [], $connection = null)
    {
        $key = Str::of($this->table)
            ->explode('.')
            ->last();
        $key = Str::replace('`', '', $key);
        $data = $attributes[$key] ?? $attributes;

        return parent::newFromBuilder($data, $connection);
    }

    protected function newBelongsTo(Builder $query, BaseModel $child, $foreignKey, $ownerKey, $relation)
    {
        return new BelongsTo($query, $child, $foreignKey, $ownerKey, $relation);
    }
}
