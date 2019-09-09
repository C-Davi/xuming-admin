<?php


namespace app\admin\model;


use think\Model;

class ShareDis extends Model
{
    protected $table = 'distribution';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * @desc 插入用户可分销产品分销记录
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

    public function selectData($where)
    {
        $result = $this->where($where)->select();
        return $result;
    }

    public function updateData($where,$data)
    {
        return $this->where($where)->update($data);
    }
}