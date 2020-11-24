<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;


/**
 * Class QueryHelper
 * @package App\Helpers
 */
class QueryHelper
{
    /**
     * 构建分页查询条件
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @return Builder
     */
    public function pagination($query)
    {
        $request = app('request');
        //分页参数
        $start = $request->input('iDisplayStart');
        $limit = $request->input('iDisplayLength');
        $limit = $limit ?: 20;

        $queryTotal = clone $query;
        //分页数据
        $list = $query->skip($start)->take($limit)->get();
        $data = [];
        $data['recordsFiltered'] = $data['recordsTotal'] = $queryTotal->count();
        $data['data'] = $list;

        return $data;
    }

    /**
     * 构建分页查询条件
     * @param Builder|\Illuminate\Database\Query\Builder $query
     * @return Builder
     */
    public static function page($query)
    {
        return (new static())->pagination($query);
    }


    /**
     * 构建分页查询条件
     * @param Builder $query
     * @param $callback
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function pageCallback($query, $callback)
    {
        $request = app('request');
        //分页参数
        $start = $request->input('iDisplayStart');
        $limit = $request->input('iDisplayLength');
        $limit = $limit ?: 20;

        $queryTotal = clone $query;
        //分页数据
        $list = $query->skip($start)->take($limit)->get()->toArray();


        foreach ($list as &$item) {
            $callback($item);
        }


        $data = [];
        $data['recordsFiltered'] = $data['recordsTotal'] = $queryTotal->count();
        $data['data'] = $list;

        return $data;
    }


    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([new static(), $name], $arguments);
    }
}