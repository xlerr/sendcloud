<?php 
namespace xlerr\sendcloud;

use Yii;

/**
 * 发送短信类
 */
class Sms {
    public $uid;
    public $pwd;

    public $url = 'http://sms.106jiekou.com/utf8/sms.aspx';
    public $worldurl = 'http://sms.106jiekou.com/utf8/worldapi.aspx';

    public $msg = [
        100 => 'send_success',                  // 发送成功
        101 => 'validation_fails',              // 验证失败
        102 => 'phone_number_format_error',     // 电话号码格式错误
        103 => 'membership_level_is_too_low',   // 会员级别不够
        104 => 'content_unaudited',             // 内容未审核
        105 => 'too_much_content',              // 内容太多
        106 => 'insufficient_account_balance',  // 账户余额不足
        107 => 'ip_restricted',                 // IP受限
        108 => 'send_too_frequently',           // 手机号码发送过于频繁
        109 => 'account_is_locked_out',         // 帐号被锁定
        110 => 'send_too_frequently',           // 手机号发送频率持续过高，黑名单屏蔽数日
        120 => 'system_upgrade',                // 系统升级
    ];

    function send($tel, $content)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'account'  => $this->uid,
            'password' => $this->pwd,
            'mobile'   => $tel,
            'content'  => rawurlencode($content),
        ]);
        $return = curl_exec($curl);
        if ($return === false) {
            Yii::error(curl_getinfo($curl), __METHOD__);
        }
        curl_close($curl);

        return $return;
    }
}