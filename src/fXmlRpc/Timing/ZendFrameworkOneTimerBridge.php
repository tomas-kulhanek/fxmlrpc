<?php
/**
 * Copyright (C) 2012-2016
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

namespace fXmlRpc\Timing;

use Zend_Log as Log;

final class ZendFrameworkOneTimerBridge extends AbstractTimerBridge
{
    /**
     * Create new Zend_Log bridge
     *
     * Allows passing custom log level and message template (with sprintf() control characters) for log message
     * customization
     *
     * @param Log           $logger
     * @param null|integer  $level
     * @param null|string   $messageTemplate
     */
    public function __construct(Log $logger, $level = null, $messageTemplate = null)
    {
        $this->logger = $logger;
        $this->setLevel($level, Log::DEBUG);
        $this->messageTemplate = $messageTemplate ?: $this->messageTemplate;
    }

    /** {@inheritdoc} */
    public function recordTiming($callTime, $method, array $arguments)
    {
        $this->logger->log(
            sprintf($this->messageTemplate, $callTime),
            $this->getLevel($callTime),
            ['xmlrpcMethod' => $method, 'xmlrpcArguments' => $arguments]
        );
    }
}
