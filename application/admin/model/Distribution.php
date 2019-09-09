<?php

/**
 * @desc c产品分销表
 */
namespace app\admin\model;


use think\Db;

class Distribution extends BaseModel
{
    protected $table = 'product_distribution';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * @desc 插入分销产品
     * @param $data
     * @return bool|int|string
     */
    public function insertData($data)
    {
        $data['create_time'] = date('Y-m-d H:i:s', time());
        $result = $this->insert($data);
        if (!$result){
            return false;
        }else{
            return $result;
        }
    }

    /**
     *
     * Desc: 查询分销记录
     * Date: 2019/6/20
     * @param $data
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function selectData($data)
    {
        return $this->where($data)->find();
    }

    public function updateData($where,$data)
    {
        $data['update_time'] = date('Y-m-d H:i:s', time());
        return $this->where($where)->update($data);
    }
}