<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Home\HomeModel;
use App\Helpers\Session;
use \Core\View;

class About extends \Core\Controller
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
		$this->session = new Session;
		$this->session->set_session_save_handler($this->session);
		$this->session->start_session();
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
		//View::renderTemplate('Home/index.html', ['title' => 1]);
		//echo $this->model->encode(1);
		echo "Hi, just testing this bull";
		//$this->session->add_value("name", "ikomi moses");
	}

	public function aboutAction()
	{
		echo "this is the about us page of about controller";
		$this->session->get_session_info();
		if($this->session->destroy($this->session->get_id())){

			echo "yay, deleted";
		} else {
			echo "!sad face";
		}
	}
}
