<?php
namespace xlerr\sendcloud;

use Yii;
/**
 * 邮件发送
 * SendCloud
 *
 * 配置sendCloud
 * ~~~~~~~~
 * 'components' => []
 *　    'sendCloud' => [
 *　        'class'    => 'xlerr\sendcloud\Mail',
 *         'user'     => 'user',
 *         'key'      => 'key',
 *         'from'     => 'aaa@qq.com',
 *         'fromname' => 'from name',
 *     ],
 * ],
 * ~~~~~~~~
 *
 * @example
 * 单次发送实例 
 * ~~~~~~~~
 * (new Mail)->to('alskdjf@163.com')->compose('tempateName', 'subject', [
 *  'key1' => ['param1'],
 *  'key2' => ['param2'],
 * ]);
 * ~~~~~~~~
 *
 * 群发实例
 * ~~~~~~~~
 * (new Mail)->to(['alskdjf@163.com', 'aaaaa@163.com'])->compose('tempateName', 'subject', [
 *  'key1' => ['param1', 'param11'],
 *  'key2' => ['param2', 'param22'],
 * ]);
 * ~~~~~~~~
 */
class Mail extends Request
{
    /**
     * 邮件主题
     * @var string
     */
    public $subject;

    /**
     * 邮件模板名称
     * @var string
     */
    public $template;

    /**
     * 收件人列表
     * @var array
     */
    private $_to;

    /**
     * 邮件模板中的占位符替换值(不同模板有不同的占位符)
     * @var array
     */
    private $_sub;

    /**
     * 发送者
     * @var string
     */
    public $from = '';

    /**
     * 发送者名字
     * @var string
     */
    public $fromname = '';

    /**
     * 调用该方法发送邮件
     * @return json
     */
    public function send()
    {
        if ($this->beforeSend() !== true) {
            return false;
        }

        return $this->request([
            'from'                 => $this->from,
            'fromname'             => $this->fromname,
            'template_invoke_name' => $this->template,
            'subject'              => $this->subject,
            'resp_email_id'        => 'true',
            'substitution_vars'    => json_encode([
                'to'  => $this->_to,
                'sub' => $this->_sub,
            ]),
        ]);
    }

    /**
     * 发送邮件之前需要验证相关数据
     * 
     * @return return boolean
     */
    public function beforeSend()
    {
        if (empty($this->subject)) {
            $this->setError('subject cannot be null');
            return false;
        }
        if (empty($this->template)) {
            $this->setError('template cannot be null');
            return false;
        }

        // 获取收件人数量
        $recipientCount = count($this->_to);

        if ($recipientCount < 1) {
            $this->setError('Please set the recipient');
            return false;
        }
        return true;
    }

    public function To($to)
    {
        $this->_to = (array) $to;

        return $this;
    }

    public function getTo()
    {
        return $this->_to;
    }

    public function Sub($sub)
    {
        $this->_sub = $sub;

        return $this;
    }

    public function getSub()
    {
        return $this->_sub;
    }

    public function compose($template, $subject, $sub)
    {
        $this->template = $template;
        $this->subject = $subject;
        $this->_sub = $sub;

        return $this;
    }
}