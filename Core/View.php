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
 * Base View
 */
namespace Core;
use App\Helpers\Extension;

class View 
{

	/**
 	 * Render a view file
 	 * 
 	 * @param string $view The view file
 	 *
 	 * @return void
 	 */
	public static function render($view, $args = [])
	{

		extract($args, EXTR_SKIP);

		$file = "../App/Views/$view"; // relative to Core directory

		if(is_readable($file)){
			require $file;
		} else {
			throw new \Exception("$file not found");
		}
	}

	/**
 	 * Render a view template using Twig
 	 * 
 	 * @param string $template The template file
 	 * @param array $args Associative array of data to display in the view (optional)
 	 *
 	 * @return void
 	 */
	public static function renderTemplate($template, $args = [])
	{
		static $twig = null;

		if($twig === null){
			$loader = new \Twig_Loader_Filesystem('../App/Views');
			$twig = new \Twig_Environment($loader, array( 'debug' => true));

			if(isset($_SESSION)){
				$twig->addGlobal('session', $_SESSION);
			}
			
			$twig->addExtension(new Extension());
			$twig->addExtension(new \Twig_Extension_Debug());
		}

		echo $twig->render($template, $args);
	}

}


?>