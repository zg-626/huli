<?php

declare (strict_types = 1);

namespace app\cms\validate;

use think\Validate;

class Article extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'title' => 'unique:cms_article',
        'redirecturl' => 'url',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'title.unique' => '文章标题已经存在，请重新输入',
        'redirecturl.url' => '请输入正确的跳转链接地址',
    ];
}
