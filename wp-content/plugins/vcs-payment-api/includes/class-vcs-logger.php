<?php
/**
 * VCS Logger
 * 
 * Handles logging for the VCS Payment API plugin.
 */

if (!defined('ABSPATH')) {
    exit;
}

class VCS_Logger {
    
    private static $log_option_name = 'vcs_payment_api_logs';
    private static $max_log_entries = 100;

    /**
     * Log a message.
     *
     * @param string $message The message to log.
     * @param string $level The log level (e.g., 'info', 'debug', 'error').
     */
    public static function log($message, $level = 'info') {
        $logs = get_option(self::$log_option_name, array());
        
        // Add new log entry
        $entry = array(
            'timestamp' => current_time('timestamp'),
            'date'      => current_time('mysql'),
            'level'     => $level,
            'message'   => $message
        );
        
        $logs[] = $entry;
        
        // Trim old log entries
        if (count($logs) > self::$max_log_entries) {
            $logs = array_slice($logs, -self::$max_log_entries);
        }
        
        update_option(self::$log_option_name, $logs);
    }

    /**
     * Get all logs.
     *
     * @return array
     */
    public static function get_logs() {
        return get_option(self::$log_option_name, array());
    }

    /**
     * Clear all logs.
     */
    public static function clear_logs() {
        delete_option(self::$log_option_name);
    }
}
