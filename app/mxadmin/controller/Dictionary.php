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
declare (strict_types = 1);

namespace app\mxadmin\controller;

use app\mxadmin\AdminBase;
use app\mxadmin\model\Dict;
use app\mxadmin\model\DictData;
use think\exception\ValidateException;

class Dictionary extends AdminBase
{
    /**
     * 字典管理
     * @return \think\response\View
     */
    public function index()
    {
        return view();
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist()
    {
        $list = Dict::order('weight,id')->select();
        return $this->result($list);
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist_same($limit=15)
    {
        if (request()->isGet()) {
            $data = input('param.');
            $list = DictData::where('dict_id', $data['dict_id'])->order('weight,id')->paginate($limit);
            return $this->result($list);
        }
    }

    /**
     * 搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function serach()
    {
        if (request()->isGet()) {
            $data = input('param.name');
            $list = Dict::whereLike('name','%' . $data . '%')->order('weight,id')->select();
            return $this->result($list);
        }
    }

    /**
     * 搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function serach_same($limit=15)
    {
        if (request()->isGet()) {
            $data = input('param.name');
            $list = DictData::whereLike('name','%' . $data . '%')->order('weight,id')->paginate($limit);
            return $this->result($list);
        }
    }

    /**
     * 添加字典
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Dict');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            $result = Dict::create($data);
            if ($result == true) {
                return $this->success('字典添加成功');
            } else {
                return $this->error('字典添加失败');
            }
        }
    }

    /**
     * 添加字典项
     */
    public function add_same()
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'DictData');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            $result = DictData::create($data);
            if ($result == true) {
                return $this->success('字典项添加成功');
            } else {
                return $this->error('字典项添加失败');
            }
        }
    }

    /**
     * 修改字典
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Dict');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            $result = Dict::update($data, ['id' => $id]);
            if ($result == true) {
                return $this->success('字典修改成功');
            } else {
                return $this->error('字典修改失败');
            }
        }
    }

    /**
     * 修改字典项
     */
    public function edit_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'DictData');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            $result = DictData::update($data, ['id' => $id]);
            if ($result == true) {
                return $this->success('字典项修改成功');
            } else {
                return $this->error('字典项修改失败');
            }
        }
    }

    /**
     * 修改字典排序
     */
    public function edit_dictweight_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = Dict::update(['weight' => $data['weight']], ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 修改字典排序
     */
    public function edit_dictdataweight_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = DictData::update(['weight' => $data['weight']], ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 修改字典项状态
     */
    public function edit_state_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = DictData::update(['status' => $data['status']], ['id' => $id]);
            if ($result == true) {
                return $this->success($data['status'] ? '已启用' : '已禁用');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 删除字典
     */
    public function del($id)
    {
        if (request()->isPost()) {
            if ($id == 1) {
                return $this->error('系统字典禁止删除');
            }
            $result = Dict::destroy($id);
            DictData::where('dict_id', $id)->delete();
            if ($result == true) {
                return $this->success('字典及对应字典项删除成功');
            } else {
                return $this->error('字典及对应字典项删除失败');
            }
        }
    }

    /**
     * 删除字典项
     * @param $id
     */
    public function del_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }
            $result = DictData::destroy($ids);
            if ($result == true) {
                return $this->success('字典项删除成功');
            } else {
                return $this->error('字典项删除失败');
            }
        }
    }
}