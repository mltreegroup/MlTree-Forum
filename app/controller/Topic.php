<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use \app\model\Options;
use \app\model\Topics;

class Topic extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $page = $this->request->param('page');
        $type = $this->request->param('type');
        $fid = $this->request->param('forum');

        $page ?? $page = 1;

        if (!empty($fid)) {
            $list = Topics::with(['user', 'forum'])->where('fid', $fid)->page($page, (int) Options::getValue('listMax'))->order('create_time', 'desc')->select();
        } else {
            $list = Topics::with(['user', 'forum'])->page($page, (int) Options::getValue('listMax'))->order('create_time', 'desc')->select();
        }

        $list->visible(['user' => ['uid', 'nick', 'email', 'avatar']]);

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
        if (!\app\model\Users::checkStatus('postTopic')) {
            return $this->out('State error', [], 1003);
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            try {
                $this->validate($data, 'app\validate\Topic.create');
            } catch (\Throwable $th) {
                return $this->out($th->getError(), [], 101);
            }

            $data['uid'] = $this->request->jwt->uid;
            $data['status'] = 1;

            $topic = Topics::create($data);

            return $this->out('Topic created successfully', $topic);
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
        $tid = $this->request->param('tid');
        $topic = Topics::with(['user', 'forum'])->find($tid);
        if (empty($topic)) {
            return $this->out('Topic does not exist', [], -23);
        }
        $topic->visible(['user' => ['uid', 'nick', 'email', 'avatar']]);

        $topic->views += 1;
        $topic->save();

        return $this->out('success', $topic);
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
        if (!\app\model\Users::checkStatus('postTopic')) {
            return $this->out('State error', [], 1003);
        }
        if ($this->request->isPut()) {
            $tid = $this->request->put('tid');
            $updateData = $this->request->put(['tid', 'fid', 'title', 'content']);

            try {
                $this->validate($updateData, 'app\validate\Topic.update');
            } catch (\Throwable $th) {
                return $this->out($th->getError(), [], 101);
            }

            $topic = Topics::find($tid);
            if (empty($topic)) {
                return $this->out('Topic does not exist', [], -24);
            } elseif ($topic->uid != $this->request->jwt->uid && $this->request->jwt->admin != 1) {
                return $this->out('Insufficient authority', [], -25);
            }

            $topic->allowField(['fid', 'title', 'content'])->save($updateData);
            return $this->out('Updated successflly', $topic);
        }
    }

    public function like()
    {
        if ($this->request->isPost()) {
            $tid = $this->request->post('tid');
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        //
        if ($this->request->isDelete()) {
            $tid = $this->request->delete('tid');
            $topic = Topics::find($tid);
            if (empty($topic)) {
                return $this->out('Topic does not exist', [], -24);
            }
            $topic->delete();
            return $this->out('Deleted successfully');
        }
    }
}
