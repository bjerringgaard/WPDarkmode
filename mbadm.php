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

        add_action( 'admin_menu', array($this, 'createMenuItem'));

        add_action( 'admin_init', array($this, 'darkModeSettingsPage'));

        add_action ('admin_menu', array($this, 'DarkmodeUser'));
    }

    function darkModeSettingsPage() {
        add_settings_section(
            'mbadarkmode',
            'Darkmode Settings',
            null,
            'mba-DarkMode'
        );

        register_setting(
            'mbadarkmode',
            'mbadarkmodeplugin'
        );

        add_settings_field( 
            'mbadarkmodeplugin',
            'Enable Darkmode',
            array($this, 'enableDarkMode'),
            'mba-Darkmode',
            'mbadarkmode',
        );
    }

    function enableDarkMode() {
        echo '<input type="checkbox" name="mbadarkmodeplugin" value="1"
        ' . checked(1 , get_option('mbadarkmodeplugin') , false) . '>';
    }

    function createMenuItem() 
    {
        add_submenu_page(
            'options-general.php',                // Where to add the file 
            'MbaDarkmodeSettings',                //Title
            'MbaDarkmode',                        //menu item name 
            'manage_options',                     //Compatibilities
            'mba-DarkMode',                       //Identifier

            array($this , 'settingsPage')
        );
    }

    function settingsPage()
    {
        echo 
            '<div class="wrap">
             <h1> MBA Darkmode Settings </h1>
             <form method="post" action="options.php">'
        ;

            do_settings_sections( 'mba-DarkMode' );
            settings_fields( 'mbadarkmode' );
            submit_button(  );

        echo 
            '</form></div>'
        ;
    }

    function DarkmodeUser(){
        {
            if(get_option('mbadarkmodeplugin') == 1)
            remove_menu_page('index.php');
        }
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


