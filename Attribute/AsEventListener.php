<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\EventDispatcher\Attribute;

/**
 * Service tag to autoconfigure event listeners.
 *
 * @author Alexander M. Turek <me@derrabus.de>
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class AsEventListener
{
    public ?string $event = null;

    public ?string $method = null;

    public int $priority = 0;

    public ?string $dispatcher = null;

    public function __construct(
        ?string $event = null,
        ?string $method = null,
        int $priority = 0,
        ?string $dispatcher = null,
    ) {
        $this->event = $event;
        $this->method = $method;
        $this->priority = $priority;
        $this->dispatcher = $dispatcher;
    }
}
