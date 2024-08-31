<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Lke\V20231130\Models;
use TencentCloud\Common\AbstractModel;

/**
 * IsTransferIntent请求参数结构体
 *
 * @method string getContent() 获取内容
 * @method void setContent(string $Content) 设置内容
 * @method string getBotAppKey() 获取机器人appKey
 * @method void setBotAppKey(string $BotAppKey) 设置机器人appKey
 */
class IsTransferIntentRequest extends AbstractModel
{
    /**
     * @var string 内容
     */
    public $Content;

    /**
     * @var string 机器人appKey
     */
    public $BotAppKey;

    /**
     * @param string $Content 内容
     * @param string $BotAppKey 机器人appKey
     */
    function __construct()
    {

    }

    /**
     * For internal only. DO NOT USE IT.
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("Content",$param) and $param["Content"] !== null) {
            $this->Content = $param["Content"];
        }

        if (array_key_exists("BotAppKey",$param) and $param["BotAppKey"] !== null) {
            $this->BotAppKey = $param["BotAppKey"];
        }
    }
}
