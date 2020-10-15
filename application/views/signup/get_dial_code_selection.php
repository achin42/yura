<option value="">Select Area Code</option>
<?php if($get_all_dial_code_record_count > 0){ ?>
    <?php foreach($q_get_all_dial_code as $get_all_dial_code){ ?>
        <option value="<?php echo $get_all_dial_code->dial_code_country_code;?>" <?php if($country_code == $get_all_dial_code->dial_code_country_code){ ?>selected<?php } ?> ><?php echo $get_all_dial_code->dial_code_country_name;?> <?php echo $get_all_dial_code->dial_code;?></option>
    <?php } ?>                                                    
<?php } ?>