<?php 
if ($user_rows > 0) {
?>
<table class="table acc-list-table">
<tbody>
<tr>
    <td style="padding-left: 55px;">ACCOUNT NUMBER</td>
    <td>BANK NAME</td>
    <td>ACC HOLDER NAME</td>
    <td>ACC TYPE</td>
    <td colspan="2">DEFAULT CURRENCY</td>
</tr>
<?php
for ($i = 0; $i < $user_rows; $i++) { ?>
<tr>
  <td style="width: 282px;">
    <div class="card-deatil d-flex align-items-center">
      <div class="form-check has-square">
        <input type="radio" name="pmethod" class="square-checkbox paymentid" <?php if ($user_data[$i]->is_active == 1) { ?>checked="checked"<?php } ?>  value="<?php echo $user_data[$i]->bank_sk;?>" id="<?php echo $user_data[$i]->bank_sk;?>" >
        <label for="<?php echo $user_data[$i]->bank_sk;?>"></label>
      </div>

      <div class="card-deatil-nob"><?php echo 'XXXX-XXXX-XXXX-'.substr($user_data[$i]->account_number, -4);?></div>
    </div>
  </td>
  <td>
    <div class="slc-card-left d-flex align-items-center">
      <div class="slc-card-nob"><?php echo $user_data[$i]->bank_name;?></div>
    </div>
  </td>
  <td><?php echo $user_data[$i]->account_name;?></td>
  <td><?php echo $user_data[$i]->account_type;?></td>
  <td><div class="card-exp"><?php echo $user_data[$i]->currency;?></div></td>
  <td style="width: 90px; text-align: right;">
    <div class="dropdown">
        <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fas fa-ellipsis-h"></i></a>
        <div class="dropdown-menu dropdown-menu-right" style="position: absolute; transform: translate3d(-128px, 25px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-end">
            <a class="dropdown-item" href="javascript:" onclick="deletepayment('<?php echo $user_data[$i]->bank_sk;?>')">Delete</a>
            
        </div>
    </div>
  </td>
</tr>

<?php } ?>
</tbody>
</table>
<?php } else { ?>

        <div class="eab-img"><img src="assets/images/bank-acc-icon.svg" alt="" /></div>
                <h6>No Bank Account linked</h6>
                <p>At least one payment method is required for you to be able to make payments to the agencies working for you. It’s recommended to keep an active payment method ahead of time.</p>
    
<?php } ?>

