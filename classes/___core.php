<?php

/**
 * LEZAZ - a Nice & portable PHP 5 framework
 *
 * @author      bassam alessawi <bassam.a.a.r@gmail.com|fb.com/bassam.essa>
 * @copyright   2015 LEZAZ
 * @link        http://lezaz.org
 * @license     MIT LICENSE
 * @version     1.0.0
 * @package     Lezaz
 */
// ------------------------------------------

/**
 * __CORE
 * @package  Lezaz
 * @author   bassam alessawi
 * @since    1.0.0
 */
Class __CORE {

    public $plugin;
    public $events = array();
    protected $output = null;
    public $main_template = 'index';
    private $valriables = array();

    public function __construct() {
        // get all plugin active inside $plugin
        $dir = PLUGIN_PATH;
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..' && filetype($dir . $file) == 'dir') {
                    if (!file_exists($dir . $file . '/RJCT'))
                        $this->plugin[] = $file;
                }
            }
            closedir($dh);
        }
    }

    public function __add($class, $name) {
        $this->$name = new $class;
    }

    public function set_tpl($tpl) {
        $this->main_template = $tpl;
    }

    public function __call($method, $args) {
        if (method_exists($this, $method)) {
            $reflection = new ReflectionMethod($this, $method);
            if (!$reflection->isPublic()) {
                throw new RuntimeException("Call to not public method " . get_class($this) . "::$method()");
            }

            return call_user_func_array(array($this, $method), $args);
        } else {
            throw new RuntimeException("Call to undefined method " . get_class($this) . "::$method()");
        }
    }

    public function encrypt($string) {
        if (!CRYPT_CACHE)
            return $string;
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, SALT, $string, MCRYPT_MODE_CBC, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"));
    }

    public function decrypt($string) {
        if (!CRYPT_CACHE)
            return $string;
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, SALT, base64_decode($string), MCRYPT_MODE_CBC, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
    }

    public function include_plugin($file) {
        global $lezaz;
        if (is_array($this->plugin)) {
            foreach ($this->plugin as $plg) {
                if (file_exists(PLUGIN_PATH . $plg . "/{$file}.php")) {
                    $this->trigger('before.load.' . $file . '.' . $plg, 'arg_1');
                    include(PLUGIN_PATH . $plg . "/{$file}.php");
                    //echo PLUGIN_PATH . $plg . "/{$file}.php";
                }
            }
        }
    }

    /*     * ******************************** Events/Hooks *********************************** */

    /**
     * Listen an event
     * @param   string      $tag
     * @param   callback    $callback
     * @param   integer     $prority
     * @return  void
     */
    public function listen($tag, $callback, $prority = 0) {
        $this->events[$tag][$prority][] = $callback;
        ksort($this->events[$tag]);
    }

    /**
     * Trigger event(s)
     * @param   string  $tag
     * @param   mixed   $params
     * @return  mixed
     */
    public function trigger($tag, $params = null, $default = null) {
        if (isset($this->events[$tag])) {
            $filtered = null;

            foreach (new ArrayIterator($this->events[$tag]) as $p => $callbacks) {
                foreach ($callbacks as $id => &$callback)
                    if (is_callable($callback))
                        $filtered = call_user_func_array($callback, array_merge((array) $params, array($filtered)));
            }

            return empty($filtered) ? $default : $filtered;
        }

        return $default;
    }

    /*     * ******************************* END/Events/Hooks/ ************************ */

    public function go($to, $using = 302) {
        $scheme = parse_url($to, PHP_URL_SCHEME);
        $to = (!empty($scheme) ? $to : ( ltrim($to, '/')));

        if (headers_sent())
            return call_user_func_array(__FUNCTION__, array($to, 'html'));

        @list($using, $after) = (array) explode(':', $using);

        switch (strtolower($using)):
            case 'html':
                echo('<meta http-equiv="refresh" content="' . (int) $after . '; URL=' . $to . '">');
                break;
            case 'js':
                echo('<script type="text/javascript">setTimeout(function(){window.location="' . $to . '";}, ' . (((int) $after) * 1000) . ');</script>');
                break;
            default:
                exit(header("Location: {$to}", true, $using));
        endswitch;
    }

    /**
     * Returns some statics
     * @return  object[float]
     */
    public function statics() {
        $statics = array();
        $statics['time'] = (float) round(microtime(1) - LEZAZ_START_TIME, 4);
        $statics['memory'] = (float) (memory_get_usage(1) / 1024);
        $statics['memory-peak'] = (float) (memory_get_peak_usage(1) - LEZAZ_START_PEAK_MEM);

        if (function_exists('sys_getloadavg')) {
            $sys_load = sys_getloadavg();
            $statics['cpu'] = $sys_load[0];
        }

        return $statics;
    }

    public function router($path, $callback) {
        if (is_array($path)) {
            foreach ($path as $pathx) {
                if ($this->router($pathx, $callback) != 'x') {
                    return '';
                }
            }
            return '';
        }
        $filtered = null;
        $vars = array(
            '@num' => '([0-9\.,]+)',
            '@alpha' => '([a-zA-Z]+)',
            '@alnum' => '([a-zA-Z0-9\.\w]+)',
            '@str' => '([a-zA-Z0-9-_\.\w]+)',
            '@*' => '(.*)',
            '@date' => '([0-9]{1,2})\/([0-9]{1,2})\/(([0-9]{2})(.{0}|.{2}))',
            '@null' => '^');

        $trimed_path = strtolower(rtrim(ltrim($path, '/'), '/'));
        $trimed_xpath = strtolower(rtrim(ltrim($_GET['directory_lezaz'], '/'), '/'));
        $pattern = str_replace('\\\\', '\\', addcslashes(str_ireplace(array_keys($vars), array_values($vars), $trimed_path), '/'));
        $pattern = "/^{$pattern}$/";

        if (preg_match($pattern, $trimed_xpath, $args)) {
            if (is_callable($callback)) {
                array_shift($args);
                $filtered = call_user_func_array($callback, $args);
                return $filtered;
            } else {
                $this->main_template = $callback;
            }
        }
        return 'x';
    }

    public function set($k, $v) {
        $this->valriables[$k] = $v;
    }

    public function get($k) {
        return $this->valriables[$k];
    }

    public function setsetting($parametr, $value = '') {
        $content = null;
        $settin_file = THEME_PATH . 'setting.ini';
        if (file_exists($settin_file)) {
            $settings = file($settin_file);
            foreach ($settings as $sett) {
                $settkv = explode('^^^', $sett);
                if (trim($settkv[0]) && trim($settkv[1])) {
                    $content[$settkv[0]] = $settkv[1];
                }
            }
        }

        $settkey = $this->encrypt($parametr);
        $settval = $this->encrypt($value);
        $content[$settkey] = $settval . "\n";
        if (!$value)
            unset($content[$settkey]);
        foreach ($content as $k => $v) {
            $contentx.="$k^^^$v";
        }
        $this->file->_write($settin_file, $contentx);
        return '';
    }

    public function setting($key, $defult = '') {
        $settin_file = THEME_PATH . 'setting.ini';
        if (file_exists($settin_file)) {
            $settings = file($settin_file);
            foreach ($settings as $sett) {
                $settkv = explode('^^^', $sett);               
                if (trim($this->decrypt($settkv[0])) == trim($key)) {
                    return $this->decrypt(rtrim($settkv[1], "\n"));
                }
            }
        }
        return $defult;
    }

    public function run() {
        $this->include_plugin('init');
        $this->include_plugin('index');
        $this->include_plugin('footer');
        $this->include_plugin('term');

        $print = $this->lezaz->include_tpl($this->main_template);


        return $print;
    }

}