<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use app\model\Forums;

class Forum extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $list = Forums::withCount('topics')->order('create_time', 'desc')->select();
        return $this->out('success', $list);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        if ($this->request->isPost()) {
            $insert = $this->request->post(['name', 'description']);
            try {
                $this->validate($insert, 'app\validate\Forum');
            } catch (\Throwable $th) {
                return $this->out($th->getError(), [], 101);
            }
            $insert['status'] = 1;
            $forum = Forums::create($insert);

            return $this->out('Created successfully', $forum);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read()
    {
        //
        $forum = Forums::find($this->request->param('tid'));
        if (empty($forum)) {
            return $this->out('Forum does not exist');
        }
        return $this->out('success', $forum);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update()
    {
        //
        if ($this->request->isPut()) {
            $fid = $this->request->put('tid');
            $forum = Forums::find($fid);
            if (empty($forum)) {
                return $this->out('Forum does not exist', [], -33);
            }
            $update = $this->request->post(['name', 'description']);
            try {
                $this->validate($update, 'app\validate\Forum');
            } catch (\Throwable $th) {
                return $this->out($th->getError(), [], 101);
            }
            $forum->save($update);
            return $this->out('Updated successfullt', $forum);
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
