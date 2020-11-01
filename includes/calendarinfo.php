<?php
class calendarInfo 
{
    function __construct() { }
    function getCalendarFields($id)
    {
        global $wpdb;
        $data = $wpdb->get_var($wpdb->prepare('SELECT data FROM ' . $wpdb->prefix . 'wpdevart_forms WHERE id="%d"', $id));
        return $data;
    }
}
    
?>