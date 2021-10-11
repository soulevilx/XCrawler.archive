<?php

namespace App\Core\Http\Controllers;

use App\Core\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    protected function _index(string $model, Request $request, array $options = null): \Illuminate\Pagination\LengthAwarePaginator
    {
        $orderDir = $request->input('orderDir', 'DESC');
        $orderBy = $request->input('orderBy', 'created_at');
        $perPage = $request->input(
            'perPage',
            ApplicationService::getConfig('core', 'pagination_perpage', config('core.pagination.per_page'))
        );

        $baseQuery = $model::orderBy($orderBy, $orderDir);
        if ($request->input('whereIn')) {
            foreach ($request->input('whereIn') as $key => $values) {
                $baseQuery->whereIn($key, $values);
            }
        }

        if ($request->input('whereHas')) {
            foreach ($request->input('whereHas') as $relationship) {
                $baseQuery->whereHas($relationship);
            }
        }

        if ($request->input('keyword') && isset($options['searchIn'])) {
            $baseQuery->where(function ($query) use ($request, $options) {
                foreach ($options['searchIn'] as $key => $column) {
                    $query->orWhere($column, 'like', '%' . $request->input('keyword') . '%');
                }
            });
        }

        return $baseQuery->paginate($perPage);
    }
}
