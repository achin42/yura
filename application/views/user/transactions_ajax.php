<?php 
  if ($user_rows > 0) {?>
    <table class="table acc-list-table transaction-tbl">
<tbody>
  <tr>
    <td>TYPE</td>
    <td>MERCHANT</td>
    <td>ACC / CARD NUMBER</td>
    <td>ACC / CARD HOLDER</td>
    <td>AMOUNT</td>
    <td>DATE</td>
  </tr>

  <?php for ($i = 0; $i < $user_rows; $i++) { ?>
  <tr>
    <td style="width: 112px;"><div class="debit-text"><?php echo $user_data[$i]->transaction_type;?></div></td>
    <td style="width: 303px;">
      <div class="merchant-detail">
        <h6>Cognizant pvt ltd.</h6>
        <p>1235, San Francisco, CA-94103, US</p>
      </div>
    </td>
    <td style="width: 270px;">
      <div class="slc-card-left d-flex align-items-center">
        <div class="slc-card-icon bk-acc-icon"><img src="assets/images/visa-logo.svg" alt=""></div>
        <div class="slc-card-nob"><?php echo 'XXXX-XXXX-XXXX-'.substr($user_data[$i]->acc_card_number, -4);?></div>
      </div>
    </td>
    <td style="width: 256px;"><?php echo $user_data[$i]->name;?></td>
    <td><div class="bk-account">USD <?php echo number_format($user_data[$i]->amount,'2');?></div></td>
    <td><?php echo $this->general->display_date_time_format_front($user_data[$i]->created_on);?></td>
  </tr>
  <?php } ?>
  </tbody>
</table>
<?php } else { ?>  


<div class="eab-img"><img src="assets/images/nature-icon.svg" alt="" /></div>
                <h6>No transactions to show</h6>
                <p>Transactions will start populating here once you start paying <br> agencies or get paid by your clients.</p>



<?php } ?>

