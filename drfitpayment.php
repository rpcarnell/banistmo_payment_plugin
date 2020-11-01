<?php
/*
Plugin Name: Dr. Fit Payment
Description: A plugin that allows customers to make payments after bookings
Version: 1.0
Author: Pedro Carbonell
License: GPLv2
*/

define('BOOKING_EMAIL','admin@drfitclinics.com');
define('DRFIT_URL', plugin_dir_url( __FILE__ ));
define('DRFIT_DIR', dirname( __FILE__ ));
define('SECRETKEY', 'R5Ue6PgwU4a74TkDK73j4Fb8B48tb4uK');//this is credit corp's secrey key
register_activation_hook( __FILE__, 'drfitpayment_install' );
add_shortcode( 'drfitpaymentform', 'showPaymentForm' );
add_shortcode( 'showpaymentres', 'afterPaymentForm' );
add_action('init', 'starting');
 
if ( is_admin() ) 
{ require_once( dirname(__FILE__).'/admin/admin.php' ); }

function starting()
{
    wp_register_style('simpleform', DRFIT_URL."assets/CSS/style.css");
    include_once('includes/gwapi.php');
    include_once('includes/bookappoinment.php');
    include_once('includes/calendarinfo.php');
}
function showPaymentForm() 
{
     if ($_POST && isset($_POST['formsubmitted'])) { do_shortcode('[showpaymentres]'); }
     else 
     {
        $calinf = new calendarInfo();
        if (!isset($_POST['id'])) 
        {
             if (! is_admin()) {
                echo "<script>\n";
                echo "window.location.replace('/booking');\n";
                echo "</script>";
            
             return;
             }
        }
        if (isset($_POST['id']))
        {
            $fields_info = $calinf->getCalendarFields($_POST['id']);
            $fields_info = json_decode($fields_info);
        }
        else $fields_info = false;
        include_once('templates/firstform.php');
     }
       
}
function afterPaymentForm()
{
    $gw = new gwapi();
    $fname = str_replace('&', '', $_POST['wpdevart_form_field1']);
    $lname = str_replace('&', '', $_POST['wpdevart_form_field2']);
    $secretKey = SECRETKEY;
    $query = "first_name=$fname&lastname=$lname&security_key=$secretKey&type=sale&amount=".($_POST['wpdevart_total_price_value1'])."&payment_token=".$_POST['payment_token'];
    $r = $gw->_doPost($query);
    if (1 == $r['response'])
    {
        $booking = new Booking();
        $calinf = new calendarInfo();
        $fields_info = $calinf->getCalendarFields($_POST['id']);
        $fields_info = json_decode($fields_info);
        $from = BOOKING_EMAIL;
        $booking->send_mail($from, "Appointment Approved", $fields_info, $_POST, $_POST['wpdevart_total_price_value1'], $_POST['wpdevart_form_checkin1'], $_POST['email']);
        
        //$fields_info = $calinf->getCalendarFields($_POST['id']);
        //$fields_info = json_decode($fields_info);
        $booking->bookReservation($_POST, $fields_info);
        echo "<div style='padding-left: 30px;'><br /><br /><br /><h2>Payment has been successful</h2>";
        echo "<p>You should receive a payment confirmation e-mail shortly.</p><br /><br /></div>";
    }
    elseif (2 == $r['response'])
    {
        echo "<div style='padding-left: 30px;'><br /><br /><br /><h2>ERROR - Credit Card may have been declined</h2></div>";
    }
    else { echo "<div style='padding-left: 30px;'><br /><br /><br /><h2>ERROR - ".$r['responsetext']."</h2><br /><br /><br /></div>"; }
   // print_r($r); //Array ( [response] => 1 [responsetext] => SUCCESS [authcode] => 123456 [transactionid] => 4735799572 [avsresponse] => [cvvresponse] => [orderid] => [type] => sale [response_code] => 100 )

}
function drfitpayment_install() 
{
   
}

?>
