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
  * Session Handler
  */
namespace App\Helpers;

class Session extends \Core\Model implements \SessionHandlerInterface 
{
	/**
	 * [$use_transaction description]
	 * @var [type]
	 */
	private $use_transaction;

	/**
	 * [$collect_garbage description]
	 * @var boolean
	 */
  	private $collect_garbage = false;

  	/**
  	 * [set_session_save_handler description]
  	 * @param Session $session_handler   [description]
  	 * @param bool    $register_shutdown [description]
  	 */
  	public function set_session_save_handler(Session $session_handler, bool $register_shutdown = true) : bool
  	{
  		$this->use_transaction = $register_shutdown;
  		return session_set_save_handler($session_handler, $register_shutdown);
  	}

  	/**
  	 * [start_session description]
  	 * @return [type] [description]
  	 */
  	public function start_session()
  	{
  		session_start();
  	}

  	/**
  	 * [create_table description]
  	 * @return [type] [description]
  	 */
	public function create_table() : bool
	{
		$this->request = $this->execute(
		  "create table if not exists sessions(
		  session_id varchar(128) not null primary key default '',
		  session_data text not null,
		  user_id bigint(20) unsigned not null default '0',
		  token varchar(10) not null default '',
		  start datetime not null default '0000-00-00 00:00:00',
		  expire datetime not null default '0000-00-00 00:00:00')
		  CHARACTER SET utf8, COLLATE utf8_general_ci"
		);

	  return $this->request;
	}
	
	/**
	 * [open description]
	 * @param  [type] $save_path    [description]
	 * @param  [type] $session_name [description]
	 * @return [type]               [description]
	 */
	public function open($save_path, $session_name)
	{
	  return true;
	}
	
	/**
	 * [close description]
	 * @return [type] [description]
	 */
	public function close()
	{
	  if($this->in_transaction()){
	    $this->commit();
	  }
	  return true;
	}

	/**
	 * [read description]
	 * @param  [type] $session_id [description]
	 * @return [type]             [description]
	 */
	public function read($session_id)
	{
	  	try {

		    if($this->use_transaction){

		    	//Default isolation, REPEATABLE READ, causes deadlock for different sessions
		      	$this->execute("SET TRANSACTION ISOLATION LEVEL READ COMMITTED");
		      	$this->begin_transaction();

		      	$this->request  = $this->fetch("SELECT * from sessions where session_id = :sid FOR UPDATE", [
		        	['sid', $session_id , 'str']
		      	]);
		      	
		      	if($this->request){
			        if($this->show_time() < $this->request->expire){
			          return $this->request->session_data;
			        } else {
			          return "";
			        }
			    } else {
			    	return "";
			    }
		    }
	  	} catch (PDOException $e) {
		    if($this->in_transaction()){
		      $this->rollback();
		    }
	    	throw $e;
	    }
	}

	/**
	 * [write description]
	 * @param  [type] $session_id   [description]
	 * @param  [type] $session_data [description]
	 * @return [type]               [description]
	 */
	public function write($session_id, $session_data)
	{
	  try {

	    $this->request = $this->execute("insert into sessions (session_id, session_data, token, start, expire)
	    values (:sid, :ssd, :tk, :st, :ex) ON DUPLICATE KEY UPDATE session_data = :ssd, start = :st, expire = :ex",
	    [ ['sid', $session_id, 'str'],
	    ['ssd', $session_data, 'str'],
	    ['tk', $this->create_token(10), 'str'],
	    ['st', $this->show_time(), 'str'],
	    ['ex', $this->set_time_in_hours(24), 'str'] ]);

	    return $this->request;

	  } catch (PDOException $e){
	    if($this->in_transaction()){
	      $this->rollBack();
	    }
	    throw $e;
	  }

	}

	/**
	 * [destroy description]
	 * @param  [type] $session_id [description]
	 * @return [type]             [description]
	 */
	public function destroy($session_id) : bool
	{

	  try {
	    $this->request = $this->execute("delete from sessions where session_id = :sid",
	    [ ['sid', $session_id, 'str'] ]);

	    return $this->request;

	  } catch (PDOException $e){
	    if($this->in_transaction()){
	      $this->rollBack();
	    }
	    throw $e;
	  }
	}

	/**
	 * [gc description]
	 * @param  [type] $max_life_time [description]
	 * @return [type]                [description]
	 */
	public function gc($max_life_time) : bool
	{
	  $this->request = $this->execute("delete from sessions where expire < :now",
	  [ ['now', $this->show_time(), 'str'] ]);
	  return $this->request;
	}

	/**
	 * [get_id description]
	 * @return [type] [description]
	 */
	public function get_id() : string
	{
		return session_id();
	}

	/**
	 * [set_value description]
	 * @param [type] $key   [description]
	 * @param [type] $value [description]
	 */
	public function set_value($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * [get_value description]
	 * @param  [type]  $key   [description]
	 * @param  boolean $value [description]
	 * @return [type]         [description]
	 */
	public function get_value($key, $value = false)
	{
	  if($value){
	    if(isset($_SESSION[$key][$value])){
	      return $_SESSION[$key][$value];
	    }
	  } else {
	    if(isset($_SESSION[$key])){
	      return $_SESSION[$key];
	    }
	  }
	  return false;
	}

	/**
	 * [delete_value description]
	 * @param  [type]  $key   [description]
	 * @param  boolean $value [description]
	 * @return [type]         [description]
	 */
	public function delete_value($key, $value = false)
	{
	  if($value){
	    if(isset($_SESSION[$key][$value])){
	      unset($_SESSION[$key][$value]);
	    }
	  } else {
	    if(isset($_SESSION[$key])){
	      unset($_SESSION[$key]);
	    }
	  }
	}

	/**
	 * [get_session_info description]
	 * @return [type] [description]
	 */
	public function get_session_info()
	{
  		echo '<pre>';
  		print_r($_SESSION);
  		echo '</pre>';
  	}





}


