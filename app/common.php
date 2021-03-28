<?php
// +----------------------------------------------------------------------
// | mxAdmin
// +----------------------------------------------------------------------
// | 版权所有 2020~2050 福州目雪科技有限公司 [ http://www.muxue.com.cn ]
// +----------------------------------------------------------------------
// | 演示地址: http://demo.muxue.com.cn
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/muxue2020/mxAdmin
// +----------------------------------------------------------------------
// | Author: 明仔 <350656405@qq.com>    微信号：zlmlovem
// +----------------------------------------------------------------------

// 应用公共文件
if (!function_exists('list_to_tree')) {
    /**
     * 把返回的数据集转换成Tree
     * @param $list 要转换的数据集
     * @param bool $disabled 渲染下拉树xmSelect时，有子类不可选择，默认可选
     * @param string $pk
     * @param string $pid
     * @param string $children 有子类时添加children数组
     * @param int $root
     * @return array
     */
    function list_to_tree($list, $disabled = false, $pk='id', $pid = 'pid', $children = 'children', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }

            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$children][] =& $list[$key];
                        $disabled ? $parent['disabled'] = true : '';
                    }
                }
            }
        }
        return $tree;
    }
}

if (!function_exists('data_sign')) {
    /**
     * 数据签名认证
     * @param  array $data 被认证的数据
     * @return string       签名
     */
    function data_sign($data)
    {
        // 数据类型检测
        if (!is_array($data)) {
            $data = (array)$data;
        }
        ksort($data); // 排序
        $code = http_build_query($data); // url编码并生成query字符串
        $sign = sha1($code); // 生成签名
        return $sign;
    }
}

if (!function_exists('getAdminId')) {
    /**
     * 获取用户ID
     * @return mixed
     */
    function getAdminId()
    {
        $data = session('admin_info.admin_id');
        return $data;
    }
}

if (!function_exists('getDictDataId')) {
    /**
     * 获取字典数据
     * @return mixed
     */
    function getDictDataId($id)
    {
        $data = \app\mxadmin\model\DictData::where(['dict_id' => $id, 'status' => 1])->order('weight,id')->select();
        return $data;
    }
}

if (!function_exists('sysoplog')) {
    /**
     * 写入系统日志
     * @param $action
     * @param $content
     */
    function sysoplog($action, $content)
    {
        if (session('?admin_info')) {
            \app\mxadmin\model\OplogModel::create([
                'node' => strtolower(app('http')->getName()) . "/" . strtolower(request()->controller()) . "/" . strtolower(request()->action()),
                'geoip' => request()->ip(),
                'action' => $action,
                'content' => $content,
                'username' => session('admin_info.username')
            ]);
        }
    }
}
