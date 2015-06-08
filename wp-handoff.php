<?php
/**
    Plugin Name: Hand Off by EvansPress.com
    Plugin URI: http://www.evanspress.com
    Description: An Admin UI made easier.
    Version: 1.0.1.2
    Author: Johnathan Evans (UX), Lex Marion Bataller (DEV)
    Author URI: http://www.evanspress.com
    Network: false
    License: GPL
*/

require_once(dirname(__FILE__) . '/helpers/Plugin.php');

if(! class_exists('UploadHandler')) {
    require_once(dirname(__FILE__) . '/helpers/UploadHandler.php');
}

class wpHandoff extends wpHandoffPlugin {
    var $pre = "wpHandoff";

    function __construct($args = false) {
        $this -> name = plugin_basename(__FILE__);

        $this -> scripts = array(
            'admin' =>  array('jquery'   =>  false,
                'jquery.ui.widget'  =>  plugins_url('/js/jquery.ui.widget.js', __FILE__),
                'jquery.iframe-transport.js'    =>  plugins_url('/js/jquery.iframe-transport.js', __FILE__),
                'jquery.fileupload' =>  plugins_url('/js/jquery.fileupload.js', __FILE__),
                'interact'  =>  plugins_url('/js/interact.js', __FILE__),
                'wp-handoff-admin'    => plugins_url('/js/admin.js', __FILE__),
            ),
            'jquery'   =>  false,
            'jquery.ui.widget'  =>  plugins_url('/js/jquery.ui.widget.js', __FILE__),
            'jquery.iframe-transport.js'    =>  plugins_url('/js/jquery.iframe-transport.js', __FILE__),
            'jquery.fileupload' =>  plugins_url('/js/jquery.fileupload.js', __FILE__),
            'interact'  =>  plugins_url('/js/interact.js', __FILE__),
            'wp-handoff-admin'    => plugins_url('/js/admin.js', __FILE__),
        );

        $this -> styles = array(
            'admin' =>  array(
                'wp-handoff-admin' =>  plugins_url('/css/admin.css', __FILE__),
            ),
            'wp-handoff-admin' =>  plugins_url('/css/admin.css', __FILE__),
        );

        $this -> options = array(
            'debugging' =>  true,
            'settings_options'  =>  array(
                //welcome message
                'welcome_message'   =>  "<h3>Welcome to your optimized WordPress administrative experience brought to you by your web designer. Please feel free to use the \"Support\" option if you need further assistance. You can get started editing your content right away by selecting the arrow at the top of the screen. Make sure to look for upcoming news, help and updates from your designer in the post feeds section below.</h3>",
                //dashboard admin menu
                'menu_order'        =>  '',
                'menu_hidden'       =>  '',
                'menu_rename'       =>  '',
                'menu_orig_names'   =>  '',
                'submenu_hidden'    =>  '',
                'submenu_rename'    =>  '',
                'submenu_orig_names'=>  '',
                //pages
                'pages_order'       =>  '',
                'pages_hidden'      =>  '',
                'pages_rename'      =>  '',
                'pages_orig_names'  =>  '',
                //login
                'login_logo'        =>  '',
                'custom_logo'       =>  '',
                //dashboard widgets
                'rss_title'         =>  'Updates, Tips and More from EvansPress.com',
                'rss_url'           =>  'http://evanspress.com/feed/',
                //settings tabs
                'active_tab'        =>  '',
                //toggles
                'admin_hidden'  =>  array(
                    'role'      =>  'administrator',
                    'admin_bar' =>  'on',
                    'title'     =>  'on',
                    'dismiss'   =>  'on',
                    'settings'  =>  'on',
                    'footer'    =>  'on',
                ),
                //meta boxes
                'meta_hidden'     =>  array(
                    'formatdiv'         =>  'on',
                    'categorydiv'       =>  'on',
                    'tagsdiv-post_div'  =>  'on',
                    'postimagediv'      =>  'on',
                    'postexcerpt'       =>  'on',
                    'trackbacksdiv'     =>  'on',
                    'postcustom'        =>  'on',
                    'commentstatusdiv'  =>  'on',
                    'slugdiv'           =>  'on',
                    'authordiv'         =>  'on',
                    'custom'            =>  'on',
                ),
                //row actions
                'row_action_hidden'    =>  array(
                    'edit'      =>  'on',
                    'inline'    =>  'on',
                    'trash'     =>  'on',
                    'view'      =>  'on',
                    'custom'    =>  'on',
                ),
                'manage_columns_hidden' =>  array(
                    'cb'            =>  'on',
                    'author'        =>  'on',
                    'categories'    =>  'on',
                    'tags'          =>  'on',
                    'comments'      =>  'on',
                    'date'          =>  'on',
                    'custom'        =>  'on',
                ),
                'support_link'  =>  'http://www.evanspress.com',
                'editor_hidden'  =>  array(
                    'addnew'            =>  'on',
                    'gallery'           =>  'on',
                    'featured_image'    =>  'on',
                    'slugbar'           =>  'on',
                ),
                /*
                 * media gallery options
                 * will sync after saving settings
                */
                'image_default_link_type'   =>  'none',
                'image_default_size'        =>  'medium',
                'image_default_align'       =>  'right',
            ),
        );

        $this -> option_pages = array(
            'Hand Off'   =>  'settings',
        );

        $this -> actions = array(
            'welcome_panel' =>  false,
            'admin_bar_menu'=>  array(
                'admin_bar_menu'    =>  array(
                    'priority'  =>  99999999999,
                ),
            ),
            'admin_menu'    =>  array(
                'mod_admin_menu'   =>  array(
                    'priority'  =>  99999999999,
                ),
            ),
            'do_meta_boxes' =>  array(
                'remove_widgets'    =>  array(
                    'priority'  =>  99999999999,
                )
            ),
            'wp_dashboard_setup'    =>  'setup_widgets',
            'login_head'    =>  false,
            'wp_ajax_login_logo_upload'    =>  'login_logo_upload',
            'wp_ajax_nopriv_login_logo_upload'  =>  'login_logo_upload',
            'activated_plugin'  =>  false,
            'load-index.php'    =>  'show_welcome_panel',
            'wp_after_admin_bar_render' =>  'admin_bar',
            'admin_head'        =>  'remove_media_button',
            'wp_logout'         =>  false,
        );

        $this -> filters = array(
            'custom_menu_order' =>  '__return_true',
            'menu_order'        =>  'change_menu_order',
            'screen_options_show_screen'    =>  'remove_screen_options',
            'contextual_help'   =>  'remove_help',
            'page_row_actions'  =>  array(
                'remove_row_actions'    =>  array(
                    'priority'  =>  99999999999,
                )
            ),
            'post_row_actions'  =>  array(
                'remove_row_actions'    =>  array(
                    'priority'  =>  99999999999,
                )
            ),
            'tag_row_actions'   =>  array(
                'remove_row_actions'    =>  array(
                    'priority'  =>  99999999999,
                )
            ),
            'manage_posts_columns'  =>  array(
                'remove_columns'    =>  array(
                    'priority'  =>  99999999999,
                )
            ),
            'manage_pages_columns'  =>  array(
                'remove_columns'    =>  array(
                    'priority'  =>  99999999999,
                )
            ),
            'plugin_action_links_' . $this -> name => 'plugin_action_links',
            'user_can_richedit'     =>  false,
            'wp_default_editor'     =>  false,
            'gettext'               =>  array(
                'rename_media_button'  =>  array(
                    'priority'  =>  99999999999,
                )
            ),
            'media_view_strings'    =>  array(
                'remove_media_strings'  =>  array(
                    'priority'  =>  99999999999,
                )
            ),
            'media_view_settings'    =>  array(
                'remove_media_settings'  =>  array(
                    'priority'  =>  99999999999,
                ),
            ),
        );

        $this -> menu = array();
        $this -> default_menu = array(
            'index.php' =>  'Dashboard',
            'edit.php?post_type=page'   =>  'Edit Pages',
        );
        $this -> submenu = array();
        $this -> default_actions = array(
            'edit'  =>  'Edit',
            'inline'    =>  'Quick Edit',
            'trash' =>  'Trash',
            'view'  =>  'View',
            'custom'    =>  'Custom',
        );
        $this -> default_meta = array(
            'formatdiv' =>  'Format',
            'categorydiv'   =>  'Categories',
            'tagsdiv-post_div'  =>  'Tags',
            'postimagediv'  =>  'Featured Image',
            'postexcerpt'   =>  'Excerpt',
            'trackbacksdiv' =>  'Send Trackbacks',
            'postcustom'    =>  'Custom Fields',
            'commentstatusdiv'  =>  'Discussion',
            'slugdiv'   =>  'Slug',
            'authordiv' =>  'Author',
            'custom'    =>  'Custom'
        );
        $this -> default_columns = array(
            'cb'    =>  'Bulk Actions',
            'title' =>  'Title',
            'author'    =>  'Author',
            'categories'    =>  'Categories',
            'tags'  =>  'Tags',
            'comments'  =>  'Comments',
            'date'  =>  'Date',
            'custom'    =>  'Custom',
        );

        //register the plugin and init assets
		$this -> register_plugin($this -> name, __FILE__, true);

        $this -> advance = 0;

        if(! empty($_GET['hand-off-mode'])) {
            $request = explode("?", $_SERVER['REQUEST_URI'])[0] . "?";
            $gets = explode("&", explode("?", $_SERVER['REQUEST_URI'])[1]);
            $index = 0;
            foreach($gets as $get) {
                if(! preg_match('/hand-off-mode(?==)/', $get)) {
                    if($index) {
                        $request .= "&";
                    }
                    $request .= $get;
                    $index++;
                }
            }
            switch($_GET['hand-off-mode']) {
                case 'basic':
                    $this -> wp_logout();
                    break;
                case 'advance':
                    setcookie('hand-off-mode', 1, 0, '/');
                    break;
            }

            header('Location:' . $request);  //wp_redirect & wp_safe_redirect does not work :(
            exit();
        }

        if(! empty($_COOKIE['hand-off-mode'])) {
            $this -> advance = 1;
        }
    }

    function mod_admin_menu() {
        global $menu, $submenu;
        $this -> menu = $menu;
        $this -> submenu = $submenu;

        $this -> rename_plugin_menu();
        $this -> remove_menus();
    }

    function admin_bar_menu($wp_admin_bar) {
        if(! empty($this -> advance)) {
            $href = $_SERVER['REQUEST_URI'];
            if(empty($_GET)) {
                $href .= "?";
            } else {
                $href .= "&";
            }
            $href .= "hand-off-mode=basic";
            $wp_admin_bar->add_node(array(
                'id' => 'hand-off-basic',
                'title' => 'Basic',
                'href' => $href,
            ));
        }
    }

    function remove_media_strings($strings) {
        if(empty($this -> advance)) {
            $editor = $this->options['settings_options']['editor_hidden'];

            if (!empty($editor['featured_image']) && $editor['featured_image'] == "on") {
                $strings['setFeaturedImageTitle'] = false;
            }

            if (!empty($editor['gallery']) && $editor['gallery'] == "on") {
                $strings['createGalleryTitle'] = false;
            }
        }

        return $strings;
    }

    function wp_logout() {
        setcookie('hand-off-mode', 0, time() - 3600, '/');
    }

    function remove_media_settings($settings) {
        if(empty($this -> advance)) {
            $settings['defaultProps'] = array(
                'link' => $this->options['settings_options']['image_default_link_type'],
                'align' => $this->options['settings_options']['image_default_align'],
                'size' => $this->options['settings_options']['image_default_size'],
            );
        }

        return $settings;
    }

    function rename_media_button($translation, $text) {
        if(empty($this -> advance)) {
            $editor = $this->options['settings_options']['editor_hidden'];

            if (is_admin() && $text === 'Add Media' && empty($editor['media_name'])) {
                return __('Add Photo');
            }
        }

        return $translation;
    }

    function remove_media_button() {
        if(empty($this -> advance)) {
            $editor = $this->options['settings_options']['editor_hidden'];

            if (!empty($editor['media']) && $editor['media'] == "on") {
                remove_action('media_buttons', 'media_buttons');
            }
        }
    }

    function user_can_richedit() {
        if(empty($this -> advance)) {
            if (empty($this->options['settings_options']['richeditor'])) {
                return true;
            }
            return false;
        }
        return true;
    }

    function wp_default_editor($editor) {
        if(empty($this -> advance)) {
            if (!empty($this->options['settings_options']['richeditor']) && $this->options['settings_options']['richeditor'] == "on") {
                return $editor;
            }
            return 'tinymce';
        }
        return $editor;
    }

    function plugin_action_links($links) {
        $links[] = '<a href="'. admin_url('options-general.php?page=hand-off') .'">Settings</a>';

        return $links;
    }

    function admin_bar() {
        if(empty($this -> advance)) {
            global $wp_admin_bar, $menu, $submenu, $current_screen, $wp_the_query;

            $page = basename($_SERVER['REQUEST_URI']);
            $page_title = get_the_title();
            $pages = $this -> get_pages();

            foreach($pages as $index => $p) {
                $pages[$index] -> link = admin_url('post.php?post=' . $p -> ID . '&action=edit');
            }

            if (is_admin()) {
                $page_title = get_admin_page_title();
            }

            foreach ($meta as $page => $widgets) {
                foreach ($widgets as $context => $group) {
                    foreach ($group as $id => $widget) {
                        if (!empty($meta_hidden[$page][$context][$id])) {
                            $meta[$page][$context][$id]['hidden'] = true;
                        }
                    }
                }
            }

            //find currently active submenu
            foreach ($submenu as $parent => $group) {
                foreach ($group as $key => $sub) {
                    if (!preg_match('/.+(\.php)/i', $sub[2])) {
                        $group[$key]['link'] = admin_url($parent . '?page=' . $sub[2]);
                    } else {
                        $group[$key]['link'] = admin_url($sub[2]);
                    }

                    if ($page == $sub[2] || ($page_title == $sub[0] && preg_replace('/\?.*/', '', $page) == $parent) || ($parent == 'index.php' && $sub[2] == 'index.php' && strpos($page, 'wp-admin') !== false)) {
                        $group[$key]['active'] = true;
                    } else {
                        $group[$key]['active'] = false;
                    }
                }

                $submenu[$parent] = $group;
            }

            //find currently active menu
            foreach ($menu as $index => $item) {
                $active = false;
                foreach ($submenu as $parent => $group) { //attach submenu to its respective menu
                    if ($item[2] == $parent) {
                        $menu[$index]['submenu'] = $group;
                        foreach ($group as $sub) {
                            //check if submenu belongs under current menu
                            if ($sub['active']) {
                                $active = true;
                            }
                        }
                    }
                }

                $menu[$index]['link'] = admin_url($item[2]);
                if ($page == $item[2] || $active || (strpos($page, 'wp-admin') !== false && $item[2] == 'index.php')) {
                    $menu[$index]['active'] = true;
                } else {
                    $menu[$index]['active'] = false;
                }

            }

            $admin = is_admin();
            $link = "";
            $label = "";
            $post = false;

            if($admin) {
                $post = get_post();
            } else {
                $post = $wp_the_query -> get_queried_object();
            }

            if(! empty($post)) {
                $post_type_object = get_post_type_object($post -> post_type);

                //check if in admin and editing
                if(($current_screen -> base == "post"
                && $current_screen -> action != "add")
                || ! $admin
                ) {
                    if ($admin) {
                        $link = set_url_scheme(get_permalink($post->ID));
                        $label = "View";
                    } else {
                        $link = admin_url("post.php?post=" . $post->ID . "&action=edit");
                        $label = "Edit";
                    }

                    switch ($post->post_type) {
                        case 'post':
                            $label .= " Post";
                            break;
                        case 'page':
                            $label .= " Page";
                            break;
                        default:
                            $post = false;
                            break;
                    }
                } else {
                    $post = false;
                }
            }


            $user = wp_get_current_user();
            $role = "administrator";

            if(! empty($this -> options['settings_options']['admin_hidden']['role'])) {
                $role = $this -> options['settings_options']['admin_hidden']['role'];
            }

            $caps = get_role($role) -> capabilities;

            $show = true;
            foreach($user -> roles as $r) {
                $user_caps = get_role($role) -> capabilities;
                foreach($user_caps as $user_cap => $allow) {
                    if(empty($caps[$user_cap])) {
                        $show = false;
                    }
                }
            }

            $advance = $_SERVER['REQUEST_URI'];
            if(empty($_GET)) {
                if(substr($advance, -1) != "?") {
                    $advance .= "?";
                }
            } else {
                $advance .= "&";
            }
            $advance .= "hand-off-mode=advance";
            $this->render('admin-bar', array(
                'pre'           => $this->pre,
                'menu'          => $menu,
                'pages'         =>  $pages,
                'pages_hidden'  =>  $this -> options['settings_options']['pages_hidden'],
                'support'       =>  $this -> options['settings_options']['support_link'],
                'logout'        => wp_logout_url(get_permalink()),
                'post'          =>  $post,
                'link'          =>  $link,
                'label'         =>  $label,
                'advance'       =>  $advance,
            ), true, 'admin');

            $this -> admin_aesthetics();
        }
    }

    function remove_help($old_help, $screen_id, $screen) {
        if (empty($this -> advance)) {
            $screen->remove_help_tabs();
            return $old_help;
        }
    }

    function remove_row_actions($actions, $post) {
        if (empty($this -> advance)) {
            //create new actions array
            $actions_hide = $this->options['settings_options']['row_action_hidden'];

            foreach ($actions_hide as $action => $value) {
                if ($action == 'custom') {   //ignore custom value for later
                    continue;
                }
                if ($value == "on") {
                    foreach ($actions as $a => $label) {
                        if (strpos($a, $action) !== false) {
                            unset($actions[$a]);
                            break;
                        }
                    }
                }
            }

            if (!empty($actions_hide['custom']) && $actions_hide['custom'] == "on") {   //remove custom actions
                foreach ($actions as $a => $label) {
                    $found = false;
                    foreach ($this->default_actions as $action => $l) {
                        if ($a == $action) {
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        unset($actions[$a]);
                    }
                }
            }
            $actions = array_filter($actions);
        }

        return $actions;
    }

    function remove_columns($columns) {
        if(empty($this -> advance)) {
            $columns_hide = $this -> options['settings_options']['manage_columns_hidden'];

            foreach($columns_hide as $column => $value) {
                if($column == 'custom') {
                    continue;
                }
                if($value == "on") {
                    foreach($columns as $c => $label) {
                        if(strpos($c, $column) !== false) {
                            unset($columns[$c]);
                            break;
                        }
                    }
                }
            }

            if(! empty($columns_hide['custom']) && $columns_hide['custom'] == "on") {   //remove custom actions
                foreach($columns as $c => $label) {
                    $found = false;
                    foreach($this -> default_columns as $column => $l) {
                        if($c == $column) {
                            $found = true;
                            break;
                        }
                    }

                    if(! $found) {
                        unset($columns[$c]);
                    }
                }
            }
            $columns = array_filter($columns);
        }

        return $columns;
    }

    function remove_screen_options() {
        if (empty($this -> advance)) {
            return false;
        }

        return true;
    }

    function show_welcome_panel() {
        $id = get_current_user_id();

        if (!get_user_meta($id, 'show_welcome_panel', true)) {
            update_user_meta($id, 'show_welcome_panel', 1);
        }
    }

    function settings_options($options) {
        return $options;
    }

    function get_pages() {
        $raw_pages = get_pages();
        $pages = array();
        $pages_order = $this -> options['settings_options']['pages_order'];
        $pages_rename = $this -> options['settings_options']['pages_rename'];
        $pages_orig = $this -> options['settings_options']['pages_orig_names'];
        $pages_hidden = $this -> options['settings_options']['pages_hidden'];
        $index = 0;

        foreach($raw_pages as $i => $item) { //use local copy to show hidden menus
            $index++;

            if(! empty($pages_rename) && ! empty($pages_rename[$item -> ID])) {
                $item -> post_title = $pages_rename[$item -> ID];
            }

            if(empty($pages_order)) {
                $pages[$index] = $item;    //add index key for later use
            } else {
                $indexed = false;
                foreach($pages_order as $i => $id) {
                    if($id == $item -> ID) {
                        $pages[$i] = $item;
                        $indexed = true;
                        break;
                    }
                }

                if(! $indexed) {
                    $pages[$index] = $item;
                }
            }
        }

        if(empty($pages_orig)) {
            $pages_orig = array();

            foreach($pages as $page) {
                $pages_orig[$page -> ID] = $page -> post_title;
            }

            //apply default menu
            $pages_hidden = array();
            foreach($pages as $page) {
                $pages_hidden[$page -> ID . $this -> pre . $page -> post_title] = "on";
            }
        } else {    //allow only existing pages
            $tmp = $pages_hidden;
            $pages_hidden = array();
            foreach($pages as $page) {
                foreach($tmp as $key => $hidden) {
                    if($page -> ID . $this -> pre . $page -> post_title == $key) {
                        $pages_hidden[$key] = $hidden;
                        break;
                    }
                }
            }
        }

        $this -> options['settings_options']['pages_orig_names'] = $pages_orig;
        $this -> options['settings_options']['pages_hidden'] = $pages_hidden;

        return $pages;
    }

    function settings() {
        global $wp_roles;
        //menu & submenu
        $order = $this -> options['settings_options']['menu_order'];
        $orig = $this -> options['settings_options']['menu_orig_names'];
        $hidden = array();
        $rename = $this -> options['settings_options']['menu_rename'];
        $orig = $this -> options['settings_options']['menu_orig_names'];
        $sub_hidden = $this -> options['settings_options']['submenu_hidden'];
        $sub_rename = $this -> options['settings_options']['submenu_rename'];
        $sub_orig = $this -> options['settings_options']['submenu_orig_names'];
        $menu = array();
        $index = 0;

        foreach($this -> options['settings_options']['menu_hidden'] as $key => $value) {    //remove non-existent hidden menus
            list($name, $file) = explode($this -> pre, $key);
            foreach($this -> menu as $i => $item) {
                if($file == $item[2]) {
                    $hidden[$key] = "on";
                }
            }
        }

        foreach($this -> menu as $i => $item) { //use local copy to show hidden menus
            if($item[0] != '') {
                $index++;
                $item[0] = preg_replace('/\s*(\<span).*(\<\/span>)/i', '', $item[0]);   //remove notification html tags

                foreach($this -> submenu as $file => $sub) {
                    if($sub[2] == 'hand-off') {
                        continue;
                    }
                    if($file == $item[2]) {
                        foreach($sub as $key => $s) {
                            if($s[2] == 'hand-off') {
                                unset($sub[$key]);
                                continue;
                            }
                            if($s[0] != '') {
                                $sub[$key][0] = preg_replace('/\s*(\<span).*(\<\/span>)/i', '', $s[0]);   //remove notification html tags
                            }
                            if($file == $s[2]) {
                                $sub[$key]['parent'] = true;
                            } else {
                                $sub[$key]['parent'] = false;
                            }
                        }

                        $this -> submenu[$file] = array_filter($sub);
                        $item['submenu'] = $sub;
                    }
                }

                if(empty($order)) {
                    $menu[$index] = $item;    //add index key for later use
                } else {
                    $indexed = false;
                    foreach($order as $i => $file) {
                        if($file == $item[2]) {
                            $menu[$i] = $item;
                            $indexed = true;
                            break;
                        }
                    }

                    if(! $indexed) {
                        $menu[$index] = $item;
                    }
                }
            }
        }

        ksort($menu);

        if(empty($orig)) {
            $orig = array();

            foreach($menu as $item) {
                if($item[0] != '') {
                    $orig[$item[2]] = $item[0];
                }
            }

            //apply default menu
            $hidden = array();
            $sub_hidden = array();
            foreach($menu as $item) {
                if($item[2] != 'index.php'
                && $item[2] != 'edit.php?post_type=page'
                ) {
                    $hidden[$item[0] . $this->pre . $item[2]] = "on";
                }

                foreach($item['submenu'] as $sub) {
                    if($sub[2] != 'hand-off') {
                        $sub_hidden[$item[2] . $this->pre . $sub[2]] = "on";
                    }
                }
            }
        }

        if(empty($sub_orig)) {
            $sub_orig = array();

            foreach($this -> submenu as $parent => $group) {
                foreach($group as $item) {
                    if($item[0] != '') {
                        $sub_orig[$item[2]] = $item[0];
                    }
                }
            }
        }
        //end
        //pages
        $pages = $this -> get_pages();
        //end
        //login logo
        $default_logo = home_url() . '/wp-admin/images/w-logo-blue.png';
        $logo = $this -> options['settings_options']['login_logo'];
        if(empty($logo)) {
            $logo = $default_logo;  //if not yet set, revert to default
        }
        //end
        //rss
        $rss_title = $this -> options['settings_options']['rss_title'];
        $rss = $this -> options['settings_options']['rss_url'];
        $default_rss = get_bloginfo('rss2_url');
        if(empty($rss)) {
            $rss = $default_rss;
        }
        //end

        $this -> render('settings', array(
            'pre'       =>  $this -> pre,
            'welcome_message'   =>  $this -> options['settings_options']['welcome_message'],
            'hidden'    =>  $hidden,
            'orig'      =>  $orig,
            'sub_hidden'=>  $sub_hidden,
            'sub_orig'  =>  $sub_orig,
            'menu'      =>  $menu,
            'default_logo'  =>  $default_logo,
            'custom_logo'   =>  $this -> options['settings_options']['custom_logo'],
            'logo'      =>  $logo,
            'default_rss'   =>  $default_rss,
            'rss'       =>  $rss,
            'active_tab'    =>  $this -> options['settings_options']['active_tab'],
            'default_actions'   =>  $this -> default_actions,
            'actions'   =>  $this -> options['settings_options']['row_action_hidden'],
            'default_meta'  =>  $this -> default_meta,
            'meta_hidden'   =>  $this -> options['settings_options']['meta_hidden'],
            'default_columns'  =>  $this -> default_columns,
            'columns'   =>  $this -> options['settings_options']['manage_columns_hidden'],
            'admin_hidden'  =>  $this -> options['settings_options']['admin_hidden'],
            'rss_title' =>  empty($rss_title) ? 'Posts Feed' : $rss_title,
            'pages' =>  $pages,
            'pages_hidden'  =>  $this -> options['settings_options']['pages_hidden'],
            'pages_orig'    =>  $this -> options['settings_options']['pages_orig_names'],
            'support'       =>  $this -> options['settings_options']['support_link'],
            'editor'        =>  $this -> options['settings_options']['editor_hidden'],
            'alignment'     =>  $this -> options['settings_options']['image_default_align'],
            'link'          =>  $this -> options['settings_options']['image_default_link_type'],
            'size'          =>  $this -> options['settings_options']['image_default_size'],
            'roles'         =>  $wp_roles -> roles,
            'redirect'      =>  $this -> options['settings_options']['login_redirect'],
            ), true, 'admin');
    }

    function welcome_panel() {
        if(empty($this -> advance)) {
            $this->render('welcome', array(
                'pre' => $this->pre,
                'welcome_message' => $this->options['settings_options']['welcome_message'],
            ), true, 'admin');
        }
    }

    // Reorder Menu
    function change_menu_order($menu) {
        if(empty($this -> advance)) {
            $order = $this->options['settings_options']['menu_order'];

            if (empty($order)) {
                return $menu;
            } else {
                $new_menu = array();
                foreach ($order as $item) {
                    $new_menu[] = $item;
                }

                return $new_menu;
            }
        }
        return $menu;
    }

    //Change Names
    function rename_plugin_menu() {
        $rename = $this->options['settings_options']['menu_rename'];
        $subrename = $this->options['settings_options']['submenu_rename'];
        $orig = $this->options['settings_options']['menu_orig_names'];
        $suborig = $this->options['settings_options']['submenu_orig_names'];

        if (empty($orig)) {
            $orig = array();
            foreach ($this -> menu as $n => $item) {
                if (strlen($item[0]) && $item[2] == 'edit.php?post_type=page') {
                    $this -> menu[$n][0] = 'Edit Pages';    //default Pages name for plugin
                }
            }
        }

        if (!empty($rename)) {
            foreach ($this -> menu as $n => $item) {
                foreach ($rename as $k => $v) {
                    if ($v != '') {
                        if ($item[2] == $k) {  //match item name with original
                            preg_match('/\s*(\<span).*(\<\/span>)/i', $item[0], $matches);  //exclude notification html tags
                            $this -> menu[$n][0] = $v . $matches[0];
                        }
                    }
                }
            }
        }

        if (!empty($subrename)) {
            foreach ($this -> submenu as $n => $group) {
                foreach ($subrename as $key => $name) {
                    if ($name != '') {
                        foreach ($group as $parent => $item) {
                            if ($item[2] == $key) {
                                preg_match('/\s*(\<span).*(\<\/span>)/i', $this -> submenu[$n][0], $matches);  //exclude notification html tags
                                $this -> submenu[$n][$parent][0] = $name . $matches[0];
                            }
                        }
                    }
                }
            }
        }

        //apply only when in basic
        if(empty($this -> advance)) {
            /*
             * workaround $submenu does not persist data after remove_submenu_page method
             * save local copy of $submenu
             *
             * save local copy of $menu for settings page
             */
            global $menu, $submenu;
            $menu = $this -> menu;
            $submenu = $this -> submenu;
            //end
        }
    }

    //Hide Menu
    function remove_menus() {
        if(empty($this -> advance)) {
            $hidden = $this->options['settings_options']['menu_hidden'];
            $subhidden = $this->options['settings_options']['submenu_hidden'];
            $orig = $this->options['settings_options']['menu_orig_names'];

            if (!empty($hidden)) {
                foreach ($hidden as $item => $value) {
                    list($name, $file) = explode($this->pre, $item);
                    if ($value == "on") {
                        remove_menu_page($file);
                    }
                }
            } else if (empty($orig)) {
                foreach ($this -> menu as $item) {
                    $found = false;
                    foreach ($this->default_menu as $file => $label) {
                        if ($item[2] == $file) {
                            $found = true;
                        }
                    }

                    if (!$found) {
                        remove_menu_page($item[2]);
                    }
                }
            }

            if (!empty($subhidden)) {
                foreach ($subhidden as $item => $value) {
                    list($parent, $sub) = explode($this->pre, $item);
                    if ($value == "on") {
                        remove_submenu_page($parent, $sub);
                    }
                }
            } else if (empty($orig)) {
                foreach ($this -> submenu as $parent => $group) {
                    foreach ($group as $item) {
                        if ($item[2] == 'hand-off') {
                            continue;
                        } else {
                            remove_submenu_page($parent, $item[2]);
                        }
                    }
                }
            }
        }
    }

    function setup_widgets() {
        $title = empty($this -> options['settings_options']['rss_title']) ? 'Posts Feed' : $this -> options['settings_options']['rss_title'];

        if(empty($this -> options['settings_options']['admin_hidden']['rss'])) {
            $this -> wp_add_dashboard_widget('posts-feed', $title, 'posts_feed');
        }
    }

    function remove_widgets() {
        if (empty($this -> advance)) {
            global $wp_meta_boxes;

            $meta_hidden = $this->options['settings_options']['meta_hidden'];

            foreach ($wp_meta_boxes as $page => $widgets) {
                foreach ($widgets as $context => $group) {
                    foreach ($group as $priority => $meta_boxes) {
                        foreach ($meta_boxes as $id => $meta_box) {
                            if (($id == $this->pre . 'posts-feed'
                                && $page == 'dashboard'
                                && $context == 'normal')
                                || $id == 'submitdiv'
                            ) {
                                continue;
                            } else {
                                if ($page == 'dashboard') {
                                    remove_meta_box($id, $page, $context);
                                } else {
                                    foreach ($meta_hidden as $box => $value) {
                                        if ($box == 'custom') {  //ignore for later use
                                            continue;
                                        }
                                        if ($box == $id && $value == "on") {
                                            remove_meta_box($id, $page, $context);
                                        }
                                    }

                                    if (!empty($meta_hidden['custom']) && $meta_hidden['custom'] == "on") {
                                        $found = false;
                                        foreach ($this->default_meta as $meta => $label) {
                                            if ($id == $meta) {
                                                $found = true;
                                                break;
                                            }
                                        }

                                        if (!$found) {
                                            remove_meta_box($id, $page, $context);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function posts_feed() {
        $rss = $this -> options['settings_options']['rss_url'];
        $count = 0;
        $items = array();

        if(empty($rss)) {
            $rss = get_bloginfo('rss2_url');
        }

        $feed = fetch_feed($rss);

        if(! is_wp_error($feed)) {
            $count = $feed -> get_item_quantity(5);
            $items = $feed -> get_items(0, $count);
        }

        $this -> render('feed', array(
            'pre'   =>  $this -> pre,
            'count' =>  $count,
            'items' =>  $items,
        ), true, 'admin');
    }

    //Change login image
    function login_head() {
        $logo = $this -> options['settings_options']['login_logo'];
        $custom = $this -> options['settings_options']['custom_logo'];

        if(! empty($logo) && ! empty($custom) && $logo == $custom):
            $logo = str_replace(basename($logo), "thumbnail/" . basename($logo), $logo);
            list($width, $height) = getimagesize($logo);
        ?>
        <style>
            body.login #login h1 a { background: url(<?php echo $logo; ?>) 0 0 no-repeat transparent; width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; max-width: 320px; max-height: 260px;}
        </style>
        <?php
        endif;
    }

    //Change Admin Bar
    function admin_aesthetics() {
        global $current_screen;

        $admin = $this -> options['settings_options']['admin_hidden'];
        $columns = $this -> options['settings_options']['manage_columns_hidden'];
        $editor = $this -> options['settings_options']['editor_hidden'];

        if(empty($this -> advance)):
        ?>
        <style type="text/css">
            li#wp-admin-bar-new-content { display: none; }
            li#wp-admin-bar-view { display: none; }
            li#wp-admin-bar-edit { display: none; }
            div#adminmenuback,
            div#adminmenuwrap { display: none; }
            div#wpcontent,
            div#wpfooter { margin-left: 0; }
            li#wp-admin-bar-menu-toggle { display: none !important; }
            #wpadminbar li#wp-admin-bar-my-account { display: none !important; }
        </style>
        <?php
        endif;

        //dashboard welcome message
        ?>
        <style type="text/css">
            .welcome-panel-content { display: none; }
            #dashboard-widgets .postbox-container { width: 100%; }

            @media only screen and (max-width: 1800px) and (min-width: 1500px) {
                #wpbody-content #dashboard-widgets #postbox-container-1,
                #wpbody-content #dashboard-widgets .postbox-container{ width: 100%; }
            }
            @media only screen and (max-width: 1499px) and (min-width: 800px) {
                #wpbody-content #dashboard-widgets .postbox-container,
                #wpbody-content #dashboard-widgets #postbox-container-2,
                #wpbody-content #dashboard-widgets #postbox-container-3,
                #wpbody-content #dashboard-widgets #postbox-container-4 { width: 100%; }
            }
        </style>
        <?php

        if(! empty($admin['admin_bar'])):
        ?>
        <style type="text/css">
            #wpadminbar { height: 0; overflow: hidden; }

            @media screen and (max-width: 782px) {
                html #wpadminbar { height: 0; min-height: 0; }
            }
        </style>
        <?php
        endif;

        if(! empty($admin['logout']) && $admin['logout'] == "on"):
        ?>
        <style type="text/css">
            .wpHandoff-admin-bar-action .wpHandoff-admin-bar-logout { display: none; }
        </style>
        <?php
        endif;

        if(! empty($admin['title']) && $admin['title'] == "on"):
        ?>
        <style type="text/css">
            #wpbody-content .wrap > h2 { font-size: 0; }
        </style>
        <?php
        endif;

        if(! empty($admin['dismiss']) && $admin['dismiss'] == "on"):
        ?>
        <style type="text/css">
            .welcome-panel .welcome-panel-close { display: none; }
        </style>
        <?php
        endif;

        if(! empty($admin['settings']) && $admin['settings'] == "on"):
        ?>
        <style type="text/css">
            .wpHandoff-admin-bar-action .wpHandoff-admin-bar-settings { display: none; }
        </style>
        <?php
        endif;

        if(! empty($admin['footer']) && $admin['footer'] == "on"):
        ?>
        <style type="text/css">
            #wpfooter > p { display: none; }
        </style>
        <?php
        endif;

        if(! empty($columns['cb']) && $columns['cb'] == "on"):
        ?>
        <style type="text/css">
            .bulkactions { display: none; }
        </style>
        <?php
        endif;

        if(! empty($editor['addnew']) && $editor['addnew'] == "on" && ($current_screen -> id == 'edit-page') || $current_screen -> id == 'page'):
        ?>
        <style type="text/css">
            #wpbody-content .wrap > h2 > a { display: none; }
        </style>
        <?php
        endif;

        if(! empty($editor['slugbar']) && $editor['slugbar'] == "on"):
        ?>
        <style type="text/css">
            #edit-slug-box { display: none; }
        </style>
        <?php
        endif;

        if(! empty($editor['richeditor']) && $editor['richeditor'] == "on" && ($current_screen -> id == 'page' || $current_screen -> id == 'post')):
        ?>
        <style type="text/css">
            .wp-switch-editor.switch-html { display: none; }
        </style>
        <?php
        endif;
    }

    //Login Logo Upload Handler
    function login_logo_upload() {
        $upload_dir = wp_upload_dir();

        $upload_handler = new UploadHandler(array(
            "script_url"	=>	plugins_url('/helpers/', __FILE__),
            "upload_dir"	=>	$upload_dir['path'] . '/',
            "upload_url"	=>	$upload_dir['url'] . '/',
            "mkdir_mode"	=>	0777,
            "delete_type" => 'POST',
            "image_versions"    =>  array(
                '' => array(
                    // Automatically rotate images based on EXIF meta data:
                    'auto_orient' => true
                ),
                'thumbnail' => array(
                    'max_width' => 320,
                    'max_height' => 260
                )
            )
            )
        );

        die();
    }

    //redirect after activation
    function activated_plugin($plugin) {
        if($plugin == $this -> name) {
            wp_redirect(admin_url("options-general.php?page=hand-off"));  //dashboard
            exit;
        }
    }
}

$wpHandoff = new wpHandoff();

?>
