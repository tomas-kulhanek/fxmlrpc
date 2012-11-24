<?php
/**
 * Copyright (C) 2012
 * Lars Strojny, InterNations GmbH <lars.strojny@internations.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace FXMLRPC\Timing;

use FXMLRPC\Timing\TimingDecorator;

class TimingDecoratorTest extends \PHPUnit_Framework_TestCase
{
    private $wrapped;

    private $timer;

    private $decorator;

    private $functions;

    public function setUp()
    {
        $this->wrapped = $this
            ->getMockBuilder('FXMLRPC\ClientInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->timer = $this
            ->getMockBuilder('FXMLRPC\Timing\TimerInterface')
            ->getMock();
        $this->functions = \PHPUnit_Extension_FunctionMocker::start($this, __NAMESPACE__)
            ->mockFunction('microtime')
            ->getMock();
        $this->decorator = new TimingDecorator($this->wrapped, $this->timer);
    }

    public function testRecordTimeIsCalled()
    {
        $this->timer
            ->expects($this->once())
            ->method('recordTiming')
            ->with(0.1, 'method', array('arg1', 'arg2'));

        $this->functions
            ->expects($this->exactly(2))
            ->method('microtime')
            ->with(true)
            ->will($this->onConsecutiveCalls(1, 1.1));

        $this->wrapped
            ->expects($this->once())
            ->method('call')
            ->with('method', array('arg1', 'arg2'));

        $this->decorator->call('method', array('arg1', 'arg2'));
    }
}
