<?php 
declare(strict_types=1);
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
 * Front Controller
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

/**
 * Composer - Libraries
 */
require_once ROOT . '/vendor/autoload.php';

/**
 * Twig Autoloaded
 * Not required in this version of Twig
 */
//Twig_Autoloader::register();

/**
 * Autoloader
 */
spl_autoload_register(function ($class)
{
	$file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
	if(is_readable($file)){
		require $file;
	}
});

/**
 * [$config description]
 * @var App
 */
$config = new App\Helpers\Config;
$config->set_show_errors(false);

/**
* Error and Exception handling 
*/
$error = new Core\Error($config);
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * [$router description]
 * @var Core
 */
$router = new Core\Router;

/**
 * Add route to dispatcher
 */
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('index', ['controller' => 'Home', 'action' => 'index']);

$router->add('{controller}/{action}');

// Match the requested route
$url = $_SERVER['QUERY_STRING'];
$router->dispatch($url);




