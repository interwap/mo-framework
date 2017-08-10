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
 * NAMING CONVENTION
 * CLASSES      PascalCase
 * METHODS      lower_case
 * FUNCTIONS    lower_case
 * PROPERTIES   lower_case
 * VARIABLE     lower_case
 *
 * Author: Ikomi Moses <muyexzy4shizzle@yahoo.com>
 *
 */

/**
 * Base Configurations.
 */
namespace App\Helpers;

class Config
{

  /**
   * [$server_name description]
   * @var [type]
   */
  private $server_name;

  /**
   * [$database_name description]
   * @var [type]
   */
  private $database_name;

  /**
   * [$username description]
   * @var [type]
   */
  private $username;

  /**
   * [$password description]
   * @var [type]
   */
  private $password;

  /**
   * [$charset description]
   * @var [type]
   */
  private $charset;

  /**
   * [$title description]
   * @var [type]
   */
  private $title;

  /**
   * [$show_errors description]
   * @var [type]
   */
  private $show_errors;
  
  /**
   * [get_server_name description]
   * @return [type] [description]
   */
  public function get_server_name() : string
  {
    return $this->server_name;
  }

  /**
   * [set_server_name description]
   * @param [type] $server_name [description]
   */
  public function set_server_name(string $server_name)
  {
    $this->server_name = $server_name;
  }

  /**
   * [get_database_name description]
   * @return [type] [description]
   */
  public function get_database_name() : string
  {
    return $this->database_name;
  }

  /**
   * [set_database_name description]
   * @param [type] $database_name [description]
   */
  public function set_database_name(string $database_name)
  {
    $this->database_name = $database_name;
  }

  /**
   * [get_username description]
   * @return [type] [description]
   */
  public function get_username() : string
  {
    return $this->username;
  }

  /**
   * [set_username description]
   * @param [type] $username [description]
   */
  public function set_username(string $username)
  {
    $this->username = $username;
  }

  /**
   * [get_password description]
   * @return [type] [description]
   */
  public function get_password() : string
  {
    return $this->password;
  }

  /**
   * [set_password description]
   * @param [type] $password [description]
   */
  public function set_password(string $password)
  {
    $this->password = $password;
  }

  /**
   * [get_charset description]
   * @return [type] [description]
   */
  public function get_charset() : string
  {
    return $this->charset;
  }

  /**
   * [set_harset description]
   * @param string $charset [description]
   */
  public function set_charset(string $charset)
  {
    $this->charset = $charset;
  }

  /**
   * [getTitle description]
   * @return [type] [description]
   */
  public function getTitle() : string
  {
      return $this->title;
  }

  /**
   * [setTitle description]
   * @param string $title [description]
   */
  public function setTitle(string $title)
  {
    $this->title = $title;
  }

  /**
   * [is_show_errors description]
   * @return boolean [description]
   */
  public function is_show_errors() : bool
  {
    return $this->show_errors;
  }

  /**
   * [set_show_errors description]
   * @param [type] $show_errors [description]
   */
  public function set_show_errors(bool $show_errors)
  {
    $this->show_errors = $show_errors;
  }

  /**
   * [set_time_zone description]
   * @param string $time_zone [description]
   */
  public function set_time_zone(string $time_zone)
  {
    date_default_timezone_set($time_zone);
  }
}