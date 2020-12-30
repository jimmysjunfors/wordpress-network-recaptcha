<?php
/*
Plugin Name: WPNetwork reCaptcha
Plugin URI: http://
Description: reCaptcha v2 for network installations
Version: 1.0.0
Author: Jimmy Sjunfors / Swapi AB
Author URI: https://swapi.se
*/

defined('ABSPATH') or die();

$wpnre_sitekey = '';
$wpnre_privatekey = '';
$wpnre_secure_ips = array("");

$wpnre_die_message = array();
$wpnre_die_message[1] = "reCAPTCHA validation failed.";
$wpnre_die_message[2] = "Return to login and try again.";

if (!in_array($_SERVER["REMOTE_ADDR"], $wpnre_secure_ips)) {
    add_action('login_form', 'wpnre_add_to_login');
    add_action('login_head', 'wpnre_add_to_head', 10);
    add_action('wp_authenticate', 'wpnre_auth_signon');
    add_action('login_enqueue_scripts', 'wpnre_login_logo');
}

function wpnre_add_to_login() {
    global $wpnre_sitekey;
    echo '<div style="margin-bottom:20px;" class="g-recaptcha" data-sitekey="'.$wpnre_sitekey.'" data-callback="captchacallback"></div>';
}

function wpnre_add_to_head() {
    echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
}

function wpnre_auth_signon() {
    global $wpnre_privatekey;
    $wpnreerror = false;
    if (isset($_POST["log"]) && isset($_POST["pwd"]) && !empty($_POST["log"]) && !empty($_POST["pwd"])) {
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
                'method' => 'POST',
                'body' => array(
                    'secret'=>$wpnre_privatekey,
                    'response'=>$_POST['g-recaptcha-response']
                )
            ));

            $response = json_decode($response["body"], true);

            if (true == $response["success"]) {
                if ($response["hostname"] == $_SERVER["HTTP_HOST"]) {
                    $wpnreerror = false;
                } else {
                    $wpnreerror = "Invalid reCaptcha hostname check";
                }
            } else {
                $wpnreerror = "Invalid reCaptcha (1)";
            }
        } else {
            $wpnreerror = "Invalid reCaptcha (2)";
        }

        if ($wpnreerror) {
            $diemessage = "<center><br><br>".$wpnre_die_message[1]."<br><br>
            Error: ".$wpnreerror."<br><br>
            <a href='/wp-admin'>".$wpnre_die_message[1]."</a>";

            DIE($diemessage);
        } else {
            return true;
        }
    }
}

function wpnre_login_logo() {
    echo '<style type="text/css">
    div#login { width: 350px; }
    </style>';
}
