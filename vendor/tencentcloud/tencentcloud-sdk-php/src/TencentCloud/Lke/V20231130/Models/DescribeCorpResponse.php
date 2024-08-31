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
 * DescribeCorp返回参数结构体
 *
 * @method string getCorpBizId() 获取企业ID

 * @method void setCorpBizId(string $CorpBizId) 设置企业ID

 * @method integer getRobotQuota() 获取机器人配额

 * @method void setRobotQuota(integer $RobotQuota) 设置机器人配额

 * @method string getFullName() 获取企业全称

 * @method void setFullName(string $FullName) 设置企业全称

 * @method string getRequestId() 获取唯一请求 ID，由服务端生成，每次请求都会返回（若请求因其他原因未能抵达服务端，则该次请求不会获得 RequestId）。定位问题时需要提供该次请求的 RequestId。
 * @method void setRequestId(string $RequestId) 设置唯一请求 ID，由服务端生成，每次请求都会返回（若请求因其他原因未能抵达服务端，则该次请求不会获得 RequestId）。定位问题时需要提供该次请求的 RequestId。
 */
class DescribeCorpResponse extends AbstractModel
{
    /**
     * @var string 企业ID

     */
    public $CorpBizId;

    /**
     * @var integer 机器人配额

     */
    public $RobotQuota;

    /**
     * @var string 企业全称

     */
    public $FullName;

    /**
     * @var string 唯一请求 ID，由服务端生成，每次请求都会返回（若请求因其他原因未能抵达服务端，则该次请求不会获得 RequestId）。定位问题时需要提供该次请求的 RequestId。
     */
    public $RequestId;

    /**
     * @param string $CorpBizId 企业ID

     * @param integer $RobotQuota 机器人配额

     * @param string $FullName 企业全称

     * @param string $RequestId 唯一请求 ID，由服务端生成，每次请求都会返回（若请求因其他原因未能抵达服务端，则该次请求不会获得 RequestId）。定位问题时需要提供该次请求的 RequestId。
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
        if (array_key_exists("CorpBizId",$param) and $param["CorpBizId"] !== null) {
            $this->CorpBizId = $param["CorpBizId"];
        }

        if (array_key_exists("RobotQuota",$param) and $param["RobotQuota"] !== null) {
            $this->RobotQuota = $param["RobotQuota"];
        }

        if (array_key_exists("FullName",$param) and $param["FullName"] !== null) {
            $this->FullName = $param["FullName"];
        }

        if (array_key_exists("RequestId",$param) and $param["RequestId"] !== null) {
            $this->RequestId = $param["RequestId"];
        }
    }
}
