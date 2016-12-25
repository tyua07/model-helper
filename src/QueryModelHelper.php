<?php

namespace Yangyifan\Model;

trait QueryModelHelper
{
    use WhereModelHelper;

    /**
     * 解析查询接口
     *
     * @param $request
     * @return mixed
     */
    public static function getNewQuery($request)
    {
        $query = (new static())->newQuery();

        //解析查询
        if ( !empty($request['search'])) {
            $search = json_decode($request['search'], true);

            if ( count($search) > 0 ) {
                $query->multiwhere(static::getSearchMap($search));
            }
        }

        //解析 limit [指定返回记录的数量]
        if ( $request['limit'] > 0 ) {
            $query->take($request['limit']);
        }

        //解析 offset [指定返回记录的开始位置]
        if ( $request['offset'] > 0 ) {
            $query->skip($request['offset']);
        }

        //解析 orderBy [指定返回结果按照哪个属性排序，以及排序顺序]
        if ( !empty($request['sortby']) ) {
            $query->orderBy($request['sortby'], $request['order'] ? : 'asc');
        }

        //解析 page [指定第几页，以及每页的记录数]
        if ( $request['page'] > 0 && $request['per_page'] > 0 ) {
            $query->skip( ($request['page'] - 1) * $request['per_page'])->take($request['per_page']);
        }

        return $query;
    }

    /**
     * 组合搜索条件
     *
     * @param array $search
     * @return mixed
     */
    public static function getSearchMap(Array $search)
    {
        return $search;
    }



}
