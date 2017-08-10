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
 * Base Extension For Twig
 */
namespace App\Helpers;
use App\Helpers\Hash;

class Extension extends \Twig_Extension
{

    /**
     * [$hash description]
     * @var [type]
     */
    private $hashids;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->set_hashids("salt", 11);
    }

    /**
     * [get_hashids description]
     * @return [type] [description]
     */
    private function get_hashids()
    {
        return $this->hashids;
    }

    /**
     * [set_hashids description]
     * @param string $salt   [description]
     * @param int    $length [description]
     */
    protected function set_hashids(string $salt, int $length)
    {
        $this->hashids = new Hash($salt, $length,'bcdfghjklmnpqrstvwxyzBCDFGHJKLMPQRSTVWXYZ0123456789');
    }

    /**
     * [hash description]
     * @param  int    $number [description]
     * @return [type]         [description]
     */
    public function encode(int $value) : string
    {
        return $this->get_hashids()->encode($value);
    }

    /**
     * [decode description]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function decode(string $value) : int
    {
        return $this->get_hashids()->decode($value)[0];
    }  

	/**
     * [getFilters description]
     * @return [type] [description]
     */
	public function getFilters() : array
    {
        return array(
            new \Twig_SimpleFilter("hashids", array($this, "encode"))
        );
    }

    /**
     * [getName description]
     * @return [type] [description]
     */
    public function getName()
    {
        return 'twig_extension';
    }
}
