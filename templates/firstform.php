<?php
//<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,700i|Roboto:300,400" rel="stylesheet">
/*
* The booking plugin will provide the following variables:
* Array 
* ( 
 * 
 * [wpdevart_form_checkin1] => 2019-06-10 
 * [wpdevart_form_checkout1] => 2019-06-10 
 * [wpdevart_count_item1] => 1 
 * 
* [wpdevart_form_field1] => Pedro 
* [wpdevart_form_field2] => Carbonell 
* [wpdevart_form_field3] => rpcarnell@gmail.com 
* [wpdevart_form_field4] => 421321323
 * 
* [wpdevart_form_field5] => this is the descript
* [wpdevart-submit1] => 
 * [wpdevart_extra_price_value1] => 0 
* [wpdevart_total_price_value1] => 100 
 * 
* [wpdevart_price_value1] => 100 
* [wpdevart_sale_type1] => 
* [id] => 1 
* [task] => save ) 
* 
*  */
wp_enqueue_style('simpleform');
 
     ?>                    
<script src="https://secure.nmi.com/token/Collect.js" data-tokenization-key="4xyRnr-Kn7ca4-G322Ts-dRjTuz" data-custom-css='{
            "background-color": "#00aa00",
            "color": "#0000ff"
        }'
        data-invalid-css='{
            "background-color":"red",
            "color":"white"
        }'
        data-valid-css='{
            "background-color":"#00aa00",
            "color":"black"
        }'
        data-placeholder-css='{
            "background-color":"#808080",
            "color":"green"
        }'
        data-focus-css='{
            "background-color":"#202020",
            "color":"yellow"
        }'></script>
        <script>
            function validateForm(form)
            {
                <?php
                if (($fields_info)) {
                foreach ($fields_info as $fi)
                {   
                    if (!is_object($fi)) continue;
                    if (!isset($fi->label)) continue;
                    if (!isset($fi->required) || $fi->required != 'on') continue;
                     
                 ?>
                var fname = form.elements['wpdevart_<?php echo $fi->name;?>'].value;
                if (!fname || fname == '') { alert('Please enter your <?php echo $fi->label;?>'); return false; }

                <?php
                } }
                ?>
                return true;
            }
            document.addEventListener('DOMContentLoaded', function () {
                CollectJS.configure({
                    'paymentSelector' : '#customPayButton',
                    'theme': 'bootstrap',
                    'primaryColor': '#116089',
                    'secondaryColor': '#505050',
                    'buttonText': 'SUBMIT ME!',
                    'instructionText': 'Enter Card Info Below',
                    'paymentType': 'cc',
                    'fields': {
                        'cvv': {
                            "display":'show'
                        }
                    },
                    'callback' : function(response) {
                        var input = document.createElement("input");
                        input.type = "hidden";
                        input.name = "payment_token";
                        input.value = response.token;
                        var form = document.getElementsByTagName("form")[0];
                        var valid = validateForm(form);
                        if (false === valid) return;
                        form.appendChild(input);
                        form.submit();
                    }
                });
            });
        </script>
        <br />
 <div class="container">
     <h2 style="margin: auto; text-align: center;">Payment Form</h2><br />
                   <form class="well form-horizontal col-12 col-md-8 offset-md-2 drfitform" method="post">
                      <fieldset>
                          <?php
                        if (($fields_info)) {
                        foreach ($fields_info as $fi)
                        {   
                            if (!is_object($fi)) continue;
                            if (!isset($fi->label)) continue;
                            if (isset($fi->isemail) && $fi->isemail == 'on')
                            {
                                echo '<input type="hidden" name="email" value="'.$_POST['wpdevart_'.$fi->name].'" />';
                            }
                            
                            ?>
                          <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo $fi->label;?></label>
                            <div class="col-md-12 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                   <input id="fullName" name="wpdevart_<?php echo $fi->name;?>" value="<?php echo $_POST['wpdevart_'.$fi->name]?>" placeholder="<?php echo $fi->label;?>" class="form-control" required="true" type="text"></div>
                            </div>
                         </div>
                                <?php
                        }}
                          ?>
                          
                         <div class="form-group">
                             
                            <div class="col-md-12 inputGroupContainer">
                               <div class="input-group"><span class="input-group-addon">
                                       <i class="glyphicon glyphicon-earphone"></i></span>
                                      <button id="customPayButton" type="button">Submit Payment</button>
                               
                               </div>
                            </div>
                         </div>
                      </fieldset>
                       <input type="hidden" name="wpdevart_form_checkin1" value="<?php echo $_POST['wpdevart_form_checkin1'];?>" />
                       <input type="hidden" name="wpdevart_form_checkout1" value="<?php echo $_POST['wpdevart_form_checkout1'];?>" />
                        <input type="hidden" name="wpdevart_count_item1" value="<?php echo $_POST['wpdevart_count_item1'];?>" />
                        <input type="hidden" name="wpdevart_total_price_value1" value="<?php echo $_POST['wpdevart_total_price_value1'];?>" />
                         
                          <input type="hidden" name="wpdevart_extra_price_value1" value="<?php echo $_POST['wpdevart_extra_price_value1'];?>" />
                          <input type="hidden" name="wpdevart_price_value1" value="<?php echo $_POST['wpdevart_price_value1'];?>" />
                          <input type="hidden" name="wpdevart-submit1" value="<?php echo $_POST['wpdevart-submit1'];?>" />
                        <input type="hidden" name="wpdevart_sale_type1" value="<?php echo $_POST['wpdevart_sale_type1'];?>" />
                            <input type="hidden" name="id" value="<?php echo $_POST['id'];?>" />
                            <input type="hidden" name="formsubmitted" value="1" />
                            
 
 
 
 
                   </form>
                 
    </div>
        
        