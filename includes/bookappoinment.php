<?php
class Booking
{
    public function __construct() { }
    
    public function send_mail($from, $subject, $fields, $post, $total_price, $res_day, $to)
    {
	    
        $headers = "MIME-Version: 1.0\nFrom: Dr.Fit <" . $from . "> \r\n Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
        $res_day = explode('-', $res_day);
        $res_day = $res_day[1]."-".$res_day[2]."-".$res_day[0];
        $res_info = "<table border='1' style='border-collapse:collapse;min-width: 360px;'>
            <caption style='text-align:left;'>Details</caption>
            <tr><td style='padding: 1px 7px;'>Reservation dates</td><td style='padding: 1px 7px;'>".$res_day."</td></tr>";
            
	 $res_info .= "<tr><td style='padding: 1px 7px;'>Price</td> <td style='padding: 1px 7px;'>$". $total_price ."</td></tr>";
	 
	$res_info .= "</table>";
        $extras = "<br /><br /><table border='1' style='border-collapse:collapse;min-width: 360px;'>";
	$extras .= "<caption style='text-align:left;'>Extra Information</caption>";
        
        foreach ($fields as $fi)
        {   
            if (!is_object($fi)) continue;
            if (!isset($fi->label)) continue;
            //$newFields['wpdevart_'.$fi->name] = $post['wpdevart_'.$fi->name];
            $extras .= "<tr><td style='padding: 1px 7px;'>".$fi->label."</td> <td style='padding: 1px 7px;'>". $post['wpdevart_'.$fi->name] ."</td></tr>";
        }
        
        $extras .= "</table>"; 
        $content = $res_info.$extras;
	$user_error_types = wp_mail($to, $subject, $content, $headers);
    }
    public function createPayment($res_id, $payment_status, $payment_address, $payment_info)
    {
        $tax_value = '';
        $ip = $_SERVER['REMOTE_ADDR'];
        $response = '';
        $date = date('Y-m-d H:i:s');
        $save_db = $wpdb->insert($wpdb->prefix . 'wpdevart_payments', array(
                'res_id' => $res_id,
                'payment_price' => $total,
                'tax' => $tax_value,
                'pay_status' => $payment_status,
                'ip' => $ip,
                'ipn' => $response,
                'payment_address' => $payment_address,
                'payment_info' => $payment_info,
                'modified_date' => $date      
          ), array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
          ));
		 
		  
        if($save_db)  {
                if($payment_status == "Completed" || $payment_status == 'Pending'){
                        //$this->send_mail($res_id,$cal_id, "completed");
                        if(isset($this->theme_option['enable_psuccess_approval']) && $this->theme_option['enable_psuccess_approval'] == "on") {
                                $change_status = $wpdb->update($wpdb->prefix . 'wpdevart_reservations', array('status' => "approved"), array('id' => $res_id));
                                $this->res_edit->change_date_avail_count( $res_id,true);
                        }
                }
                else if($payment_status == 'Failed' || $payment_status == 'Denied' || $payment_status == 'Expired' || $payment_status == 'Voided' || $payment_status == 'Refunded' || $payment_status == 'Processed'){
                        //$this->send_mail($res_id,$cal_id, "failed");
                }
        }
    }
    public function bookReservation($post, $fields_info)
    {
        global $wpdb;
        $newFields = array();
        $form = '';
        foreach ($fields_info as $fi)
        {   
            if (!is_object($fi)) continue;
            if (!isset($fi->label)) continue;
            $newFields['wpdevart_'.$fi->name] = $post['wpdevart_'.$fi->name];
        }
        if (count($newFields) > 0) { $form = json_encode($newFields); }
   
        $currency = '&#36;';
        $sale_percent_value = '';
        $sale_type = '';
        $save_in_db = $wpdb->insert($wpdb->prefix . 'wpdevart_reservations', array(
			'calendar_id' => $post['id'],                       
			'single_day' => '',                       
			'check_in' => $post['wpdevart_form_checkin1'],         
			'check_out' => $post['wpdevart_form_checkout1'],         
			'start_hour' => '',         
			'end_hour' => '',         
			'currency' => $currency,         
			'count_item' => $post['wpdevart_count_item1'],         
			'price' => $post['wpdevart_price_value1'],         
			'total_price' => $post['wpdevart_total_price_value1'],         
			'extras' => '[]',         
			'extras_price' => '',         
			'form' => $form,         
			'address_billing' => '',         
			'address_shipping' => '',         
			'email' => $post['email'],         
			'status' => 'approved',         
			'payment_method' => '',         
			'payment_status' => '',         
			'date_created' => date('Y-m-d H:i',time()),        
			'is_new' => 1,        
			'sale_percent' => $sale_percent_value,     
			'sale_type' => $sale_type     
		  ), array(
			'%d', /*calendar_id*/
			'%s', /*single_day*/
			'%s', /*check_in*/
			'%s', /*check_out*/
			'%s', /*start_hour*/
			'%s', /*end_hour*/
			'%s', /*currency*/
			'%d', /*count_item*/
			'%s', /*price*/
			'%s', /*total_price*/
			'%s', /*extras*/
			'%s', /*extras_price*/
			'%s', /*form*/
			'%s', /*address_billing*/
			'%s', /*address_shipping*/
			'%s', /*email*/
			'%s', /*status*/
			'%s', /*payment_method*/
			'%s', /*payment_status*/
			'%s', /*date_created*/
			'%d', /*is_new*/
			'%s', /*sale_value*/
			'%s' /*sale_type*/
		  ));
    }
}
?>