<?php
/**
 * Created by 24th_srm.
 * User: By Cbw
 * Email: [17854288794@163.com]
 * Date: 2019/5/20
 * Time: 14:58
 */

namespace app\admin\model;
use think\Model;
use think\Db;

class Systems extends BaseModel
{
    protected $table = 'system';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'created_time';
    protected $updateTime = 'update_time';

    /**
     *
     * Desc: find
     * Date: 2019/5/20
     * @param array $where
     * @param string $fileds
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getSystem($param)
    {
        return $this->where($param)->find();
    }

    /**
     *
     * Desc:insert
     * Date: 2019/5/20
     * @param array|mixed $data
     * @return bool|int|string
     */
    public function insertData($data)
    {
        $result = $this->save($data);
        if (!$result){
            return false;
        }else{
            return $result;
        }
    }

    /**
     *
     * Desc:update
     * Date: 2019/5/20
     * @param $where
     * @param $data
     * @return bool
     */
    public function updateData($where,$data)
    {
        $result = $this->where($where)->update($data);
        if (!$result){
            return false;
        }else{
            return true;
        }
    }

    /**
     *
     * Desc:软删除
     * Date: 2019/5/20
     * @param $where
     * @param array $data
     * @return bool
     */
    public function deleteData($where,$data=['is_show'=>2])
    {
        $result = $this->where($where)->update($data);
        if(!$result){
            return false;
        }else{
            return true;
        }
    }
}