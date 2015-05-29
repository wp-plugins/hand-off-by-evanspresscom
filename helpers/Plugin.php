<?php
/*
Lex Marion Bataller
lexmarionbataller@yahoo.com
*/

class wpHandoffPlugin {

    var $version = '';
    var $pre = '';
    var $url = '';
    var $plugin_name = '';
    var $plugin_base = '';
    var $debugging = true;
    var $domain = '';

    var $classes = array();
    var $menu_pages = array();
    var $option_pages = array();
    var $options = array();
    var $scripts = array();
    var $styles = array();
    var $shortcodes = array();
    var $query_vars = array();

    var $actions = array();
    var $filters = array();

    //facebook vars
    var $username = '';

    function Plugin() {
        return true;
    }

    function toSlug($string) {
        return preg_replace('/[^a-z0-9\-]+/', '-', strtolower($string));
    }

    function register_plugin($name = '', $base = '', $retain_options = false) {
        $this -> url = explode("&", $_SERVER['REQUEST_URI'])[0];

        $this -> domain = str_replace(array("-", "."), "_", $_SERVER['HTTP_HOST']);

        $this -> plugin_name = $name;
        $this -> plugin_base = str_replace("\\", "/", rtrim(dirname($base), '/'));

        //load pages & admin page assets
        $actions = array(
            'admin_init'    =>  false,
            'admin_menu'    =>  false,
            'admin_head'    =>  false,
            'init'          =>  'wp-init',
            'admin_enqueue_scripts' =>  false,
            'wp_enqueue_scripts'    =>  'enqueue_scripts',
        );

        foreach($this -> actions as $tag => $action) {
            //convert to array
            if(is_string($action)) {
                $action = array($action => false);
            } else if($action === false) {
                $action = array($action => $action);
            }

            //merge values
            foreach($actions as $t => $a) {
                if(! strcmp($tag, $t)) {
                    $action[$tag][] = $a;
                }
            }

            //save changes
            $this -> actions[$tag] = $action;
        }

        $this -> actions = array_merge($actions, $this -> actions);

        //rewrite rules
        $filters = array(
            'query_vars'   =>  array(
                'add_query_vars'    =>  array(
                    'priority'  =>  1,
                )
            ),
            'rewrite_rules_array'   =>  array(
                'add_rewrite_rules' =>  array(
                    'priority'  =>  1,
                )
            )
        );

        foreach($this -> filters as $tag => $filter) {
            //convert to array
            if(is_string($filter)) {
                $filter = array($filter => false);
            } else if($filter === false) {
                $filter = array($filter => $filter);
            }

            //merge values
            foreach($filters as $t => $f) {
                if(! strcmp($tag, $t)) {
                    $filter[$tag][] = $f;
                }
            }

            //save changes
            $this -> filters[$tag] = $filter;
        }

        $this -> filters = array_merge($filters, $this -> filters);

        $this -> initialize_actions();
        $this -> initialize_filters();

        $this -> initialize_classes();
        $this -> initialize_options();
        $this -> initialize_shortcodes();

        $this -> debugging = $this -> get_option("debugging") === 'true' ? true : false;

        if ($this -> debugging === true) {
            @error_reporting(1);
            @ini_set('display_errors', 1);
        } else {
            @error_reporting(0);
            @ini_set('display_errors', 0);
        }

        $this -> fill_options();

        if(empty($retain_options)) {
            register_deactivation_hook($base, array($this, 'deactivation_hook'));
        }

        return true;
    }

    function initialize_actions() {
        if(! empty($this -> actions)) {
            foreach($this -> actions as $tag => $action) {
                if(is_string($action)) {
                    $this -> add_action($tag, $action);
                } else if(is_array($action)) {
                    foreach($action as $func => $opts) {
                        $priority = empty($opts['priority']) ? 10 : $opts['priority'];
                        $params = empty($opts['params']) ? 10 : $opts['params'];

                        $this -> add_action($tag, $func, $priority, $params);
                    }
                } else {
                    $this -> add_action($tag);
                }
            }
        }
    }

    function initialize_filters() {
        if(! empty($this -> filters)) {
            foreach($this -> filters as $tag => $filter) {
                if(is_string($filter)) {
                    $this -> add_filter($tag, $filter);
                } else if(is_array($filter)) {
                    foreach($filter as $func => $opts) {
                        $priority = empty($opts['priority']) ? 10 : $opts['priority'];
                        $params = empty($opts['params']) ? 10 : $opts['params'];

                        $this -> add_filter($tag, $func, $priority, $params);
                    }
                } else {
                    $this -> add_filter($tag);
                }
            }
        }
    }

    function wp_init() {
        $this -> session_manager();

        $this -> add_filter("mce_external_plugins", "add_buttons");
        $this -> add_filter('mce_buttons', 'register_buttons');
    }

    function session_manager() {
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    function add_buttons($buttons) {
        foreach($this -> buttons as $button => $opt) {
            $buttons[$button] = $opt['src'];
        }

        return $buttons;
    }

    function register_buttons($buttons) {
        foreach($this -> buttons as $button => $opt) {
            $buttons = array_merge($buttons, $opt['buttons']);
        }

        return $buttons;
    }

    function admin_init() {
        $this -> flush_rewrite_rules();

        foreach($this -> options as $group => $options) {
            if(is_array($options)) {
                foreach($options as $option => $value) {
                    $this -> register_setting($group, $option, $group);
                }
            }
        }
    }

    function admin_menu() {
        foreach($this -> option_pages as $page => $opt) {
            $capability = 'administrator';
            $func = $opt;
            $slug = $this -> toSlug($page);
            if(is_array($opt)) {
                if(! empty($opt['capability'])) {
                    $capability = $opt['capability'];
                }
                $func = $opt['func'];
                if(! empty($func)) {
                    add_options_page($page, $page, $capability, $slug, array($this, $func));
                }
            } else {
                add_options_page($page, $page, $capability, $slug, array($this, $func));
            }
        }

        foreach($this -> menu_pages as $page => $opt) {
            $capability = 'administrator';
            $slug = $this -> toSlug($page);
            if(is_array($opt)) {
                if(! empty($opt['capability'])) {
                    $capability = $opt['capability'];
                }
                $icon = empty($opt['icon']) ? false : $opt['icon'];
                $position = empty($opt['position']) ? false : $opt['position'];
                add_menu_page($page, $page, $capability, $slug, array($this, $opt['func']), $icon, $position);

                if (!empty($opt['sub']) && is_array($opt['sub'])) {
                    foreach ($opt['sub'] as $sub => $op) {
                        $capability = empty($op['capability']) ? $capability : $op['capability'];
                        add_submenu_page($slug, $sub, $sub, $capability, $this->pre . $sub, array($this, $op['func']));
                    }
                }
            } else {
                add_menu_page($page, $page, $capability, $slug, array($this, $opt), false, false);
            }
        }
    }

    function admin_head() {
        $this -> render('head', false, true, 'admin');
    }

    function admin_enqueue_scripts() {
        foreach($this -> scripts as $script => $value) {
            if(is_array($value) && ! strcmp($script , 'admin')) {
                foreach($value as $arr => $url) {
                    if(is_array($url)) {
                        $src = empty($url['src']) ? false : $url['src'];
                        $deps = empty($url['dependency']) ? array() : $url['dependency'];
                        $ver = $url['version'] === false ? false : $url['version'];
                        $in_footer = $url['footer'] === true ? true : false;
                        wp_enqueue_script($arr, $deps, $ver, $in_footer);
                    } else {
                        wp_enqueue_script($arr, $url);
                    }
                }
            }
        }
        foreach($this -> styles as $style => $value) {
            if(is_array($value) && ! strcmp($style , 'admin')) {
                foreach($value as $arr => $url) {
                    wp_enqueue_style($arr, $url);
                }
            }
        }
    }

    function enqueue_scripts() {
        foreach($this -> scripts as $script => $url) {
            if(is_array($url)) {
                $src = empty($url['src']) ? false : $url['src'];
                $deps = empty($url['dependency']) ? array() : $url['dependency'];
                $ver = $url['version'] === false ? false : $url['version'];
                $in_footer = $url['footer'] === true ? true : false;
                wp_enqueue_script($script, $deps, $ver, $in_footer);
            } else {
                wp_enqueue_script($script, $url);
            }
        }
        foreach($this -> styles as $style => $url) {
            if(! is_array($url)) {
                wp_enqueue_style($style, $url);
            }
        }

        return true;
    }

    function flush_rewrite_rules() {
        global $wp_rewrite;

        $wp_rewrite -> flush_rules();
    }


    function add_query_vars($vars) {
        foreach($this -> query_vars as $shortcode => $v) {
            foreach($v as $var) {
                $vars[] = $var;
            }
        }

        return $vars;
    }

    function add_rewrite_rules($rules) {
        global $wp_rewrite;

        //retrieve permalink structure
        $struct = array_filter(explode("/", get_option("permalink_structure", false)));

        //retrieve all pages
        $pages = get_pages(array(
            'post_type' =>  'page',
            'post_status'   =>  'publish'
        ));
        //retrieve all posts
        $posts = get_posts(array(
            'post_type'  =>  'post',
            'post_status'   =>  'publish'
        ));

        $posts = array_merge($posts, $pages);   //merge pages & posts for easier looping

        foreach($posts as $post) {
            foreach($this -> query_vars as $shortcode => $vars) {
                if (strpos($post -> post_content,'[' . $shortcode) !== false) {//scan for shortcode start phrase
                    $params = array();
                    foreach($vars as $index => $var) {
                        switch($post -> post_type) {
                            case "post":
                                $params[] = $var . "=" . $wp_rewrite -> preg_index(count($struct) + $index);
                                break;
                            case "page":
                                $params[] = $var . "=" . $wp_rewrite -> preg_index(1);
                                break;
                        }
                    }

                    switch($post -> post_type) {
                        case "post":
                            $regex = '';

                            //build request URI regex
                            foreach($struct as $s) {
                                switch($s) {
                                    case "%postname%":
                                        $regex .= $post -> post_name;
                                        break;
                                    case "%post_id%":
                                        $regex .= $post -> ID;
                                        break;
                                    default:
                                        if(preg_match("%(.+)%", $s, $matches) !== FALSE) {
                                            $regex .= "(.+)";
                                        } else {
                                            $regex .= $s;
                                        }
                                        break;
                                }
                                $regex .= "/";
                            }

                            $regex .= "(.+)";

                            $newrules = array($regex => 'index.php?p=' . $post -> ID . '&' . implode("&", $params));
                            break;
                        case "page":
                            $newrules = array($post -> post_name . "/(.+)" => 'index.php?page_id=' . $post -> ID . '&' . implode("&", $params));
                            break;
                    }

                    $rules = $newrules + $rules;
                }
            }
        }

        return $rules;
    }

    function initialize_classes() {
        //init classes here
        if (! empty($this -> classes)) {
            foreach ($this -> classes as $name => $class) {
                global ${$name};
                if (class_exists($class)) {
                    ${$name} = new $class;
                }
            }
        }

        return false;
    }

    function initialize_options() {
        //init opt values here

        foreach($this -> options as $group => $options) {
            if(is_array($options)) {
                foreach($options as $option => $value) {
                    $this -> add_option($option, $value);
                }
            } else {
                $this -> add_option($group, $options);
            }
        }

        return true;
    }

    function fill_options() {
        //assign values

        foreach($this -> options as $group => $options) {
            if(is_array($options)) {
                foreach($options as $option => $value) {
                    $this -> options[$group][$option] = $this -> get_option($option);
                }
            } else {
                $this -> options[$group] = $this -> get_option($group);
            }
        }
    }

    function deactivation_hook() {
        //delete opt values here

        foreach($this -> options as $group => $options) {
            if(is_array($options)) {
                foreach($options as $option => $value) {
                    $this -> delete_option($option);
                }
            } else {
                $this -> delete_option($group);
            }
        }

        return true;
    }

    function initialize_shortcodes() {
        //init shortcode

        foreach($this -> shortcodes as $shortcode => $func) {
            $this -> add_shortcode($shortcode, array($this, $func));
        }

        return true;
    }

    function add_meta_box($id, $name, $callback, $screen = 'post', $context = 'advanced', $priority = 'default', $args = array()) {
        $id = $this -> pre . $this -> toSlug($id);

        if(add_meta_box($id, $name, array($this, $callback), $screen, $context, $priority, $args)) {
            return true;
        }

        return false;
    }

    function wp_add_dashboard_widget($id, $name, $callback) {
        if($this -> add_meta_box($id, $name, $callback, 'dashboard', 'normal', 'core')) {
            return true;
        }

        return false;
    }

    function register_setting($group, $name, $callback) {
        if(register_setting($this -> pre . $group, $this -> pre . $name, array($this, $callback))) {
            return true;
        }

        return false;
    }

    function add_option($name = '', $value = '') {
        if (add_option($this -> pre . $name, $value)) {
            return true;
        }

        return false;
    }

    function update_option($name = '', $value = '') {
        if (update_option($this -> pre . $name, $value)) {
            return true;
        }

        return false;
    }

    function get_option($name = '', $stripslashes = true) {
        if ($option = get_option($this -> pre . $name)) {
            if (@unserialize($option) !== false) {
                return unserialize($option);
            }

            if ($stripslashes == true) {
                $option = stripslashes_deep($option);
            }

            return $option;
        }

        return false;
    }

    function delete_option($name = '') {
        if(delete_option($this -> pre . $name)) {
            return true;
        }

        return false;
    }

    function debug($var = array()) {
        if ($this -> debugging === true) {
            echo '<pre>' . print_r($var, true) . '</pre>';
            return true;
        }

        return false;
    }

    function url() {
        $url = get_option('siteurl') . substr($this -> plugin_base, strlen(realpath(ABSPATH)));
        return $url;
    }

    function add_action($action, $function = '', $priority = 10, $params = 1) {
        if(add_action($action, array($this, (empty($function)) ? $action : $function), $priority, $params)) {
            return true;
        }

        return false;
    }

    function remove_action($action, $function = '', $priority = 10) {
        if(remove_action($action, array($this, (empty($function)) ? $action : $function), $priority)) {
            return true;
        }

        return false;
    }

    function add_filter($filter, $function = '', $priority = 10, $params = 1) {
        if(add_filter($filter, ! strcmp('__return_true', $function) || ! strcmp('__return_false', $function)? $function : array($this, (empty($function)) ? $filter : $function), $priority, $params)) {
            return true;
        }

        return false;
    }

    function remove_filter($filter, $function = '', $priority = 10) {
        if(remove_filter($filter, array($this, (empty($function)) ? $filter : $function), $priority)) {
            return true;
        }

        return false;
    }

    function add_shortcode($name, $method) {
        if (add_shortcode($name, $method)) {
            return true;
        }

        return false;
    }

    function remove_shortcode($name) {
        if (remove_shortcode($name)) {
            return true;
        }

        return false;
    }

    function render_msg($message = '') {
        $this -> render('msg-top', array('message' => $message), true, 'admin');
    }

    function render($file = '', $params = array(), $output = true, $folder = 'admin') {
        if (!empty($file)) {
            $filename = $file . '.php';
            $filepath = $this -> plugin_base . '/views/' . $folder . '/';
            $filefull = $filepath . $filename;

            if (file_exists($filefull)) {
                if ($output === false) {
                    ob_start();
                }

                if (!empty($params) && is_array($params)) {
                    foreach ($params as $pkey => $pval) {
                        ${$pkey} = $pval;
                    }
                }

                if (!empty($this -> classes)) {
                    foreach ($this -> classes as $name => $class) {
                        global ${$name};
                    }
                }

                include($filefull);

                if ($output === false) {
                    $data = ob_get_clean();
                    return $data;
                } else {
                    flush();
                    return true;
                }
            } else {
                $message = 'File "/views/' . $folder . '/' . $filename . '" does not exist!';
                $this -> render_msg($message);
            }
        }

        return false;
    }

    function fdebug($var) {
        echo "Debug Start<pre>";
        var_dump($var);
        echo "</pre>Debug End";
    }

}

?>