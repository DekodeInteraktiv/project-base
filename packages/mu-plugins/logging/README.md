# Logging

Utility function `write_log` for logging arbitrary variables to the error log. You can view the log in realtime in your terminal by executing `tail -f debug.log` and Ctrl+C to stop.

## Requirements

Enable logging in Wordpress by setting these constants
* `define( 'WP_DEBUG', true )`
* `define( 'WP_DEBUG_LOG', true )`
