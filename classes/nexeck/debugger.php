<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Debugger module for Kohana
 *
 * @package   Kohana/Debugger
 * @author    Marcel Beck <marcel.beck@outlook.com>
 * @copyright (c) 2012 Marcel Beck
 */
abstract class Nexeck_Debugger
{
    /**
     * Formatted files informations
     *
     * @var array
     */
    protected static $_files = array();

    /**
     * Formatted modules informations
     *
     * @var array
     */
    protected static $_modules = array();

    /**
     * Formatted routes informations
     *
     * @var array
     */
    protected static $_routes = array();

    /**
     * Formatted database queries
     *
     * @var StdClass
     */
    protected static $_database_queries;

    /**
     * Formatted application benchmark
     *
     * @var StdClass
     */
    protected static $_benchmark_application;

    /**
     * Formatted benchmark groups
     *
     * @var array
     */
    protected static $_benchmark_groups = array();

    /**
     * Formatted messages
     *
     * @var array
     */
    protected static $_messages = array();

    /**
     * Formatted stacktraces
     *
     * @var array
     */
    protected static $_stacktraces = array();

    /**
     * @var bool
     */
    public static $enabled = true;

    /**
     * Function calls which should be removed from backtraces
     *
     * @var array
     */
    protected static $_excluded_backtrace_functions = array(
        'dump_var',
        'add_stack_trace',
    );

    /**
     * Toolbar template
     *
     * @var string
     */
    protected static $_toolbar_template = 'debugger/toolbar';

    /**
     * @var Config_Group|StdClass
     */
    protected static $_config;

    /**
     * Initialisize Config
     *
     * @static
     */
    public static function init()
    {
        Debugger::$_config = Kohana::$config->load('debugger');
        Debugger::$enabled = Debugger::$_config->enabled;
    }

    /**
     * Render Debugger Toolbar
     *
     * <code>
     * Debugger::render();
     * </code>
     *
     * @static
     */
    public static function render_toolbar()
    {
        if (Debugger::_is_enabled() === true) {
            $template = new View(Debugger::$_toolbar_template);

            // Javascript for toolbar
            $template->set('scripts', file_get_contents(Kohana::find_file('views', 'debugger/toolbar', 'js')));

            // CSS for toolbar
            $template->set('styles', file_get_contents(Kohana::find_file('views', 'debugger/toolbar', 'css')));

            Debugger::_collect_data();

            echo $template->render();
        }
    }

    /**
     * Collect the data
     *
     * @static
     */
    protected static function _collect_data()
    {
        Debugger::get_benchmark_application();

        if (Kohana::$config->load('debugger.panels.benchmarks')) {
            Debugger::get_benchmark_groups();
        }

        if (Kohana::$config->load('debugger.panels.database')) {
            Debugger::get_database_queries();
        }

        if (Kohana::$config->load('debugger.panels.files')) {
            Debugger::get_files();
        }

        if (Kohana::$config->load('debugger.panels.modules')) {
            Debugger::get_modules();
        }

        if (Kohana::$config->load('debugger.panels.routes')) {
            Debugger::get_routes();
        }

        if (Kohana::$config->load('debugger.panels.messages')) {
            Debugger::get_messages();
        }

        if (Kohana::$config->load('debugger.panels.stacktraces')) {
            Debugger::get_stacktraces();
        }
    }

    /**
     * Get list of included files
     *
     * <code>
     * Debugger::get_files()
     * </code>
     *
     * @return array file currently included by php
     */
    public static function get_files()
    {
        if (empty(Debugger::$_files) === false) {
            return Debugger::$_files;
        }

        $files = array();

        $included_files = (array) get_included_files();
        sort($included_files);
        foreach ($included_files as $key => $file) {
            $path = Debugger::_replace_paths($file);

            $files[++$key] = array(
                'path'  => $path,
                'size'  => filesize($file),
                'lines' => count(file($file)),
            );
        }

        return Debugger::$_files = $files;
    }

    /**
     * Get module list
     *
     * <code>
     * Debugger::get_modules()
     * </code>
     *
     * @return array  module_name => module_path
     */
    public static function get_modules()
    {
        if (empty(Debugger::$_modules) === false) {
            return Debugger::$_modules;
        }

        $modules = Kohana::modules();
        foreach ($modules as $name => $path) {
            $path = Debugger::_replace_paths($path);

            $modules[$name] = $path;
        }

        return Debugger::$_modules = $modules;
    }

    /**
     * Get all application routes
     *
     * <code>
     * Debugger::get_routes()
     * </code>
     *
     * @return array
     */
    public static function get_routes()
    {
        if (empty(Debugger::$_routes) === false) {
            return Debugger::$_routes;
        }

        $routes = array();

        /**
         * @var Route $route
         */
        foreach (Route::all() as $name => $route) {
            $reflection_class = new ReflectionClass('Route');

            $reflection_property_route_regex = $reflection_class->getProperty('_route_regex');
            $reflection_property_route_regex->setAccessible(true);

            $reflection_property_uri = $reflection_class->getProperty('_uri');
            $reflection_property_uri->setAccessible(true);

            $routes[] = array(
                'name'        => $name,
                'current'     => (($route == Request::initial()->route()) ? true : false),
                'route_regex' => $reflection_property_route_regex->getValue($route),
                'uri'         => $reflection_property_uri->getValue($route),
            );
        }

        return Debugger::$_routes = $routes;
    }

    /**
     * Get all session data
     *
     * <code>
     * Debugger::get_session()
     * </code>
     *
     * @static
     * @return array
     */
    public static function get_session()
    {
        $session = Session::instance()->as_array();
        ksort($session);

        return $session;
    }

    /**
     * Get query data
     *
     * <code>
     * Debugger::get_query()
     * </code>
     *
     * @return array
     */
    public static function get_query()
    {
        $get = Request::initial()->query();
        ksort($get);

        return $get;
    }

    /**
     * Get post data
     *
     * <code>
     * Debugger::get_post()
     * </code>
     *
     * @return array
     */
    public static function get_post()
    {
        $post = Request::initial()->post();
        ksort($post);

        return $post;
    }

    /**
     * Get param data
     *
     * <code>
     * Debugger::get_param()
     * </code>
     *
     * @static
     * @return array
     */
    public static function get_param()
    {
        $post = Request::initial()->param();
        ksort($post);

        return $post;
    }

    /**
     * Retrieves query benchmarks from database
     *
     * <code>
     * Debugger::get_database_queries()
     * </code>
     *
     * @return  array
     */
    public static function get_database_queries()
    {
        if (!class_exists('Database')) {
            return array();
        }
        if (Debugger::$_database_queries !== null) {
            return Debugger::$_database_queries;
        }

        $result = array();

        $groups = Profiler::groups();
        foreach (Database::$instances as $name => $db) {
            $group_name = 'database (' . strtolower($name) . ')';
            $group      = arr::get($groups, $group_name, false);

            if ($group) {
                $sub_time = $sub_memory = $sub_count = 0;
                foreach ($group as $query => $tokens) {
                    $sub_count += count($tokens);
                    foreach ($tokens as $token) {
                        $total = Profiler::total($token);
                        $sub_time += $total[0];
                        $sub_memory += $total[1];
                        $result[$name][] = array(
                            'name'   => $query,
                            'time'   => $total[0],
                            'memory' => $total[1]
                        );
                    }
                }
            }
        }

        return Debugger::$_database_queries = $result;
    }

    /**
     * Get application benchmark
     *
     * <code>
     * Debugger::get_benchmark_application()
     * </code>
     *
     * @static
     * @return StdClass
     */
    public static function get_benchmark_application()
    {
        if (Debugger::$_benchmark_application !== null) {
            return Debugger::$_benchmark_application;
        }

        $benchmark_application = new StdClass();

        $profiler_application = Profiler::application();

        $benchmark_application->min     = $profiler_application['min'];
        $benchmark_application->max     = $profiler_application['max'];
        $benchmark_application->total   = $profiler_application['total'];
        $benchmark_application->average = $profiler_application['average'];
        $benchmark_application->current = $profiler_application['current'];

        return Debugger::$_benchmark_application = $benchmark_application;
    }

    /**
     * Get benchmark groups
     *
     * <code>
     * Debugger::get_benchmark_groups()
     * </code>
     *
     * @return array formatted benchmarks
     */
    public static function get_benchmark_groups()
    {
        if (Kohana::$profiling === false) {
            return array();
        }

        if (empty(Debugger::$_benchmark_groups) === false) {
            return Debugger::$_benchmark_groups;
        }

        $groups = Profiler::groups();
        $result = array();
        foreach (array_keys($groups) as $group) {
            if (strpos($group, 'database (') === false) {
                foreach ($groups[$group] as $name => $marks) {
                    $result[$group][] = array(
                        'name'       => $name,
                        'count'      => count($marks),
                        'stats'      => Profiler::stats($marks),
                    );
                }
            }
        }

        return $result;
    }

    /**
     * Add a message
     *
     * <code>
     * Debugger::add_message('Foobar')
     * </code>
     *
     * @static
     *
     * @param string $message
     */
    public static function add_message($message)
    {
        $call_stack = debug_backtrace();

        $idx = 0;

        // Get max id of 'add' debug functions
        foreach ($call_stack as $lkey => $lvalue) {
            if (in_array($call_stack[$lkey]['function'], Debugger::$_excluded_backtrace_functions) === true) {
                $idx = $lkey;
            }
        }

        $file = Debugger::_replace_paths($call_stack[$idx]['file']);

        $message = array(
            'message'   => $message,
            'file'      => $file,
            'line'      => $call_stack[$idx]['line'],
            'function'  => $call_stack[$idx + 1]['function'],
            'class'     => $call_stack[$idx + 1]['class'],
            'call_type' => $call_stack[$idx + 1]['type'],
            'args'      => $call_stack[$idx + 1]['args'],
        );

        array_unshift(Debugger::$_messages, $message);
    }

    /**
     * Get messages
     *
     * <code>
     * Debugger::get_messages()
     * </code>
     *
     * @static
     * @return array
     */
    public static function get_messages()
    {
        return Debugger::$_messages;
    }

    /**
     * Dump var
     *
     * <code>
     * Debugger::dump_var(array('foo' => 'bar'))
     * </code>
     *
     * @static
     *
     * @param mixed $var
     */
    public static function dump_var($var)
    {
        if (is_array($var)) {
            $var_name = '(array) ';
        } elseif (is_object($var)) {
            $var_name = '(object) ' . get_class($var);
        } else {
            $var_name = 'Variable';
        }

        $dump            = Debug::dump($var);
        $html_element_id = md5($dump);

        Debugger::add_message(
            '<b><a href="#" onclick="debugToolbar.toggle(\'' . $html_element_id . '\'); return false;">dump of \'' . $var_name . '\'</a></b> :' . "\n" . '<pre id="' . $html_element_id . '" style="display:none">' . $dump . '</pre>',
            'Variable Dump'
        );
    }

    /**
     * Add a stracktrace
     *
     * <code>
     * Debugger::add_stacktrace()
     * </code>
     *
     * @static
     */
    public static function add_stacktrace()
    {
        $e          = new Exception;
        $stacktrace = ($e->getTraceAsString());

        array_unshift(Debugger::$_stacktraces, $stacktrace);
    }

    /**
     * Get stacktraces
     *
     * <code>
     * Debugger::get_stacktraces()
     * </code>
     *
     * @static
     * @return array
     */
    public static function get_stacktraces()
    {
        return Debugger::$_stacktraces;
    }

    /**
     * Check if xdebug is enabled
     *
     * <code>
     * Debugger::xdebug_enabled()
     * </code>
     *
     * @static
     * @return bool
     */
    public static function xdebug_enabled()
    {
        return extension_loaded('xdebug');
    }

    /**
     * @intern Replace paths
     *
     * @static
     *
     * @param string $path
     *
     * @return mixed
     */
    protected static function _replace_paths($path)
    {
        return str_replace(
            array(
                 APPPATH,
                 MODPATH,
                 SYSPATH
            ),
            array(
                 'application/',
                 'modules/',
                 'system/'
            ),
            $path
        );
    }

    /**
     * Shutdown function, which renders the toolbar
     *
     * @static
     */
    public static function shutdown()
    {
        Debugger::render_toolbar();
    }

    /**
     * Determines if all the conditions are correct to display the toolbar
     *
     * <code>
     * Debugger::is_enabled()
     * </code>
     *
     * @static
     * @return bool
     */
    protected static function _is_enabled()
    {
        // Auto render if secret key isset
        if ((Debugger::$_config->secret_key !== false) and (Request::initial()->query(Debugger::$_config->secret_key) !== null)) {
            return true;
        }

        if (Debugger::$enabled !== true) {
            return false;
        }

        // Don't auto render when in PRODUCTION (this can obviously be
        // overridden by the above secret key)
        if (Kohana::$environment === Kohana::PRODUCTION) {
            return false;
        }

        // Don't auto render toolbar for ajax requests
        if (Request::initial()->is_ajax()) {
            return false;
        }

        // Don't auto render toolbar for cli requests
        if (Kohana::$is_cli) {
            return false;
        }

        return true;
    }
}

