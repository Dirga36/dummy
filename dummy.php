<?php

/**
 * Plugin Name: Dummy
 * Description: Lorem ipsum, dolor sit amet consectetur adipisicing elit. Rem, cum rerum laudantium perferendis maiores delectus vitae, fuga reiciendis totam aliquid, ea ab repellat asperiores odit?
 * Plugin URI: https://github.com/Dirga36
 * Version: 1.0.0
 * Author: Dirga
 * Author URI: https://github.com/Dirga36
 * Text Domain: dummy-plug
**/

if ( !defined('ABSPATH') )
{
    echo '404';
    exit;
}

class SimpleContactForm
{
    public function __construct()
    {
        add_action('init', array($this, 'create_custom_post_type'));
    }

    public function create_custom_post_type()
    {
        $args = array(
            'public' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'supports' => array('title'),
            'labels' => array(
                'name' => 'Contact Form',
                'singular_name' => 'Contact Form Entry'
            ),
            'capability_type' => 'manage_options',
            'menu_icon' => 'dashicons-media-text',
        );

        register_post_type('simple_contact_form', $args);
    }
}

new SimpleContactForm;