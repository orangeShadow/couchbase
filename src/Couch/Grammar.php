<?php

namespace nailfor\Couchbase\Couch;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar as BaseGrammar;

class Grammar extends BaseGrammar
{
    protected function wrapValue($value)
    {
        if ($value !== '*') {
            return str_replace('"', '""', $value);
        }

        return $value;
    }

    protected function whereIn(Builder $query, $where)
    {
        if (!empty($where['values'])) {
            return $this->wrapValue($where['column']) . ' in [' . $this->parameterize($where['values']) . ']';
        }

        return '0 = 1';
    }
}
