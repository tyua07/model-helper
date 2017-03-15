<?php

namespace Yangyifan\Model;

use Closure;
use MongoDB\BSON\ObjectID;
use Yangyifan\Library\UtilityLibrary;

trait TransFormModelHelper
{
    /**
     * 需要转换的字段
     *
     * @var array
     */
    protected $transFields;

    /**
     * 获得转换的字段
     *
     * @author @author yangyifan <yangyifanphp@gmail.com>
     * @return array
     */
    public function getTransFields()
    {
        return $this->transFields;
    }

    /**
     *  获取 transFormFields 之前需要执行的方法
     *
     * @author @author yangyifan <yangyifanphp@gmail.com>
     * @return Closure
     */
    protected function getTransFormBeforeEvent()
    {
        return function(){};
    }

    /**
     * 根据 $transFields 转换字段
     *
     * @param array|null $transFields
     * @return array
     */
    public function transFormFields($transFields = null)
    {
        $transFields = is_null($transFields) ? $this->getTransFields() : $transFields;

        if ( UtilityLibrary::isArray($transFields) == false ) {
            return [];
        }

        // 判断 transFormFields 之前需要执行的方法, 如果有则触发事件。
        $this->getTransFormBeforeEvent() instanceof Closure && call_user_func($this->getTransFormBeforeEvent());

        $data = [];

        foreach ( $transFields as $fieldKey => $fieldValue ) {
            $data[$fieldKey] =
                !is_array($fieldValue)
                && $this->getAttribute($fieldValue)
                && $this->getOriginal($fieldKey)
                    ? $this->getAttribute($fieldValue)
                    :   ( $this->hasGetMutator($fieldKey)
                    ? $this->mutateAttribute($fieldKey, $this->parseFields($fieldValue) )
                    : $this->getAttribute($fieldValue)
                );
        }

        unset($fieldKey);
        unset($fieldValue);

        return $data;
    }

    /**
     * 解析当前模型对应的字段的值
     *
     * @param $field
     * @return array
     */
    protected function parseFields($field)
    {
        if ( is_array($field) ) {

            array_walk($field, function(&$item){
                $item = $this->getOriginal($item);
            });

            return $field;
        }

        $field = $this->getOriginal($field);

        return $field instanceof ObjectID ? static::toStringId($field) : $field;
    }
}
