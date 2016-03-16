<?php
namespace xlerr\sendcloud;

use Yii;

class Request
{
    /**
     * 调试开关
     * @var boolean
     */
    public $debug = false;

    /**
     * 请求地址（一般情况不需要修改）
     * @var string
     */
    public $url = 'https://sendcloud.sohu.com/webapi/mail.send_template.json';

    /**
     * 授权账号
     * @var string
     */
    public $user;

    /**
     * 授权 key
     * @var string
     */
    public $key;

    /**
     * 异常信息
     * @var string
     */
    private $error;

    /**
     * 缓存POST数据
     * @var array
     */
    private $_params;

    public function request($params = [])
    {
        if ($this->beforeRequest() === false) {
            return false;
        }

        $this->_params = (array) $params;

        $context  = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query(array_merge($this->_params, [
                    'api_user' => $this->user,
                    'api_key'  => $this->key,
                ])),
            ],
        ]);

        $result = file_get_contents($this->url, FILE_TEXT, $context);

        return $this->afterRequest($result);
    }

    public function beforeRequest()
    {
        if (!$this->url) {
            $this->setError('Cannot request a null URL!');
            return false;
        }
        if (!$this->user) {
            $this->setError('Authorization ID cannot be empty!');
            return false;
        }
        if (!$this->key) {
            $this->setError('Authorization token cannot be empty!');
            return false;
        }
        return true;
    }

    public function afterRequest($result)
    {
        $info = json_decode($result, true);
        if ($this->debug === true) {
            Yii::trace([
                'params'      => $this->_params,
                'result'      => $result,
                'result_json' => $info,
            ], __CLASS__);
        }
        if ($info === null) {
            $this->setError('Abnormal results');
            return false;
        }
        if ($info['message'] === 'error') {
            $this->setError($info['errors']);
            return false;
        }
        if ($info['message'] !== 'success') {
            $this->setError('Unknown error');
            return false;
        }
        return $info;
    }

    public function setError($error)
    {
        if ($this->debug) {
            Yii::error($error, __CLASS__);
        }
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }
}