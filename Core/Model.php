<?php
/**
 * Copyright 2017 Interwaptech, LTD.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Interwaptech.
 *
 * As with any software that integrates with the Interwaptech platform, your use
 * of this software is subject to the Interwaptech's Developer Principles and
 * Policies [http://developers.interwaptech.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */

/**
 * Base Model
 */
declare(strict_types=1);
namespace Core;
use App\Helpers\Config;

abstract class Model extends \App\Helpers\Helper
{	

	/**
	 * [$config description]
	 * @var [type]
	 */
	private $config;

  	/**
  	 * [$connection description]
  	 * @var null
  	 */
  	private $connection;

  	/**
  	 * [$request description]
  	 * @var [type]
  	 */
  	protected $request;

  	/**
  	 * [$result description]
  	 * @var [type]
  	 */
  	protected $result;

  	/**
  	 * [$session description]
  	 * @var [type]
  	 */
  	protected $session;

  	/**
  	 * [__construct description]
  	 */
  	public function __construct()
  	{	
  		/**
  		 * [$this->config global site configuration]
  		 * @var Config
  		 */
  		$this->config = new Config;
  		$this->config->set_server_name("localhost");
  		$this->config->set_database_name("mercury");
  		$this->config->set_username("root");
  		$this->config->set_password("");
  		$this->config->set_charset("utf8");
  		$this->config->set_time_zone("Africa/Accra");

  		/*
  		 * Other settings
  		 */
  		$this->set_hashids("salt", 11);
  		$this->set_encryption(12, false);
  		$this->get_connection();
  		$this->create_table();
  	}

  	/**
	 * [create_table description]
	 * @return [type] [description]
	 */
	abstract public function create_table();

	/**
	 * [get_connection description]
	 * @return [type] [description]
	 */
  	private function get_connection()
  	{
  		try {

  			if(empty($this->connection)) {
  				$this->connection = new \PDO('mysql:host=' . $this->config->get_server_name() . '; dbname=' . $this->config->get_database_name() , $this->config->get_username(), $this->config->get_password());
  				$this->connection->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_NATURAL);
  				$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  				$this->connection->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
  			}

  			return $this->connection;
    	} catch (PDOException $e) {
     		echo $e->getMessage();
     		die();
    	}
  	}

	/**
	 * [begin_transaction description]
	 * @return [type] [description]
	 */
	public function begin_transaction()
	{
		return $this->get_connection()->beginTransaction();
	}

	/**
	 * [in_transaction description]
	 * @return [type] [description]
	 */
	public function in_transaction()
	{
		return $this->get_connection()->inTransaction();
	}

	/**
	 * [use_transaction description]
	 * @return [type] [description]
	 */
	public function use_transaction()
	{
		return $this->get_connection()->useTransaction();
	}

	/**
	 * [rollback description]
	 * @return [type] [description]
	 */
	public function rollback()
	{
		return $this->get_connection()->rollBack();
	}

	/**
	 * [commit description]
	 * @return [type] [description]
	 */
	public function commit()
	{
		return $this->get_connection()->commit();
	}

	/**
	 * [last_inserted_id description]
	 * @return [type] [description]
	 */
	public function last_inserted_id()
	{
		return $this->get_connection()->lastInsertId();
	}

  	/**
  	 * [bind_param description]
  	 * @param  [type] $Parameter [description]
  	 * @return [type]            [description]
  	 */
  	private function bind_param($parameter)
  	{
  		switch (strtolower($parameter)) {

	  		case 'int':
	  			return \PDO::PARAM_INT;
	  			break;

	  		case 'str':
	  			return \PDO::PARAM_STR;
	  			break;

	  		case 'bool':
	  			return \PDO::PARAM_BOOL;
	  			break;

	  		case 'null':
	  			return \PDO::PARAM_NULL;
	  			break;
  		}
  	}

  	/**
  	 * [prepare description]
  	 * @param  [type] $query [description]
  	 * @return [type]        [description]
  	 */
  	private function prepare($query)
  	{
	  	if (($result = $this->connection->prepare($query)) != false) {
	  		return $result;
	  	} else {
	  		return false;
	  	}
	}

	/**
  	 * @return database result post routing
  	 */
  	private function execute_request($query, array $bind_params = [], $method = '')
  	{
  		$result = $this->prepare($query);
		if ($result != false) {

			if(count($bind_params) > 0) {
				foreach ($bind_params as $value) {
					$result->bindParam(":{$value[0]}", $value[1], $this->bind_param($value[2]));
				}
			}

			$result->execute();

			switch ($method) {

				case 'fetch':
					$row = $result->fetch(\PDO::FETCH_OBJ);
					return $row;
					break;

				case 'fetchAll':
					$row = $result->fetchAll(\PDO::FETCH_OBJ);
					return $row;
					break;

				case 'execute':
					return true;
					break;

				default:
					return false;
					break;
			}
		} else {
			return false;
		}
	}

	/**
	 * [fetch description]
	 * @param  [type] $query       [description]
	 * @param  array  $bind_params [description]
	 * @return [type]              [description]
	 */
	public function fetch($query, array $bind_params = [])
	{
		return $this->execute_request($query, $bind_params , 'fetch');
	}

	/**
	 * [fetchAll description]
	 * @param  [type] $query       [description]
	 * @param  array  $bind_params [description]
	 * @return [type]              [description]
	 */
	public function fetchAll($query, array $bind_params = [])
	{
		return $this->execute_request($query, $bind_params , 'fetchAll');
	}

	/**
	 * [execute description]
	 * @param  [type] $query       [description]
	 * @param  array  $bind_params [description]
	 * @return [type]              [description]
	 */
	public function execute($query, array $bind_params = [])
	{
		return $this->execute_request($query, $bind_params , 'execute');
	}
}
