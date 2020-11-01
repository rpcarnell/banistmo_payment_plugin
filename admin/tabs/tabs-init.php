<?php
function drft_tabs_init(){
    require_once dirname( __FILE__ ) . '/class-tabs-drfit.php';
}
add_action('init','drft_tabs_init',10);
?>