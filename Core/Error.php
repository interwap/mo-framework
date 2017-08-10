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
 * Error and excepition handler
 */
namespace Core;

class Error
{
	/**
	 * [$config description]
	 * @var [type]
	 */
	private static $config;

	/**
	 * [__construct description]
	 * @param Config $config [description]
	 */
	public function __construct(\App\Helpers\Config $config)
	{
		self::$config = $config;
	}

	/**
	* Error handling. Convert all errors to Exceptions by throowing an ErrorException
	*
	* @param int $level Error level
	* @param string $message Error message
	* @param string $file Filename the error was raised in
	* @param int $line Line number in the file
	*
	* @return void
	*/
	public static function errorHandler($level, $message, $file, $line) 
	{
		if ( error_reporting() !== 0 ) { // to keepthe @ operator working
			throw new \ErrorException($message, 0, $level, $file, $line);
		}
	}

	/**
	* Exception handler.
	*
	* @param Exception $exception The exception
	*
	* @return void
	*/
	public static function exceptionHandler($exception) 
	{

		// Code is 404 (not found) or 500 (general error)
		$code = $exception->getCode();
		if ($code != 404) {
			$code = 500;
		}
		http_response_code($code);

		if ( self::$config->is_show_errors() ) {

			echo "<h1>Fatal error</h1>"; 
			echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
			echo "<p>Message: '" . $exception->getMessage() . "'</p>";
			echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
			echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
		} else {

			$log = ROOT . '/Logs/' . date('Y-m-d') . '.txt';
			ini_set('error_log', $log);

			$message = "Uncaught exception: '" . get_class($exception) . "'";
			$message .= " with message '" . $exception->getMessage() . "'";
			$message .= "\nStack trace: " . $exception->getTraceAsString();
			$message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();

			error_log($message);
			
			View::renderTemplate("Error/$code.html", [ 'title' => $code]);
		}	
	}
}