<?php

/**
 * Custom theme module to modify the currently selected theme easily.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 *
 * @author    Stephen Nielson <snielson@discoverandchange.com>
 * @copyright Copyright (c) 2023 Discover And Change, Inc. <snielson@discoverandchange.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace DiscoverAndChange\Modules\CustomManningTheme;

use OpenEMR\Core\ModulesClassLoader;

/**
 * @global ModulesClassLoader $classLoader
 */
$classLoader->registerNamespaceIfNotExists("DiscoverAndChange\\Modules\\CustomManningTheme\\", __DIR__ . DIRECTORY_SEPARATOR . "src");
/**
 * @global EventDispatcher $eventDispatcher Injected by the OpenEMR module loader;
 */
$bootstrap = new Bootstrap($eventDispatcher, $GLOBALS['kernel']);
$bootstrap->subscribeToEvents();
