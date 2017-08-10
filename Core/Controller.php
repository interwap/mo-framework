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
 * Base Controller
 */

namespace Core;

abstract class Controller 
{

	/**
 	 * Parameters for the matched route
 	 * @var array
 	 */
	protected $route_params = [];

	/**
 	 * Class constructor
 	 * 
 	 * @param array $route_params Parameters from the route
 	 *
 	 * @return void
 	 */
	public function __construct($route_params)
	{ 
		$this->route_params = $route_params;
	}

	/**
 	 * Class constructor
 	 * 
 	 * @param array $route_params Parameters from the route
 	 *
 	 * @return void
 	 */
	public function __call($name, $args)
	{ 
		$method = $name . 'Action';

		if(method_exists($this, $method)){

			if($this->before() !== false){
				call_user_func_array([$this, $method], $args);
				$this->after();
			}
		} else {
			throw new \Exception("Method $method not found in controller " . get_class($this));
		}
	}

	/**
 	 * Before filter - called before an action method
 	 *
 	 * @return void
 	 */
	protected function before()
	{ 
	}

	/**
 	 * After filter - called after an action method
 	 *
 	 * @return void
 	 */
	protected function after()
	{
	}
}
