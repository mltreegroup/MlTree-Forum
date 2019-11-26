<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Console;
use think\facade\Db;

class Install extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('install')
            ->addArgument('password', Argument::OPTIONAL, 'Administrators Password')
            ->addOption('email', null, Option::VALUE_REQUIRED, 'Administrators Email')
            ->setDescription('For installing mltreeforum');
    }

    protected function execute(Input $input, Output $output)
    {
        $pwd = \trim($input->getArgument('password') ?? 'admin');
        $email = $input->getOption('email') ?? 'admin@admin.com';

        // 安装数据库
        $output->writeln("=============Mltree Forum Install 1/4=============");
        $output->writeln("");
        $output->writeln(\date('Y-m-d H:i:s', time()) . ' Creating database');
        Console::call('migrate:run');

        // 写入网站基本信息
        $output->writeln("=============Mltree Forum Install 2/4=============");
        $output->writeln("");
        $output->writeln(\date('Y-m-d H:i:s', time()) . ' Inserting Site Baseinformation');
        // **rule
        $rule = [
            ['name' => 'admin', 'rule' => 'app\controller\Admin', 'type' => 'class', 'status' => 1],
            ['name' => 'createTopic', 'rule' => 'Topic\create', 'type' => 'url', 'status' => 1],
            ['name' => 'createComment', 'rule' => 'Comment\create', 'type' => 'url', 'status' => 1],
            ['name' => 'createForum', 'rule' => 'Forum\create', 'type' => 'url', 'status' => 1],
            ['name' => 'updateTopic', 'rule' => 'Topic\update', 'type' => 'url', 'status' => 1],
            ['name' => 'updateComment', 'rule' => 'Comment\update', 'type' => 'url', 'status' => 1],
            ['name' => 'updateForum', 'rule' => 'Forum\update', 'type' => 'url', 'status' => 1],
            ['name' => 'deleteTopic', 'rule' => 'Topic\delete', 'type' => 'url', 'status' => 1],
            ['name' => 'deleteComment', 'rule' => 'Comment\delete', 'type' => 'url', 'status' => 1],
            ['name' => 'deleteForum', 'rule' => 'Forum\delete', 'type' => 'url', 'status' => 1],
        ];
        Db::name('rule')->insertAll($rule);

        // **Options
        $options = [
            ['name' => 'siteStatus', 'value' => '1', 'type' => 'base'],
            ['name' => 'siteTitle', 'value' => 'MlTreeForum', 'type' => 'base'],
            ['name' => 'notice', 'value' => 'This is a new MlTreeForum website!', 'type' => 'base'],
            ['name' => 'siteUrl', 'value' => '', 'type' => 'base'],
            ['name' => 'defaultGroup', 'value' => '2', 'type' => 'base'],
            ['name' => 'listMax', 'value' => '20', 'type' => 'base'],
            ['name' => 'commentListMax', 'value' => '0', 'type' => 'base'],
            ['name' => 'activeMail', 'value' => '0', 'type' => 'reg'],
            ['name' => 'reg_active_time', 'value' => 24 * 36000, 'type' => 'reg'],
            ['name' => 'fromName', 'value' => 'MlTree', 'type' => 'email'],
            ['name' => 'fromAdress', 'value' => 'https://forum.kingsr.cc', 'type' => 'email'],
            ['name' => 'smtpHost', 'value' => 'smtp.aliyun.com', 'type' => 'email'],
            ['name' => 'smtpPort', 'value' => '365', 'type' => 'email'],
            ['name' => 'replyTo', 'value' => '', 'type' => 'email'],
            ['name' => 'smtpUser', 'value' => 'user', 'type' => 'email'],
            ['name' => 'smtpPass', 'value' => 'password', 'type' => 'email'],
            ['name' => 'encriptionType', 'value' => 'SSL', 'type' => 'email'],
        ];
        Db::name('options')->insertAll($options);

        // **Groups
        $groups = [
            ['name' => 'Administrators', 'description' => '网站超级管理员，拥有网站全部权限', 'rule' => '1', 'create_time' => time(), 'update_time' => time(), 'status' => 1],
            ['name' => 'Member', 'description' => '网站注册会员', 'rule' => '2,3,5,6', 'create_time' => time(), 'update_time' => time(), 'status' => 1],
        ];
        Db::name('groups')->insertAll($groups);

        // **Forums
        $forums = [
            ['name' => '默认板块', 'description' => '网站安装时自动安装的默认板块', 'create_time' => time(), 'update_time' => time(), 'status' => 1],
        ];
        Db::name('forums')->insertAll($forums);

        // 写入管理员信息
        $output->writeln("=============Mltree Forum Install 3/4=============");
        $output->writeln("");
        $output->writeln(\date('Y-m-d H:i:s', time()) . ' Inserting Administrators Information');
        Db::name('users')->insert([
            'gid' => 1,
            'nick' => 'Administrators',
            'email' => $email,
            'password' => password_hash($pwd, PASSWORD_DEFAULT),
            'create_ip' => 'localhost',
            'create_time' => time(),
            'update_time' => time(),
            'status' => 1,
        ]);

        // 指令输出
        $output->writeln("=============Mltree Forum Install 4/4=============");
        $output->writeln("");
        $output->writeln(\date('Y-m-d H:i:s', time()) . ' MlTreeForum Install successfully!');

    }
}
