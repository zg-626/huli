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
namespace TencentCloud\Cynosdb\V20190107\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 备可用区库存信息
 *
 * @method string getSlaveZone() 获取备可用区
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setSlaveZone(string $SlaveZone) 设置备可用区
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getStockCount() 获取备可用区的库存数量	
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setStockCount(integer $StockCount) 设置备可用区的库存数量	
注意：此字段可能返回 null，表示取不到有效值。
 * @method boolean getHasStock() 获取备可用区是否有库存	
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setHasStock(boolean $HasStock) 设置备可用区是否有库存	
注意：此字段可能返回 null，表示取不到有效值。
 */
class SlaveZoneStockInfo extends AbstractModel
{
    /**
     * @var string 备可用区
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $SlaveZone;

    /**
     * @var integer 备可用区的库存数量	
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $StockCount;

    /**
     * @var boolean 备可用区是否有库存	
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $HasStock;

    /**
     * @param string $SlaveZone 备可用区
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $StockCount 备可用区的库存数量	
注意：此字段可能返回 null，表示取不到有效值。
     * @param boolean $HasStock 备可用区是否有库存	
注意：此字段可能返回 null，表示取不到有效值。
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
        if (array_key_exists("SlaveZone",$param) and $param["SlaveZone"] !== null) {
            $this->SlaveZone = $param["SlaveZone"];
        }

        if (array_key_exists("StockCount",$param) and $param["StockCount"] !== null) {
            $this->StockCount = $param["StockCount"];
        }

        if (array_key_exists("HasStock",$param) and $param["HasStock"] !== null) {
            $this->HasStock = $param["HasStock"];
        }
    }
}
