<?php
namespace App\Helpers;
/**
 * Class CategoryHelper
 * @package App\Helpers
 */
final class CategoryHelper
{
    /**
     * 单次循环返回无限极分类嵌套
     * @param array $data 操作的数组
     * @param string $columnPri 唯一键名，如果是表则是表的主键
     * @param string $columnPid 父ID键名
     * @return array
     */
    static function region_tree($data, $columnPri, $columnPid)
    {
        if (empty($data)) {
            return array();
        }
        usort($data, function ($prev, $next) use($columnPid ) {
            return self::CStrCmp($prev[$columnPid], $next[$columnPid]);
        });

        foreach ($data as $value) {
            $parent_id = $value[$columnPid];
            $pri_id = $value[$columnPri];
            if (isset(${$pri_id})) {
                $value['_data'] = isset($value['_data']) ? array_merge($value['_data'], ${$pri_id}) : ${$pri_id};
                unset(${$pri_id});
            }
            ${$parent_id}[] = $value;
        }
        unset($data, $key, $value, $columnPid, $columnPri, $pri_id);
        $dataArray = ${$parent_id}[0];
        return $dataArray;
    }


    /**
     * 无限递归关联函数
     * 兼容5.3-写的
     * @param $a
     * @param $b
     * @return int
     */
    private static function CStrCmp($a, $b)
    {
        if ($a == $b) return 0;
        return $a > $b ? -1 : 1;
    }

}