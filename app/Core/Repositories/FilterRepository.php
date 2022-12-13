<?php

namespace App\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class FilterRepository
{
    protected Builder $builder;
    protected array $filters = [
        'orderBy' => 'created_at',
        'orderDirection' => 'asc',
    ];

    public function __construct(private Model $model, private Request $request)
    {
        $this->builder = $this->model->newQuery();
        $this->reset();
    }

    public function reset()
    {
        foreach ($this->filters as $key => $default) {
            $this->request->merge([
                $key => $this->request->input($key, $this->request->cookie('filter' .$key, $default)),
            ]);
        }

        foreach ($this->request->toArray() as $key => $value) {
            if (is_array($value))
                continue;
            Cookie::queue('filter.'.$key, $value, 10);
        }
    }

    public function get()
    {
        return $this->where()->order()->paginate();
    }

    private function order()
    {
        $orderBy = $this->request->input('orderBy', 'created_at');
        $orderDirection = $this->request->input('orderDirection', 'ASC');

        if (is_array($orderBy)) {
            foreach ($orderBy as $column => $direction) {
                $this->builder->orderBy($column, $orderDirection);
            }
        } elseif ($orderBy && $orderDirection) {
            $this->builder->orderBy($orderBy, $orderDirection);
        }

        return $this;
    }

    private function where()
    {
        if ($this->request->input('whereAfter')) {
            foreach ($this->request->input('whereAfter') as $key => $values) {
                $this->builder->where(function ($query) use ($key, $values) {
                    $query->where($key, '<=', $values)
                        ->orWhereNull($key);
                });
            }
        }

        if ($this->request->input('where')) {
            $this->builder->where($this->request->input('where'));
        }

        if ($this->request->input('whereIn')) {
            foreach ($this->request->input('whereIn') as $key => $values) {
                $this->builder->whereIn($key, $values);
            }
        }

        if (is_array($this->request->input('whereNull'))) {
            foreach ($this->request->input('whereNull') as $column) {
                $this->builder->whereNull($column);
            }
        }

        if (is_array($this->request->input('whereNotNull'))) {
            foreach ($this->request->input('whereNotNull') as $column) {
                $this->builder->whereNotNull($column);
            }
        }

        if (is_array($this->request->input('whereHas'))) {
            foreach ($this->request->input('whereHas') as $relationship => $where) {
                $this->builder->whereHas($relationship, function ($query) use ($where) {
                    $query->where($where);
                });
            }
        }

        if (is_array($this->request->input('whereDoesntHave'))) {
            foreach ($this->request->input('whereDoesntHave') as $relationship => $where) {
                $this->builder->whereDoesntHave($relationship, function ($query) use ($where) {
                    $query->where($where);
                });
            }
        }

        if ($this->request->input('has')) {
            foreach ((array) $this->request->input('has') as $relationship) {
                $this->builder->has($relationship);
            }
        }

        if ($this->request->input('doesntHave')) {
            foreach ((array) $this->request->input('doesntHave') as $relationship) {
                $this->builder->doesntHave($relationship);
            }
        }

        if ($this->request->input('search') && isset($options['searchIn'])) {
            $this->builder->where(function ($query) use ($options) {
                foreach ($options['searchIn'] as $key => $columns) {
                    if (is_array($columns)) {
                        $query->orWhereHas($key, function ($query) use ($columns) {
                            $query->where(function ($query) use ($columns) {
                                foreach ($columns as $column) {
                                    $query->orWhere($column, 'like', '%'.$this->request->input('search').'%');
                                }
                            });
                        });
                    } else {
                        $query->orWhere($columns, 'like', '%'.$this->request->input('search').'%');
                    }
                }
            });
        }

        if (isset($options['addSelect'])) {
            foreach ((array) $options['addSelect'] as $addSelect) {
                $this->builder->addSelect($addSelect);
            }
        }

        return $this;
    }

    private function paginate()
    {
        return $this->builder->paginate(10);
    }
}
