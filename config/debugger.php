<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'enabled'                    => Kohana::$environment > Kohana::PRODUCTION,
    'register_shutdown_function' => true,
    'secret_key'                 => 'foobar',
    'panels'                     => array(
        'benchmarks'  => true,
        'database'    => false,
        'vars'        => true,
        'ajax'        => true,
        'files'       => true,
        'modules'     => true,
        'routes'      => true,
        'messages'    => true,
        'stacktraces' => true,
    ),
);
