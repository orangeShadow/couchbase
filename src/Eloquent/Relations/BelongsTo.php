<?php

namespace nailfor\Couchbase\Eloquent\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class BelongsTo extends \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    public function addEagerConstraints(array $models)
    {
        // We'll grab the primary key name of the related models since it could be set to
        // a non-standard name and not "id". We will then construct the constraint for
        // our eagerly loading query so it returns the proper models from execution.
        $key = $this->ownerKey;

        $whereIn = $this->whereInMethod($this->related, $this->ownerKey);

        $this->whereInEager($whereIn, $key, $this->getEagerModelKeys($models));
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        if (static::$constraints) {
            // For belongs to relationships, which are essentially the inverse of has one
            // or has many relationships, we need to actually query on the primary key
            // of the related models matching on the foreign key that's on a parent.
            $this->query->where($this->ownerKey, '=', $this->getForeignKeyFrom($this->child));
        }
    }

    protected function getRelatedKeyFrom(Model $model)
    {
        $key = $this->ownerKey === $model->getKeyName() ? $model->getKeyNameAlias() : $this->ownerKey;

        return Arr::get($model, $key);
    }

    /**
     * Get the value of the model's foreign key.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    protected function getForeignKeyFrom(Model $model)
    {
        return Arr::get($model, $this->foreignKey);
    }
}
