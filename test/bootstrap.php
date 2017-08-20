<?php

/*
 * This file is part of the easelaboratory/broadway-extensions package.
 *
 * (c) easelab.it <os@easelab.it>
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
