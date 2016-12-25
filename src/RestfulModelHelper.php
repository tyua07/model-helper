<?php

namespace Yangyifan\Model;

use Illuminate\Support\Collection;

trait RestfulModelHelper
{
    /**
     * 获得列表数据
     *
     * @return array
     */
    public static function getAll($request)
    {
        if (static::all()->isEmpty()) {
            $collection = collect();
        } else {
            $collection = static::getNewQuery($request)->get();
        }

        return [
            'data'    => static::mergeData($collection)->toArray(),
            'columns' => static::getListColumns(),
        ];
    }

    /**
     * 组合数据
     *
     * @param Collection $collection
     * @return mixed
     */
    protected static function mergeData(Collection $collection)
    {
        return $collection;
    }

    /**
     * 获得数据
     *
     * @param $id
     * @return mixed
     */
    public static function show($id)
    {
        $moel = static::find($id);

        return  !empty($moel) ? $moel->toArray() : [];
    }

    /**
     * 更新数据
     *
     * @param $updataData
     * @param $id
     */
    public static function updateData($updataData, $id)
    {
        $moel = static::findOrFail($id);

        $moel->update($updataData);

        $moel->save();

        return $moel;
    }

    /**
     * 创建数据
     *
     * @param $data
     * @return array|bool
     */
    public static function store($data)
    {
        $model = static::create($data);

        return $model ? static::find($model->id) : false;
    }

    /**
     * 软删除数据
     *
     * @param array|int $id
     * @return mixed
     */
    public static function destroy($id)
    {
        $model = static::findOrFail($id);
        $model->delete();

        return $model->trashed();
    }
}
