<?php

use think\migration\db\Column;
use think\migration\Migrator;

class InstallSql extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $users = $this->table('users', ['id' => 'uid']);
        $users->addColumn('gid', 'integer', ['comment' => '所属用户组ID'])
            ->addColumn('nick', 'string', ['limit' => 50, 'comment' => '用户昵称'])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('password', 'string', ['limit' => 60])
            ->addColumn('avatar', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('motto', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('phone', 'string', ['limit' => 11, 'null' => true])
            ->addColumn('create_ip', 'string', ['limit' => 15])
            ->addColumn('create_time', 'integer')
            ->addColumn('update_time', 'integer')
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->addColumn('last_time', 'integer', ['null' => true])
            ->addColumn('last_ip', 'string', ['limit' => 15, 'null' => true])
            ->addColumn('status', 'integer', ['limit' => 1])
            ->addColumn('handle_code', 'string', ['limit' => 64, 'null' => true, 'comment' => '用户操作代码'])
            ->addColumn('overdue_time', 'integer', ['null' => true])
            ->addIndex(['uid', 'gid'], ['unique' => true])
            ->create();

        $fields = $this->table('fields', ['id' => 'feid'])
            ->addColumn('name', 'string', ['limit' => 200])
            ->addColumn('value', 'text')
            ->create();

        $forums = $this->table('forums', ['id' => 'fid'])
            ->addColumn('name', 'string')
            ->addColumn('description', 'string', ['null' => true])
            ->addColumn('create_time', 'integer')
            ->addColumn('update_time', 'integer')
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->addColumn('status', 'integer', ['limit' => 1])
            ->create();

        $groups = $this->table('groups', ['id' => 'gid'])
            ->addColumn('name', 'string')
            ->addColumn('description', 'string', ['null' => true])
            ->addColumn('rule', 'string', ['null' => true])
            ->addColumn('create_time', 'integer')
            ->addColumn('update_time', 'integer')
            ->addColumn('status', 'integer', ['limit' => 1])
            ->create();

        $options = $this->table('options', ['id' => false])
            ->addColumn('name', 'string')
            ->addColumn('value', 'text')
            ->addColumn('type', 'string')
            ->create();

        $rule = $this->table('rule', ['id' => 'ruid'])
            ->addColumn('name', 'string')
            ->addColumn('rule', 'string')
            ->addColumn('type', 'string')
            ->addColumn('status', 'integer', ['limit' => 1])
            ->create();

        $topics = $this->table('topics', ['id' => 'tid'])
            ->addColumn('fid', 'integer')
            ->addColumn('uid', 'integer')
            ->addColumn('title', 'string')
            ->addColumn('content', 'text')
            ->addColumn('views', 'integer', ['default' => 0])
            ->addColumn('likes', 'integer', ['default' => 0])
            ->addColumn('create_time', 'integer')
            ->addColumn('update_time', 'integer')
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->addColumn('is_top', 'integer', ['limit' => 1, 'comment' => '是否置顶，1为置顶', 'default' => 0])
            ->addColumn('is_down', 'integer', ['limit' => 1, 'comment' => '是否下沉，1为下沉', 'default' => 0])
            ->addColumn('status', 'integer', ['limit' => 1])
            ->create();

        $comments = $this->table('comments', ['id' => 'coid'])
            ->addColumn('tid', 'integer')
            ->addColumn('uid', 'integer')
            ->addColumn('reply_coid', 'integer', ['comment' => '回复的评论ID', 'null' => true])
            ->addColumn('content', 'text')
            ->addColumn('create_time', 'integer')
            ->addColumn('update_time', 'integer')
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->addColumn('status', 'integer', ['limit' => 1])
            ->create();

        $files = $this->table('files', ['id' => 'file_id'])
            ->addColumn('uid', 'integer')
            ->addColumn('file_url', 'string', [ 'null' => true])
            ->addColumn('type', 'text')
            ->addColumn('service_type', 'integer')
            ->addColumn('create_time', 'integer')
            ->addColumn('update_time', 'integer')
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->addColumn('status', 'integer', ['limit' => 1])
            ->create();

    }
}
