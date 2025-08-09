<?php
// +----------------------------------------------------------------------
// | 邮件服务类
// +----------------------------------------------------------------------

namespace app\common\service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use think\facade\Config;

class MailService
{
    /**
     * 发送邮件
     * @param string $toEmail 收件人邮箱
     * @param string $subject 邮件主题
     * @param string $content 邮件内容
     * @return array 返回发送结果
     */
    public function sendMail($toEmail, $subject, $content)
    {
        try {
            // 获取邮件配置
            $mailConfig = Config::get('mail');
            
            // 实例化PHPMailer
            $mail = new PHPMailer(true);
            
            // 服务器设置
            $mail->isSMTP();
            $mail->Host       = $mailConfig['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailConfig['username'];
            $mail->Password   = $mailConfig['password'];
            $mail->SMTPSecure = $mailConfig['encryption'];
            $mail->Port       = $mailConfig['port'];
            $mail->CharSet    = 'UTF-8';
            
            // 发件人
            $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
            
            // 收件人
            $mail->addAddress($toEmail);
            
            // 邮件内容
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $content;
            
            // 发送邮件
            $mail->send();
            
            return ['status' => true, 'message' => '邮件发送成功'];
        } catch (Exception $e) {
            return ['status' => false, 'message' => '邮件发送失败: ' . $mail->ErrorInfo];
        }
    }
}