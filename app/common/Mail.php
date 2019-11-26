<?php
namespace app\common;

use app\model\Options;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{

    public $fromName;
    public $fromAdress;
    public $smtpHost;
    public $smtpPort;
    public $replyTo;
    public $smtpUser;
    public $smtpPass;
    public $encriptionType;
    public $errorMsg;

    public function __construct()
    {
        $mailOptions = Options::getValues(["email"]);
        $this->fromName = $mailOptions["fromName"];
        $this->fromAdress = $mailOptions["fromAdress"];
        $this->smtpHost = $mailOptions["smtpHost"];
        $this->smtpPort = $mailOptions["smtpPort"];
        $this->replyTo = $mailOptions["replyTo"];
        $this->smtpUser = $mailOptions["smtpUser"];
        $this->smtpPass = $mailOptions["smtpPass"];
        $this->encriptionType = $mailOptions["encriptionType"];
    }

    public function Send($to, $name, $title, $content)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $this->smtpHost;
        if (!empty($this->encriptionType) && $this->encriptionType != "no") {
            $mail->SMTPSecure = $this->encriptionType;
        }
        $mail->Port = $this->smtpPort;
        $mail->CharSet = 'UTF-8';
        $mail->FromName = $this->fromName;
        $mail->Username = $this->smtpUser;
        $mail->Password = $this->smtpPass;
        $mail->From = $this->fromAdress;
        $mail->isHTML(true);
        $mail->addAddress($to, $name);
        $mail->Subject = $title;
        $mail->Body = $content;
        $status = $mail->send();
        if (!$status) {
            $this->errorMsg = $mail->ErrorInfo;
            return false;
        }
        return true;
    }

    public function SendActiveLink($userInfo, $code, $time)
    {
        $url = Options::getValue('siteUrl');
        $content = [
            '[siteTitle]' => Options::getValue('siteTitle'),
            '[url]' => \url('user/activation', ['uid' => $userInfo->uid, 'code' => $code, 'time' => $time]),
            '[userName]' => $userInfo->username,
        ];
        $title = strtr('激活您的【[siteTitle]】账号', $content);
        $_raw = \file_get_contents(public_path() . 'public' . \config('view.view_depr') . 'template' . \config('view.view_depr') . 'activeMail.html');
        $text = strtr($_raw, $content);

        return $this->Send($userInfo->email, $userInfo->username, $title, $text);
    }

    public function SendForgetCode($userInfo, $code, $time)
    {
        $content = [
            '[url]' => \url('user/forget', ['uid' => $userInfo->uid, 'code' => $code, 'time' => $time]),
            '[siteTitle]' => Options::getValue('siteTitle'),
            '[userName]' => $userInfo->username,
        ];
        $title = strtr('找回您的【[siteTitle]】账号', $content);
        $_raw = \file_get_contents(public_path() . 'public' . \config('view.view_depr') . 'template' . \config('view.view_depr') . 'forgetMail.html');
        $text = strtr($_raw, $content);

        return $this->Send($userInfo->email, $userInfo->username, $title, $text);
    }
}
