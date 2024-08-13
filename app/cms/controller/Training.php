<?php

declare (strict_types = 1);

namespace app\cms\controller;

use app\cms\model\TrainingSign;
use app\mxadmin\AdminBase;
use app\cms\model\Training as TrainingModel;
use app\cms\model\CmsCategory;
use app\mxadmin\model\UserModel;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\Filesystem;
use think\Request;

class Training extends AdminBase
{
    /**
     * 表单弹窗页面
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function form()
    {
        return view('',[
            'trainingtype' => getDictDataId(6),
        ]);
    }

    /**
     * 学习班管理
     * @return \think\response\View
     */
    public function index()
    {
        return view('', [
            'trainingtype' => getDictDataId(6),
        ]);
    }

    /**
     * 报名管理
     * @return \think\response\View
     */
    public function sign()
    {
        return view('sign');
    }

    /**
     * 学习管理
     * @return \think\response\View
     */
    public function study($id)
    {
        $userlist = UserModel::alias('u')
            ->join('training_sign ts', 'ts.user_id = u.id')
            ->join('training t', 't.id = ts.training_id')
            ->join('cms_category c', 'c.id = u.d_id')
            ->where('ts.training_id', $id)
            ->field('u.*,ts.create_time as sign_time,t.title as training_title,t.study_time,c.name as departmentname')
           ->select();
        return view('study',['userlist'=>$userlist]);
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist($limit=15)
    {
        $list = TrainingModel::with(['admin', 'typename'])->withCount(['signups'])->order('weight asc,id desc')->paginate($limit);
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
            $serach = new TrainingModel();
            if ($data['type'] != '') {
                $serach = $serach->where('type', $data['type']);
            }
            if ($data['title'] != '') {
                $serach = $serach->whereLike('title', '%' . $data['title'] . '%');
            }
            if ($data['status'] != '') {
                $serach = $serach->where('status', $data['status']);
            }
            $list = $serach->with(['admin', 'typename'])-> order('weight asc,id desc')->paginate($limit);
            return $this->result($list);
        }
    }

    /**
     * 添加学习班
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Article');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            $data['admin_id'] = getAdminId();

            $result = TrainingModel::create($data);
            if ($result == true) {
                return $this->success('添加成功');
            } else {
                return $this->error('添加失败');
            }
        }
    }

    /**
     * 修改学习班
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Article');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }

            $result = TrainingModel::update($data, ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
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
            $result = TrainingModel::update(['status' => $data['status']], ['id' => $id]);
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
            $result = TrainingModel::update(['weight' => $data['weight']], ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 删除学习班
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
            $result = TrainingModel::destroy($ids);
            if ($result == true) {
                return $this->success('删除成功');
            } else {
                return $this->error('删除失败');
            }
        }
    }

    /**
     * 返回报名记录Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist_sign($id, int $limit=15)
    {
        /*$list = MsgReadModel::with('user')->where('m_id',cache('m_id'))->order('id desc')->paginate($limit);
        return $this->result($list);*/
        $notificationId = $id;
        // 查询所有用户及其阅读情况
        /*$users = UserModel::with(['trainingSigns' => function ($query) use ($notificationId) {
            $query->where('training_id', $notificationId)->with('training');
        },'hospital'])
            ->paginate($limit);
        // 遍历用户及其报名情况
        foreach ($users as $user) {
            $user->sign_status = 0;
            $user->sign_time = '';
            $user->training_title = '';
            $user->study_time = '';
            foreach ($user->trainingSigns as $trainingSign) {
                $user->sign_status = 1;
                $user->training_title = $trainingSign->training->title;
                $user->study_time = $trainingSign->training->study_time;
                if ($trainingSign->create_time) {
                    $user->sign_time=$trainingSign->create_time;
                }
            }
        }*/
        $users = UserModel::alias('u')
            ->join('training_sign ts', 'ts.user_id = u.id')
            ->join('training t', 't.id = ts.training_id')
            ->join('cms_category c', 'c.id = u.d_id')
            ->where('ts.training_id', $notificationId)
            ->field('u.*,ts.create_time as sign_time,t.title as training_title,t.study_time,c.name as departmentname')
            ->paginate($limit);
        if(!$users->isEmpty()){
            foreach ($users as $user){
                $user->sign_status = 1;
                $user->sign_time=date('Y-m-d',$user->sign_time);
                $user->study_time=date('Y-m-d',$user->study_time);

            }
        }
        return $this->result($users);
    }

    /**
     * 返回报名记录Json格式的搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist_sign_search($id, int $limit=15)
    {
        /*$list = MsgReadModel::with('user')->where('m_id',cache('m_id'))->order('id desc')->paginate($limit);
        return $this->result($list);*/
        $notificationId = $id;
        // 查询所有用户及其阅读情况
        /*$users = UserModel::with(['trainingSigns' => function ($query) use ($notificationId) {
            $query->where('training_id', $notificationId)->with('training');
        },'hospital'])
            ->paginate($limit);
        // 遍历用户及其报名情况
        foreach ($users as $user) {
            $user->sign_status = 0;
            $user->sign_time = '';
            $user->training_title = '';
            $user->study_time = '';
            foreach ($user->trainingSigns as $trainingSign) {
                $user->sign_status = 1;
                $user->training_title = $trainingSign->training->title;
                $user->study_time = $trainingSign->training->study_time;
                if ($trainingSign->create_time) {
                    $user->sign_time=$trainingSign->create_time;
                }
            }
        }*/
        $data = input('param.');
        $nickname = $data['nickname'] ?? '';
        $where = [];
        if (!empty($nickname)) {
            $where[] = ['u.nickname', 'like', '%' . $nickname . '%'];
        }
        $users = UserModel::alias('u')
            ->join('training_sign ts', 'ts.user_id = u.id')
            ->join('training t', 't.id = ts.training_id')
            ->join('cms_category c', 'c.id = u.d_id')
            ->where('ts.training_id', $notificationId)
            ->where($where)
            ->field('u.*,ts.create_time as sign_time,t.title as training_title,t.study_time,c.name as departmentname')
            ->paginate($limit);
        if(!$users->isEmpty()){
            foreach ($users as $user){
                $user->sign_status = 1;
                $user->sign_time=date('Y-m-d',$user->sign_time);
                $user->study_time=date('Y-m-d',$user->study_time);

            }
        }
        return $this->result($users);
    }

    /**
     * 返回学习记录Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist_same_study($id, int $limit=15)
    {
        /*$list = MsgReadModel::with('user')->where('m_id',cache('m_id'))->order('id desc')->paginate($limit);
        return $this->result($list);*/
        $notificationId = $id;
        // 查询所有用户及其阅读情况
        /*$users = UserModel::with(['trainingSigns' => function ($query) use ($notificationId) {
            $query->where('training_id', $notificationId)->with('training');
        },'hospital'])
            ->paginate($limit);
        // 遍历用户及其报名情况
        foreach ($users as $user) {
            $user->sign_status = 0;
            $user->sign_time = '';
            $user->training_title = '';
            $user->study_time = '';
            foreach ($user->trainingSigns as $trainingSign) {
                $user->sign_status = 1;
                $user->training_title = $trainingSign->training->title;
                $user->study_time = $trainingSign->training->study_time;
                if ($trainingSign->create_time) {
                    $user->sign_time=$trainingSign->create_time;
                }
            }
        }*/
        $users = UserModel::alias('u')
            ->join('training_sign ts', 'ts.user_id = u.id')
            ->join('training t', 't.id = ts.training_id')
            ->join('cms_category c', 'c.id = u.d_id')
            ->where('ts.training_id', $notificationId)
            ->field('u.*,ts.create_time as sign_time,ts.check_time,ts.study_time,ts.is_study,ts.is_check,t.title as training_title,t.study_time,c.name as departmentname')
            ->paginate($limit);
        if(!$users->isEmpty()){
            foreach ($users as $user){
                $user->study_status = 0;
                $user->sign_time=date('Y-m-d h:m',$user->sign_time);
                $user->study_time=date('Y-m-d h:m',$user->study_time);
                $user->check_time=date('Y-m-d h:m',$user->check_time);

            }
        }
        return $this->result($users);
    }

    /**
     * 返回学习记录Json格式的搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist_same_study_search($id, int $limit=15)
    {
        /*$list = MsgReadModel::with('user')->where('m_id',cache('m_id'))->order('id desc')->paginate($limit);
        return $this->result($list);*/
        $notificationId = $id;
        // 查询所有用户及其阅读情况
        /*$users = UserModel::with(['trainingSigns' => function ($query) use ($notificationId) {
            $query->where('training_id', $notificationId)->with('training');
        },'hospital'])
            ->paginate($limit);
        // 遍历用户及其报名情况
        foreach ($users as $user) {
            $user->sign_status = 0;
            $user->sign_time = '';
            $user->training_title = '';
            $user->study_time = '';
            foreach ($user->trainingSigns as $trainingSign) {
                $user->sign_status = 1;
                $user->training_title = $trainingSign->training->title;
                $user->study_time = $trainingSign->training->study_time;
                if ($trainingSign->create_time) {
                    $user->sign_time=$trainingSign->create_time;
                }
            }
        }*/
        $data = input('param.');
        $nickname = $data['nickname'] ?? '';
        $where = [];
        if (!empty($nickname)) {
            $where[] = ['u.nickname', 'like', '%' . $nickname . '%'];
        }
        $users = UserModel::alias('u')
            ->join('training_sign ts', 'ts.user_id = u.id')
            ->join('training t', 't.id = ts.training_id')
            ->join('cms_category c', 'c.id = u.d_id')
            ->where('ts.training_id', $notificationId)
            ->where($where)
            ->field('u.*,ts.create_time as sign_time,t.title as training_title,t.study_time,c.name as departmentname')
            ->paginate($limit);
        if(!$users->isEmpty()){
            foreach ($users as $user){
                $user->study_status = 1;
                $user->sign_time=date('Y-m-d',$user->sign_time);
                $user->study_time=date('Y-m-d',$user->study_time);

            }
        }
        return $this->result($users);
    }

    //excel导入
    public function importExcel($filename = "")
    {
        $file = $filename;
        try {
            // 验证文件大小，名称等是否正确
            //validate(['file' => 'filesize:51200|fileExt:xls,xlsx'])->check($file);
            validate(['file' => [
                // 限制文件大小(单位b)，这里限制为8M
                'fileSize' => 8 * 1024 * 1024,
                // 限制文件后缀，多个后缀以英文逗号分割
                'fileExt'  => 'xls,xlsx'
                // 更多规则请看“上传验证”的规则，文档地址https://www.kancloud.cn/manual/thinkphp6_0/1037629#_444
            ]])->check(['file' => $file]);

            // 将文件保存到本地
            //$savename = Filesystem::putFile('topic', $file[0]);
            $savename = Filesystem::disk('public')->putFile('file', $file);
            // 截取后缀
            $fileExtendName = substr(strrchr($savename, '.'), 1);
            // 有Xls和Xlsx格式两种
            if ($fileExtendName == 'xlsx') {
                $objReader = IOFactory::createReader('Xlsx');
            } else {
                $objReader = IOFactory::createReader('Xls');
            }
            // 设置文件为只读
            $objReader->setReadDataOnly(TRUE);
            // 读取文件，tp6默认上传的文件，在runtime的相应目录下，可根据实际情况自己更改
            $objPHPExcel = $objReader->load(public_path() . 'storage/' . $savename);
            //excel中的第一张sheet
            $sheet = $objPHPExcel->getSheet(0);
            // 取得总行数
            $highestRow = $sheet->getHighestRow();
            // 取得总列数
            $highestColumn = $sheet->getHighestColumn();
            Coordinate::columnIndexFromString($highestColumn);
            $lines = $highestRow - 1;
            if ($lines <= 0) {
                //echo('数据不能为空！');
                return $this->error('数据不能为空！');
                exit();
            }
            // 直接取出excle中的数据
            $data = $objPHPExcel->getActiveSheet()->toArray();
            // 删除第一个元素（表头）
            array_shift($data);
            // 返回结果
            return $data;
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        }
    }

    /** @DESC [导入签到] */
    public function check(Request $request)
    {
        // 接收文件上传信息
        $file = $request->file("file");
        // 调用类库，读取excel中的内容
        $excel_array = $this->importExcel($file);

        $data = [];
        foreach ($excel_array as $key => $value) {
            $number = $key + 2;
            // 正则去除多余空白字符
            $phone = preg_replace('/\s+/', '', $value['1']);
            $value2 = preg_replace('/\s+/', '', $value['2']);
            $value3 = preg_replace('/\s+/', '', $value['3']);
            $value4 = preg_replace('/\s+/', '', $value['4']);

            if ($value3 !== '签到') {
                return $this->error('签到状态有误，请检查导入类型');
            }

            // 查询用户是否存在
            $userinfo = Db::name('user')->where('phone', $phone)->find();
            if (!$userinfo) {
                return $this->error('用户手机号不存在，请检查，在第' . $number . '行');
            }

            // 查询学习班详情
            $info = \app\cms\model\Training::where('id', $value4)->find();
            if (!$info) {
                return $this->error('学习班不存在，请检查');
            }

            $updateData = [
                'is_check' => 1,
                'check_time' => $value2,
                'training_id' => $value4
            ];
            // 如果学习班不需要考试，再更新学习记录
            if ($info['is_exam'] === 0) {
                $updateData['is_study'] = 1;
                $updateData['study_time'] = $value2;
            }

            $data[] = $updateData;
        }

        // 启动事务
        Db::startTrans();
        try {
            // 批量更新
            foreach ($data as $item) {
                TrainingSign::where([
                    'user_id' => $userinfo['id'], // 假设 user_id 是关联字段
                    'training_id' => $item['training_id']
                ])->update($item);
            }

            // 提交事务
            Db::commit();
            return $this->success('文件上传成功，已经导入' . count($data) . '条数据');
        } catch (\Throwable $t) {
            // 回滚事务
            Db::rollback();
            return $this->error('导入数据失败: ' . $t->getMessage());
        }
    }

}
