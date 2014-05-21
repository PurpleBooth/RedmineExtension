<?php

/*
 * This file is part of the Behat
 *
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

spl_autoload_register(function($class) {
    if (0 === strpos($class, 'PurpleBooth\\Behat\\RedmineExtension')) {
        require_once(__DIR__.'/src/'.str_replace('\\', '/', $class).'.php');

        return true;
    }
    if (0 === strpos($class, 'Redmine')) {
        require_once(__DIR__.'/vendor/kbsali/redmine-api/lib/'.str_replace('\\', '/', $class).'.php');

        return true;
    }
}, true, false);

return new PurpleBooth\Behat\RedmineExtension\DependencyInjection\PurpleBoothBehatRedmineExtension();
