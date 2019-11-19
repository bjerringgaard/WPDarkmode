<?php

/*
 * Plugin Name: MBA DarkMode
 * Description: Dark Mode the Admin Panel 
 * Version: 0.1
 * Author: Marcus Bjerringgaard
 * Author URI: https://bjerringgaard.com
 * Text Domain: DarkMode-Plugin
 */

// Security
defined('ABSPATH') or die;

class MBADarkMode
{
    function __construct()
    {
        add_action('init' , array($this, 'dmEnable'));
    }

    // DarkMode Style Sheet  
    function DarkMode() {
        $src = plugins_url( '/style.css', __FILE__ );
        wp_enqueue_style( 'DarkMode', $src, '');
    }

    // Activate in the Evening / Night
    function dmEnable() 
    {
        $time = date('H', time());
        if( $time >= "18" && $time <= "24") {
            add_action('admin_enqueue_scripts' ,array($this, 'DarkMode'));
        }
        
        else if($time <= "7") {
            add_action('admin_enqueue_scripts' ,array($this, 'DarkMode'));
        }
    }
}

$MBAdmObj = new MBADarkMode();