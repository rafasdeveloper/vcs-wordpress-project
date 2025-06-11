<?php
/*
Plugin Name: Redirect to Login
Description: Redirects unauthenticated users to a custom login page.
Version: 1.0
Author: Your Name
*/

function redirect_to_login() {
    // Check if user is not logged in and is not on the login page
    if (!is_user_logged_in() && !is_admin()) {
        wp_redirect('https://vcs-demo.rafaeldeveloper.co/login'); // Redirect to your specified login page
        exit;
    }
}

// Hook the function into WordPress
add_action('template_redirect', 'redirect_to_login');
