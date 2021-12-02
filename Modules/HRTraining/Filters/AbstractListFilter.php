<?php

namespace Modules\HRTraining\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractListFilter
{
    /** @var List */
    protected $list;

    /** @var Builder  The Eloquent builder. */
    protected $builder;

    /** @var array Registered filters to operate upon. */
    protected $filters = [];

    /**
     * Create a new filter instance.
     *
     * @param $list
     */
    public function __construct($list)
    {
        $this->list = $list;
    }

    /**
     * Apply the filters.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->list as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }
}
