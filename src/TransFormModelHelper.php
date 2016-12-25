<?php

namespace Yangyifan\Model;

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

        $data = [];

        foreach ( $transFields as $fieldKey => $fieldValue ) {
            $data[$fieldKey] =
                !is_array($fieldValue)
                && $this->getAttribute($fieldValue)
                && $this->getOriginal($fieldKey)
                    ? $this->getAttribute($fieldValue)
                    :   ( $this->hasGetMutator($fieldKey)
                            ? $this->mutateAttribute($fieldKey, $this->parseFields($fieldValue) )
                            : ''
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
                $item = $this->{$item};
            });
            return $field;
        }

        return $this->{$field};
    }
}
