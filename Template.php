<?php
namespace xlerr\sendcloud;

use Yii;
/**
 * 模板管理
 */
class Template extends Request
{
	public $debug = true;
	
	public function __construct()
	{
		if (!$this->user) {
			$this->user = Yii::$app->sendCloud->user;
		}
		if (!$this->key) {
			$this->key = Yii::$app->sendCloud->key;
		}
	}

	/**
	 * 查询模板
	 * @param  string $template_name 模板名称
	 * @return json
	 */
	public function select($template_name = '')
	{
		$this->url = 'https://sendcloud.sohu.com/webapi/template.get.json';
		return $this->request(['invoke_name' => $template_name]);
	}

	/**
	 * 删除模板
	 * @param  string $template_name 模板名称
	 * @return json
	 */
	public function delete($template_name)
	{
		$this->url = 'https://sendcloud.sohu.com/webapi/template.delete.json';
		return $this->request(['invoke_name' => $template_name]);
	}

	public function add()
	{

	}

	public function update()
	{

	}
}