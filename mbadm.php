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
        // Admin Init
        add_action( 'admin_init', array($this, 'darkModeSettingsPage'));

        // Admin Menu 
        add_action( 'admin_menu', array($this, 'createMenuItem'));

        add_action ('admin_menu', array($this, 'DarkModeAlwaysEnable'));

        add_action ('admin_menu', array($this, 'DarkmodeTimedEnable'));
    }

    function darkModeSettingsPage() {
        add_settings_section('mbadmplugin', 'Darkmode Settings', null, 'mba-DarkMode');

        // Always Enable Darkmode
        register_setting( 'mbadmplugin','dmalwaysenableplugin', 'esc_attr');
        add_settings_field('dmalwaysenableplugin', 'Always Enable Darkmode', array($this, 'alwaysEnableDarkMode'), 'mba-DarkMode','mbadmplugin');

        // Enable Timed Darkmode
        register_setting( 'mbadmplugin','dmtimedenableplugin', 'esc_attr');
        add_settings_field('dmtimedenableplugin', 'Enable Timed Darkmode', array($this, 'timedEnableDarkMode'), 'mba-DarkMode','mbadmplugin');

        // Darkmode Start Time
        register_setting( 'mbadmplugin', 'dmstarttimeplugin', 'esc_attr');
        add_settings_field('dmstarttimeplugin', 'Darkmode Starting time', array($this, 'darkModeStartTimer'), 'mba-DarkMode', 'mbadmplugin',);

        // Darkmode End Time
        register_setting( 'mbadmplugin', 'dmendtimeplugin', 'esc_attr');
        add_settings_field('dmendtimeplugin', 'Darkmode Ending time', array($this, 'darkModeEndTimer'), 'mba-DarkMode', 'mbadmplugin',);
    }

    // Always Enable Darkmode
    function alwaysEnableDarkMode() {
        echo '<input type="checkbox" name="dmalwaysenableplugin" value="1"
        ' . checked(1 , get_option('dmalwaysenableplugin') , false) . '>';
    }

    // Enable Darkmode
    function timedEnableDarkMode() {
        echo '<input type="checkbox" name="dmtimedenableplugin" value="1"
        ' . checked(1 , get_option('dmtimedenableplugin') , false) . '>';
    }

    // Darkmode Start Time
    function darkModeStartTimer() {
         $startvalue = get_option('dmstarttimeplugin');
        echo "<input name='dmstarttimeplugin' type='number' value='{$startvalue}'>";
    }

    // Darkmode End Time
    function darkModeEndTimer() {
        $endvalue = get_option('dmendtimeplugin');
       echo "<input name='dmendtimeplugin' type='number' value='{$endvalue}'>";
   }

    function createMenuItem() 
    {
        add_submenu_page(
            'options-general.php',
            'MbaDarkmodeSettings',         
            'MBA DarkMode',                 
            'manage_options',                
            'mba-DarkMode',                       

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
            settings_fields( 'mbadmplugin' );
            submit_button(  );

        echo 
            '</form></div>'
        ;
    }

    // DarkMode Style Sheet  
    function DarkMode() {
        $src = plugins_url( '/style.css', __FILE__ );
        wp_enqueue_style( 'DarkMode', $src, '');
    }


    // Timed Activation
    function DarkmodeTimedEnable() 
    {
        if( get_option('dmtimedenableplugin') == 1 ) {
            $time = date('H', time());
            if( $time >= get_option('dmstarttimeplugin') && $time <= "24") {
                add_action('admin_enqueue_scripts' ,array($this, 'DarkMode'));
            }
            
            else if($time <= get_option('dmendtimeplugin')) {
                add_action('admin_enqueue_scripts' ,array($this, 'DarkMode'));
            }
        }   
    }

    // Always Enable 
    function DarkModeAlwaysEnable(){
        {
            if(get_option('dmalwaysenableplugin') == 1)
            add_action('admin_enqueue_scripts' ,array($this, 'DarkMode'));
        }
    }

} 
$MBAdmObj = new MBADarkMode();