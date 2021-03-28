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

namespace app\mxadmin\model;

use think\Model;

class Attachment extends Model
{
    /**
     * 格式化字节大小
     * @param $value
     * @return string
     */
    public function getFilesizeAttr($value)
    {
        return formatBytes($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function getStorageAttr($value)
    {
        $storage = ['local'=>'本地', 'qiniu'=>'七牛云'];
        return $storage[$value];
    }

    /**
     * 保存上传文件信息
     * @param $url
     * @param $filetype
     * @param $filesize
     * @param $mimetype
     * @param $storage
     * @param $sha1
     */
    public static function record($url, $filetype, $filesize, $mimetype, $storage, $sha1, $hash = '')
    {
        self::create([
            'admin_id' => getAdminId(),
            'url' => $url,
            'filetype' => $filetype,
            'filesize' => $filesize,
            'mimetype' => $mimetype,
            'storage' => $storage,
            'sha1' => $sha1,
            'hash' => $hash,
        ]);
    }

    /**
     * 获取上传者信息
     * @return \think\model\relation\HasOne
     */
    public function username()
    {
        return $this->hasOne(AdminModel::class, 'id', 'admin_id')->bind(['username']);
    }
}