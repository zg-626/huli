<?php

declare (strict_types = 1);

namespace app\mxadmin\controller;

use app\cms\model\CmsCategory;
use app\cms\model\Fees;
use app\common\model\Department;
use app\mxadmin\AdminBase;
use app\mxadmin\model\AdminModel;
use app\mxadmin\model\DictData;
use app\mxadmin\model\UserModel;
use app\mxadmin\model\AuthGroup;
use app\mxadmin\model\AuthGroupAccess;
use PhpOffice\PhpSpreadsheet\Reader\Xls\MD5;
use think\Request;
use app\cms\model\CmsDepartment;
use app\mxadmin\model\AuthRule;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use think\exception\ValidateException;
use think\facade\Filesystem;
use think\facade\Db;
class User extends AdminBase
{
    /**
     * 无需权限判断的方法
     * @var array
     */
    protected $noNeedAuth = ['edit_update_time','form', 'serach', 'update'];
    /**
     * 账号管理
     * @return \think\response\View
     */
    public function index()
    {
        $rolelist = AuthGroup::field('id,title')->order('id')->select();

        $department = list_to_trees(CmsCategory::getCategoryData(), true);

        
        
        return view('',[
            'rolelist'  =>  $rolelist,
            'admin_id' => getAdminId(),
            'category'   =>  $department,
            'educational_type' => getDictDataId(2),
            'position_type' => getDictDataId(3),
            'professional_type' => getDictDataId(4),
            'is_admin'   =>  session('admin_info.is_admin'),
        ]);
    }

    // 查看
    public function find($id)
    {
        $info = UserModel::where('id', $id)->find();
        // 职务变更记录
        $department=Department::with('position')->where('user_id', $id)->order('start_time desc')->select();
        return view('', [
            'info' => $info,
            'department' => $department
        ]);
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist($limit=15)
    {

        $list = UserModel::with(['hospital','educationalType','positionType','professionalType'])->order('id', 'desc')->paginate($limit);

        
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
            $serach = new UserModel();
            
            if ($data['phone'] != '') {
                $serach = $serach->whereLike('phone', '%' . $data['phone'] . '%');
            }
            if ($data['nickname'] != '') {
                $serach = $serach->whereLike('nickname', '%' . $data['nickname'] . '%');
            }
            if ($data['startDate'] != '' && $data['endDate'] != '') {
                $serach = $serach->whereBetweenTime('create_time', strtotime($data['startDate']), strtotime($data['endDate']) + 86399);
            }
            if ($data['status'] != '') {
                $serach = $serach->where('status', $data['status']);
            }
            if ($data['sex'] != '') {
                $serach = $serach->where('sex', $data['sex']);
            }

            if ($data['educational_id'] != '') {
                $serach = $serach->where('educational_id', $data['educational_id']);
            }

            if ($data['professional_id'] != '') {
                $serach = $serach->where('professional_id', $data['professional_id']);
            }

            /*if ($data['position_id'] != '') {
                $serach = $serach->where('position_id', $data['position_id']);
            }*/

            if ($data['d_id'] != '') {
                $serach = $serach->where('d_id', $data['d_id']);
            }
            $list = $serach->with(['hospital','educationalType','positionType','professionalType'])->order('id', 'desc')->paginate($limit);

           
            return $this->result($list);
        }
    }

    /**
     * 添加账号
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'User');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            $data['password'] = $data['newpassword'];
            $data['vip_time'] = time()+86400*15;
            $result = UserModel::create($data);

            if ($result == true) {
                return $this->success('账号添加成功');
            } else {
                return $this->error('账号添加失败');
            }
        }
    }

    /**
     * 修改账号
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'User.edit');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            if (empty($data['newpassword'])) {
                unset($data['password']);
            } else {
                $data['password'] = $data['newpassword'];
            }

            $result = UserModel::update($data, ['id' => $id]);
            //echo $UserModel->getLastSql();exit;
            if ($result == true) {
                return $this->success('账号修改成功');
            } else {
                return $this->error('账号修改失败');
            }
        }
    }


    /**
     * 审核并微信通知
     */
    public function shenhe($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            /*try {
                $this->validate($data, 'User.edit');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }*/

            $result = UserModel::update($data, ['id' => $id]);

            if ($result == true) {
                // 如果审核成功，同步管理员表
                if($data['status'] == 1){
                    $user = UserModel::where('id', $id)->find();
                    $create=[
                        'password'=>$user['password'],
                        'nickname'=>$user['nickname'],
                        'username'=>$user['phone'],
                    ];
                    $admin_info=AdminModel::create($create);
                    // 新增用户所属角色
                    $role_id = explode(',','2');
                    foreach ($role_id as $value) {
                        $dataset[] = ['uid' => $admin_info->id, 'group_id' => $value];
                    }
                    AuthGroupAccess::insertAll($dataset);
                }
                // 如果审核成功，增加缴费记录
                self::addFees($id);
                return $this->success('账号审核成功');
            } else {
                return $this->error('账号审核失败');
            }
        }
    }

    //增加缴费记录
    public static function addFees($id)
    {
        //获取分类
        $categories = DictData::where('dict_id', 11)->select();
        //获取年份
        $years = DictData::where('dict_id', 12)->select();
        // 示例的嵌套循环创建记录
        foreach ($years as $year) {
            foreach ($categories as $category) {
                Fees::create([
                    'dict_id' => $category->id,
                    'dict_data_id' => $year->id,
                    'user_id' => $id, // 替换为你实际的用户ID
                    'status' => 0,
                    'fees_year' => $year->name,
                    'fees_type' => $category->name,
                ]);
            }
        }
    }

    /**
     * 修改账号状态
     */
    public function edit_state_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');

            if($data['status'] == 1){
                $status = 0;
            }else{
                $status = 1;
            }

            /*if ($id == getAdminId()) {
                return $this->error('自已账号禁止禁用');
            }
            if ($value['is_admin'] == 1) {
                return $this->error('超级管理员禁止禁用');
            }*/
            $result = UserModel::update(['status' => $status], ['id' => $id]);

            if ($result == true) {
                return $this->success($data['status'] ? '已禁用' : '已启用');
            } else {
                return $this->error('账号状态修改失败');
            }
        }
    }

    /**
     * 修改账号状态
     */
    public function edit_permissions_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if(session('admin_info.is_admin') == 1){
                $value = UserModel::where('id', $id)->find();
                $result = UserModel::update(['permissions' => $data['permissions']], ['id' => $id]);

                if ($result == true) {
                    return $this->success($data['permissions'] ? '已开启' : '未开启');
                } else {
                    return $this->error('账号状态修改失败');
                }        
            }else{
                return $this->error('请使用总管理员修改当前权限');
            }
            
        }
    }

    /**
     * 修改账号状态
     */
    public function edit_white_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if(session('admin_info.is_admin') == 1){
                $value = UserModel::where('id', $id)->find();
                $result = UserModel::update(['is_white' => $data['is_white']], ['id' => $id]);

                if ($result == true) {
                    return $this->success($data['is_white'] ? '已授权' : '未授权');
                } else {
                    return $this->error('修改失败');
                }        
            }else{
                return $this->error('请使用总管理员修改当前权限');
            }
            
        }
    }

    /**
     * 删除账号
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
            
            $result = UserModel::destroy($ids);

            if ($result == true) {
                // 删除用户所属角色
                //AuthGroupAccess::whereIn('uid', $ids)->delete();
                //删除token
                Db::name('user_token')->where('u_id',$id)->delete();
                return $this->success('账号删除成功');
            } else {
                return $this->error('账号删除失败');
            }
        }
    }

    /**
     * 删除账号
     */
    public function Update($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }
            $result=UserModel::whereIn('id',$ids)->update(['vip_time'=>strtotime($data['vip_time'])]);
            if ($result == true) {

                return $this->success('批量更新成功');
            } else {
                return $this->error('批量更新失败');
            }
        }
    }

    /**
     * 复制用户
     * @param $id
     */
    public function copyuser()
    {
        if (request()->isPost()) {
            $UserModel = new UserModel;
            //$data = input('param.');
            if(session('admin_info.is_admin') == 1){
                $data = [
                    ['phone'=>185,'nickname'=>'masterplate','headimg'=>'','status'=>1,'d_id'=>10],
                ];        
            }else{
                $data = [
                    ['phone'=>185,'nickname'=>'masterplate','headimg'=>'','status'=>1,'d_id'=>session('admin_info.d_id')],
                ];
            }
            $result = $UserModel->saveAll($data);
            if ($result == true) {
                return $this->success('复制成功');
            } else {
                return $this->error('复制失败');
            }
        }
    }

    /**
     * 修改账号使用时间
     */
    public function edit_update_time($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if(!empty($data['field'])){
                $data['field']=strtotime($data['field']);
            }
            //print_r($data['field']);exit;
            $result = UserModel::update(['vip_time' => $data['field']], ['id' => $id]);
            if ($result == true) {
                return $this->success('设置成功');
            } else {
                return $this->error('设置失败');
            }
        }
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

    /** @DESC [用户导入] */
    public function daoru(Request $request)
    {
        // 接收文件上传信息
        $file = $request->file("file");
        // 调用类库，读取excel中的内容
        $excel_array = self::importExcel($file);
        foreach ($excel_array as $key => $value) {
            $number = $key +2;
            // 正则去除多余空白字符
            $data[$key]['phone'] = preg_replace('/\s+/', '', $value['0']);
            //查询是否存在
            $userinfo=Db::name('user')->where('phone',$value['0'])->find();
            if($userinfo){
                return $this->error('登录账号已经存在，请检查，在第'. $number.'行');
            }
            $data[$key]['nickname'] = preg_replace('/\s+/', '', $value['1']);
         
            //通过名称获取部门id
            //$data[$key]['department'] = preg_replace('/\s+/', '', $value['2']);
            $data[$key]['d_id']=Db::name('cms_department')->where('name',preg_replace('/\s+/', '', $value['2']))->value('id');
            $value3 = preg_replace('/\s+/', '', $value['3']);
            if ($value3 === '男') {
                $data[$key]['sex'] = 1;
            } elseif ($value3 === '女') {
                $data[$key]['sex'] = 0;
            } else {
                $data[$key]['sex'] = 2;
            }
            $data[$key]['workname'] = preg_replace('/\s+/', '', $value['4']);
            $data[$key]['password'] = md5(preg_replace('/\s+/', '', $value['5']));
            $data[$key]['create_time'] = time();
            $data[$key]['update_time'] = time();
            $data[$key]['status'] = 1;
            $data[$key]['vip_time'] = time()+86400*15;
        }
    
        // 启动事务
        Db::startTrans();
        try{
            //进行数据库操作的一系列语句
            $result = UserModel::insertAll($data);
            if ($result!=true) {
                return $this->error('导入数据失败');
            }
            // 提交事务
            Db::commit();
            return $this->success('文件上传成功，已经导入'.$result.'条数据');
        }catch (\Throwble $t){
            // 回滚事务
            Db::rollback();
            return $this->error('导入数据失败'. $e->getMessage());
        } 
               
        /*echo "<pre>";
        print_r($data);   //  二维数组*/
    }

}
