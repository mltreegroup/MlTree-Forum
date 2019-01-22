<?php
namespace app\common\model;

use app\common\model\Option;
use PHPMailer\PHPMailer\PHPMailer;
use think\Model;

class Mail extends Model
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
        $mailOptions = Option::getValues(["email"]);
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
        $url = Option::getValue('siteUrl');
        $content = [
            '{siteTitle}' => Option::getValue('siteTitle'),
            '{url}' => url('forum/user/Active', ['uid' => $userInfo->uid, 'code' => $code, 'time' => $time]),
            '{userName}' => $userInfo->username,
        ];
        $title = strtr('激活您的【 {siteTitle} 】账号', $content);
        $text = strtr(Option::getValue('active_mail_content'), $content);

        return $this->Send($userInfo->email, $userInfo->username, $title, $text);
    }

    public function SendForgetCode($userInfo, $code)
    {
        $content = [
            '{code}' => $code,
            '{siteTitle}' => Option::getValue('siteTitle'),
            '{userName}' => $userInfo->username,
        ];
        $title = strtr('找回您的【 {siteTitle} 】账号', $content);
        $text = strtr(Option::getValue('reset_mail_content'), $content);
        return $this->Send($userInfo->email, $userInfo->username, $title, $text);
    }
}
