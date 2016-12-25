<?php

namespace Yangyifan\Model;

use Illuminate\Database\Query\Builder;

trait WhereModelHelper
{
    /**
     * 多条件查询where
     *
     * @param Builder $query
     * @param array $arr
     * @return mixed
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public function scopeMultiwhere($query, $arr)
    {
        if (empty($arr) || !is_array($arr)) {
            return $query;
        }

        foreach ($arr as $key => $value) {
            // 如果为 null
            if ( is_null($value) ) {
                $this->parseQuery($key, 'null', null, $query);
            } else if (is_array($value)) {
                // 解析query查询
                $this->parseQuery($key, key($value), $value, $query);

            } else {
                $query->where($key, $value);
            }
        }

        unset($key);
        unset($value);

        return $query;
    }

    /**
     * 解析query查询
     *
     * @param string $schema       搜索字段
     * @param string $type         搜索类型
     * @param string $value        组合条件
     * @param Builder $query        $query
     * @return mixed
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    protected function parseQuery($schema, $type, $value, $query)
    {
        switch(strtolower($type)){
            case 'in':
                /**
                 * [ 'schema' =>  ['in' => [1, 2]] ]
                 */
                $query->whereIn($schema, array_values($value[$type]));
                break;
            case 'not in':
                /**
                 * [ 'schema' =>  ['not in' => [1, 2]] ]
                 */
                $query->whereNotIn($schema, array_values($value[$type]));
                break;
            case 'between':
                /**
                 * [ 'schema' =>  ['between' => [$start, $end] ] ]
                 */
                $query->whereBetween($schema, array_values($value[$type]));
                break;
            case 'not between':
                /**
                 * [ 'schema' =>  ['not between' => [$start, $end] ] ]
                 */
                $query->whereNotBetween($schema, array_values($value[$type]));
                break;
            case 'null':
                /**
                 * [ 'schema' =>  null ]
                 */
                $query->whereNull($schema);
                break;
            case 'or':
                /**
                 * [ 'schema' =>  ['or' => ['=' => 1]] ]
                 */

                $value  = $value[$type];
                $type   = key($value);

                $query->orWhere($schema, $type, $value[$type]);
                break;
            default:

                /**
                 *
                 * [ 'schema' =>  ['like' => '%11%'] ]
                 * [ 'schema' =>  ['=' => 1] ]
                 * [ 'schema' =>  ['>' => 1] ]
                 * [ 'schema' =>  ['>=' => 1] ]
                 * [ 'schema' =>  ['<' => 1] ]
                 * [ 'schema' =>  ['<=' => 1] ]
                 *
                 */

                $query->where($schema, $type ? : '=', current($value));
                break;
        }
        return $query;
    }
}
