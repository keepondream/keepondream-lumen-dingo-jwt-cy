<?php
/**
 * Description: 服务层接口
 * Author: WangSx
 * DateTime: 2019-06-18 10:19
 */

namespace App\Common\Interfaces;


interface ServiceInterface
{
    /**
     * Description: 创建
     * Author: WangSx
     * DateTime: 2019-06-18 10:21
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Description: 更新
     * Author: WangSx
     * DateTime: 2019-06-26 16:09
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data,int $id);

    /**
     * Description: 根据主键获取详情
     * Author: WangSx
     * DateTime: 2019-06-18 10:23
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Description: 根据主键删除
     * Author: WangSx
     * DateTime: 2019-06-18 10:24
     * @param int $id
     * @return mixed
     */
    public function deleteById(int $id);

}

