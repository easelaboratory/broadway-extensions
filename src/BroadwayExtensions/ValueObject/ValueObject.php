<?php
/*
 * This file is part of the easelaboratory/broadway-extensions package.
 *
 * (c) easelab.it <os@easelab.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayExtensions\ValueObject;

interface ValueObject extends \JsonSerializable
{
    public function __toString();
}
