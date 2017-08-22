<?php

/*
 * This file is part of the francescotrucchia/broadway-extensions package.
 *
 * (c) Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (file_exists($file = __DIR__ . '/../vendor/autoload.php')) {
    $loader = require $file;
    $loader->add('BroadwayExtensions', __DIR__);
} else {
    throw new RuntimeException('Install dependencies to run test suite.');
}
