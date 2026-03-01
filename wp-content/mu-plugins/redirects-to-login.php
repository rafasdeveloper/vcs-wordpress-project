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
        $frontend_url = getenv('VCS_CLIENT_WP_URL') ?: 'http://localhost:8081';
        wp_redirect(rtrim($frontend_url, '/') . '/login');
        exit;
    }
}

// Hook the function into WordPress
add_action('template_redirect', 'redirect_to_login');
