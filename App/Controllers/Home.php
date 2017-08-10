<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Home\HomeModel;
use App\Helpers\Session;
use \Core\View;

class Home extends \Core\Controller
{	

	/**
	 * [$model description]
	 * @var [type]
	 */
	private $model;

	/**
	 * [before description]
	 * @return [type] [description]
	 */
	protected function before()
	{ 
		$this->model = new HomeModel;
		//$this->session = new Session;
		//$this->session->set_session_save_handler($this->session);
		//$this->session->start_session();
	}

	/**
	 * [after description]
	 * @return [type] [description]
	 */
	protected function after()
	{
	}

	/**
	 * [indexAction description]
	 * @return [type] [description]
	 */
	public function indexAction()
	{
		View::renderTemplate('Home/index.html', ['title' => "Welcome to Mo"]);
	}
}
