<?php
/*
 * This file is part of the francescotrucchia/soisy package.
 *
 * (c) Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayExtensions\EventSourcing;

use Broadway\Repository\Repository as BaseRepository;

interface Repository extends BaseRepository
{
    public function getType();
}
