<?php

namespace Yangyifan\Model;

interface QueryInterface
{
    /**
     * 获得列表显示字段
     *
     * @return mixed
     */
    public static function getListColumns();

    /**
     * 组合搜索条件
     *
     * @param array $request
     * @return mixed
     */
    public static function getSearchMap(Array $request);
}
