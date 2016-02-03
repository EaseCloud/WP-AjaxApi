<?php

abstract class AjaxApi {

    static $action;  // Api handle key

    /**
     * Api arguments
     * @var array
     * array(
     *  array(
     *      'key' => argument key,
     *      'title' => argument title,
     *      'method' => GET | post,
     *      'required' => TRUE | false,
     *      'default' => '',
     *  ),
     * )
     */
    static $arguments = array();

    /**
     * Whether the api allow login user or un-login user.
     * @var string both|user|guest|<TODO:UserRole>
     */
    static $permission = 'both';

    /**
     * The ajax callback function
     * @var callback
     */
    static function callback() {}

    /**
     * Setup the subclass and init the api hook.
     */
    static function init() {

        // Ensure the keys for the arguments is defined
        $argument_default = array(
            'method' => 'get',
            'required' => true,
            'default' => '',
        );
        foreach(static::$arguments as &$arg) {
            $arg = array_merge($argument_default, $arg);
        }

        // Set the hook.
        if(in_array(static::$permission, array('both', 'user'))) {
            add_action('wp_ajax_'.static::$action, function() {
                static::callback();
                exit;
            });
        }
        if(in_array(static::$permission, array('both', 'guest'))) {
            add_action('wp_ajax_nopriv_'.static::$action, function() {
                static::callback();
                exit;
            });
        }

    }

    /**
     * Get the client request url for the api
     * @param array $args
     * @return string|void|WP_Error:
     *  Returns the directing url, or WP_Error for failure.
     */
    static function getUrl($args=array()) {
        $url = admin_url('admin-ajax.php?action='.static::$action);
        foreach(static::$arguments as $arg) {
            if($arg['method'])
            if(isset($args[$arg['key']])) {
                $url .= "&{$arg['key']}={$args[$arg['key']]}";
            } elseif(@$arg['default']) {
                $url .= "&{$arg['key']}={$arg['default']}";
            } elseif(@$arg['required']) {
                return new WP_Error(
                    'required',
                    "{$arg['title']} argument missing!",
                    $args
                );
            }
        }
        return $url;
    }

};