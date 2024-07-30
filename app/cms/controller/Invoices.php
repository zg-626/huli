<?php

declare (strict_types = 1);

namespace app\cms\controller;

use app\mxadmin\AdminBase;
use app\cms\model\Invoices as InvoicesModel;

class Invoices extends AdminBase
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
        $list = InvoicesModel::with('user')->order('id desc')->paginate($limit);
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
            $invoicesModel = new InvoicesModel();
            $nickname = $data['nickname'] ?? '';
            $status = $data['status'] ?? '';

            $list = $invoicesModel::hasWhere('user' , function ($query) use ($nickname) {
                $query->field('id,nickname');
                if (!empty($nickname)) {
                    $query->where('nickname', 'like', '%' . $nickname . '%');
                }
            })->with('user')
                ->where(function ($query) use ($status) {
                    if ($status === '0') {
                        $query->where('Invoices.status', 0);
                    } elseif ($status === '1') {
                        $query->where('Invoices.status', 1);
                    }
                    // You can add more conditions as needed
                })
                ->order('id desc')
                ->paginate($limit);


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
            $result = InvoicesModel::create($data);

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
            $result = InvoicesModel::update($data, ['id' => $id]);

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
            $result = InvoicesModel::update(['status' => $data['status']], ['id' => $id]);
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
            $result = InvoicesModel::update(['weight' => $data['weight']], ['id' => $id]);
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
            $result = InvoicesModel::destroy($ids);

            if ($result == true) {
                return $this->success('数据删除成功');
            } else {
                return $this->error('数据删除失败');
            }
        }
    }

    /**
     * 开票
     */
    public function open($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }
            $result = InvoicesModel::whereIn('id', $ids)->update(['status' => 1]);

            if ($result == true) {
                return $this->success('开票成功');
            } else {
                return $this->error('开票失败');
            }
        }
    }

    /**
     * 作废
     */
    public function cancel($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }
            $result = InvoicesModel::whereIn('id', $ids)->update(['status' => 0]);

            if ($result == true) {
                return $this->success('作废成功');
            } else {
                return $this->error('作废失败');
            }
        }
    }
}
