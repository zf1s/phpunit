<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2001-2014, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PHPUnit
 * @subpackage Util
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2001-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 3.7.45
 */

declare(ticks = 1);

// php-invoker is optional, so the class is only declared when it is installed - including this file
// without it (e.g. code coverage with processUncoveredFilesFromWhitelist) must not fail
if (class_exists('PHP_Invoker')) {
    /**
     * PHP_Invoker from the optional php-invoker 1.1 package, with invoke() using is_int()
     * instead of is_integer(), which is deprecated since PHP 8.6. php-invoker 1.1 is no longer maintained.
     *
     * Only instantiated after class_exists('PHP_Invoker'), so php-invoker stays optional.
     *
     * @package    PHPUnit
     * @subpackage Util
     * @author     Sebastian Bergmann <sebastian@phpunit.de>
     * @copyright  2001-2014 Sebastian Bergmann <sebastian@phpunit.de>
     * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
     * @link       http://www.phpunit.de/
     * @since      Class available since Release 3.7.45
     */
    class PHPUnit_Util_Invoker extends PHP_Invoker
    {
        /**
         * Invokes a callable and raises an exception when the execution does not
         * finish before the specified timeout.
         *
         * @param  callable                 $callable
         * @param  array                    $arguments
         * @param  int                      $timeout   in seconds
         * @return mixed
         * @throws InvalidArgumentException
         */
        public function invoke($callable, array $arguments, $timeout)
        {
            if (!is_callable($callable)) {
                throw new InvalidArgumentException;
            }

            if (!is_int($timeout)) {
                throw new InvalidArgumentException;
            }

            pcntl_signal(SIGALRM, array($this, 'callback'), TRUE);
            pcntl_alarm($timeout);

            $this->timeout = $timeout;

            try {
                $result = call_user_func_array($callable, $arguments);
            }

            catch (Exception $e) {
                pcntl_alarm(0);
                throw $e;
            }

            pcntl_alarm(0);

            return $result;
        }
    }
}
