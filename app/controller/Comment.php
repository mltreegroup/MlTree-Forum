<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use app\model\Comments;
use app\model\Topics;
use app\model\Options;

class Comment extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $tid = $this->request->param('tid');
        $page = $this->request->param('page') ?? 1;

        $topic = Topics::find($tid);
        if (empty($topic)) {
            return $this->out('Topic does not exist', [], -51);
        }
        $comment = Comments::with('user')
            ->where('tid', $tid)
            ->page((int) $page, (int) Options::getValue('commentListMax'))
            ->order('create_time', 'desc')
            ->select();

        $comment->visible(['user' => ['uid', 'avatar', 'nick', 'email']]);

        return $this->out('success', $comment);
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
            $tid = $this->request->post('tid');
            $insert = $this->request->post(['tid', 'content']);

            $topic = Topics::find($tid);
            if (empty($topic)) {
                return $this->out('Topic does not exist', [], -51);
            }
            $insert['uid'] = $this->request->jwt->uid;
            try {
                $this->validate($insert, 'app\validate\Comment');
            } catch (\Throwable $th) {
                return $this->out($th->getError(), [], 101);
            }
            $insert['status'] = 1;

            $comment = $topic->comments()->save($insert);
            return $this->out('Comment success', $comment);
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
        $coid = $this->request->param('coid');
        $comment = Comments::with(['user', 'forum'])->find($coid);
        if (empty($comment)) {
            return $this->out('Comment does not exist', [], -53);
        }
        $comment->visible(['user' => ['uid', 'nick', 'email', 'avatar']]);
        return $this->out('success', $comment);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
        if ($this->request->isPut()) {
            $coid = $this->request->put('coid');
            $updateData = $this->request->put(['fid', 'content']);

            try {
                $this->validate($updateData, 'app\validate\Comment.update');
            } catch (\Throwable $th) {
                return $this->out($th->getError(), [], 101);
            }

            $comment = Comments::find($coid);
            if (empty($topic)) {
                return $this->out('Topic does not exist', [], -54);
            } elseif ($comment->uid != $this->request->jwt->uid && $this->request->jwt->admin != 1) {
                return $this->out('Insufficient authority', [], -55);
            }

            $comment->allowField(['fid', 'content'])->save($updateData);
            return $this->out('Updated successflly', $comment);
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
        if ($this->request->isDelete()) {
            $coid = $this->request->delete('coid');

            $comment = Comments::find($coid);
            if (empty($topic)) {
                return $this->out('Topic does not exist', [], -24);
            }

            $comment->delete();

            return $this->out('Deleted successflly');
        }
    }
}
