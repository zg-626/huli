<?php

declare (strict_types = 1);

namespace app\mxadmin\controller;

use app\mxadmin\AdminBase;
use app\mxadmin\model\OplogModel;
use think\facade\Db;

class Oplog extends AdminBase
{
    /**
     * 日志管理
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
    public function datalist($limit=15)
    {
        $list = OplogModel::order('id', 'desc')->paginate($limit);
        $ip2region = new \Ip2Region();
        foreach ($list as $item) {
            $result = $ip2region->btreeSearch($item['geoip']);
            $item['isp'] = isset($result['region']) ? $result['region'] : '';
            $item['isp'] = str_replace(['中国|', '0|', '内网IP|', '|'], '', $item['isp']);
        }
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
            $serach = new OplogModel();
            if ($data['username'] != '') {
                $serach = $serach->whereLike('username', '%' . $data['username'] . '%');
            }
            $list = $serach->order('id', 'desc')->paginate($limit);
            $ip2region = new \Ip2Region();
            foreach ($list as $item) {
                $result = $ip2region->btreeSearch($item['geoip']);
                $item['isp'] = isset($result['region']) ? $result['region'] : '';
                $item['isp'] = str_replace(['中国|', '0|', '内网IP|', '|'], '', $item['isp']);
            }
            return $this->result($list);
        }
    }

    /**
     * 删除日志
     * @param $id
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
            $result = OplogModel::destroy($ids);
            if ($result == true) {
                return $this->success('删除成功');
            } else {
                return $this->error('删除失败');
            }
        }
    }

    /**
     * 清空所有日志
     */
    public function delall()
    {
        if (request()->isPost()) {
            $result = Db::name('oplog')->delete(true);
            if ($result == true) {
                return $this->success('一键清空成功');
            } else {
                return $this->error('目前没有数据可清空');
            }
        }
    }
}
