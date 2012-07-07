<?php defined('SYSPATH') or die('No direct script access.');

// Render Debugger Toolbar on the end of application execution
if (Kohana::$config->load('debugger.register_shutdown_function') === true) {
    Debugger::init();
    register_shutdown_function(array('Debugger', 'shutdown'));
}

