<?php

declare (strict_types = 1);

namespace app\cms\controller;

use app\mxadmin\AdminBase;
use app\cms\model\CmsQrcode;

class Qrcode extends AdminBase
{
    /**
     * 列表管理
     * @return \think\response\View
     */
    public function index()
    {
        return view('', [
            'adtype' => getDictDataId(1),
        ]);
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist($limit=15)
    {
        $list = CmsQrcode::order('id desc')->paginate($limit);
        return $this->result($list);
    }

    /**
     * 搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function serach($limit=15)
    {
        if (request()->isGet()) {
            $data = input('param.');
            $serach = new CmsQrcode();
            if ($data['type'] != '') {
                $serach = $serach->where('type', $data['type']);
            }
            if ($data['title'] != '') {
                $serach = $serach->whereLike('title', '%' . $data['title'] . '%');
            }
            if ($data['status'] != '') {
                $serach = $serach->where('status', $data['status']);
            }
            $list = $serach->order('id desc')->paginate($limit);
            return $this->result($list);
        }
    }

    /**
     * 添加
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = CmsQrcode::create($data);

            if ($result == true) {
                return $this->success('数据添加成功');
            } else {
                return $this->error('数据添加失败');
            }
        }
    }

    /**
     * 修改
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = CmsQrcode::update($data, ['id' => $id]);

            if ($result == true) {
                return $this->success('数据修改成功');
            } else {
                return $this->error('数据修改失败');
            }
        }
    }

    /**
     * 修改状态
     */
    public function edit_state_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = CmsQrcode::update(['status' => $data['status']], ['id' => $id]);
            if ($result == true) {
                return $this->success($data['status'] ? '已启用' : '已禁用');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 修改排序
     */
    public function edit_weight_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = CmsQrcode::update(['weight' => $data['weight']], ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 删除
     */
    public function del($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }
            $result = CmsQrcode::destroy($ids);

            if ($result == true) {
                return $this->success('数据删除成功');
            } else {
                return $this->error('数据删除失败');
            }
        }
    }
}
