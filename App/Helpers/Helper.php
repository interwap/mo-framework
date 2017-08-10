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
 * Base Helper.
 */
namespace App\Helpers;
use App\Helpers\Password;

abstract class Helper extends Extension
{

  /**
   * [$encryption description]
   * @var [type]
   */
  private $encryption;

  /**
   * [clean_value description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function clean_value($value)
  {
    $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    return $value;
  }

  /**
   * [create_friendly_url description]
   * @param  string $text [description]
   * @return [type]       [description]
   */
  public function create_friendly_url(string $text) : string
  {
    $text = strtolower($text);
    $text = preg_replace("/[^a-z0-9_\s-]/", "", $text);
    $text = preg_replace("/[\s-]+/", " ", $text);
    $text = preg_replace("/[\s_]/", "-", $text);
    return $text;
  }

  /**
   * [show_time description]
   * @return [type] [description]
   */
  public function show_time()
  {
    return date ("Y-m-d H:i:s", time());
  }

  /**
   * [set_time_to description]
   * @param [type] $hours [description]
   */
  public function set_time_in_hours(int $time)
  {
    return date ("Y-m-d H:i:s", strtotime("+{$time} hours"));
  }

  /**
   * [create_new_date description]
   * @param  string $date   [description]
   * @param  string $format [description]
   * @return [type]         [description]
   */
  public function set_date_format(string $date, string $format)
  {
    return date_format(date_create($date), $format);
  }

  /**
   * [create_token description]
   * @param  int    $length [description]
   * @return [type]         [description]
   */
  public function create_token(int $length) : string
  {
    $token = null;
    $string =  "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $max = strlen($string);
    for ($i=0; $i < $length; $i++){
      $token .= $string[$this->random_number_generator(0, $max - 1)];
    }
    return $token;
  }

  /**
   * [random_number_generator description]
   * @param  [type] $min [description]
   * @param  [type] $max [description]
   * @return [type]      [description]
   */
  private function random_number_generator($min, $max)
  {
    $range = $max - $min;
    if ($range < 1) return $min; //not so random
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; //length in bytes
    $bits = (int) $log + 1; //length in bit
    $filter = (int) (1 << $bits) - 1; //set all lower bit to 1
    do {
      $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
      $rnd = $rnd & $filter;
    } while ($rnd > $range);

    return $min + $rnd;
  }

  /**
   * [create_link description]
   * @param  string $url [description]
   * @return [type]      [description]
   */
  public function create_link(string $url)
  {
    return preg_replace('@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@', '<a href="http$2://$4" target="_blank">$1$2$3$4</a>', $url);
  }

  /**
   * [getEncryption description]
   * @return [type] [description]
   */
  private function get_encryption()
  {
    return $this->encryption;
  }

  /**
   * [set_encrpytion description]
   * @param int  $iteration       [description]
   * @param bool $portable_hashes [description]
   */
  protected function set_encryption(int $iteration, bool $portable_hashes)
  {
    $this->encryption = new Password($iteration, $portable_hashes);
  }

  /**
   * [hash_password description]
   * @param  string $value [description]
   * @return [type]        [description]
   */
  public function hash_password(string $value) : string
  {
    return $this->get_encryption()->hash_password($value);
  }

  /**
   * [validate_password description]
   * @param  string $value       [description]
   * @param  string $stored_hash [description]
   * @return [type]              [description]
   */
  public function validate_password(string $value, string $stored_hash) : bool
  {
    return $this->get_encryption()->validate_password($value, $stored_hash);
  }    
}
