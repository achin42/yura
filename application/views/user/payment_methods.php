<!doctype html>
    <html lang="en">
    <head>
        <?php $this->load->view('inc/header-include');?>
    </head>
    <body>
        <!-- ======================== Topbar start ========================== -->
            <?php $this->load->view('inc/header-menu');?>
        <!-- ======================== Topbar end ========================== -->

        <!-- ======================== Main container start ========================== -->
        <div class="main-container">
      <div class="main-content height-auto <?php if ($user_rows == 0){?>d-flex align-items-center<?php } ?>">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="payment-mode-box">
              <div class="payment-mode-header">
                  <?php $this->load->view('inc/profile-common-menu');?>
                  <div class="right-filter-bax ml-auto d-flex align-items-center" id="show_add_payment_btn" >
                      <span class="v-line"></span>
                      
                      <span class="v-space"></span>
                      <a href="javascript:" onclick="showaddpaymentblock();"  class="btn btn-second" >Link Bank Account</a>
                    </div>
              </div>
              <div class="account-box mar-50 <?php if ($user_rows == 0){?>empty-account-box<?php } ?>" id="paymentmethods">
                    
                    <div id="loadingdata" style="text-align: center;"><b><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="ajax-loader-img loader-img fa-spin" /></b> </div>           
              </div>
              
              <div class="account-box empty-account-box <?php if ($user_rows > 0){?>pl-0<?php } ?>" id="listing_div">
              <form name="frmpaymentmethod" id="frmpaymentmethod" action="<?php echo $this->config->item("site_url");?>user/fn_insert_payment_method" method="post" data-toggle="validator" novalidate="true">
              <table class="table acc-list-table" id="addpaymentblock" <?php if ($user_rows == 0){?>style="display:none;"<?php } else {?>style="display:none;"<?php } ?>>
              <tbody>        
              <tr>
                  
                  <td colspan="3">
                    <div class="card-deatil d-flex align-items-center">
                      
                      <div class="select-card d-flex justify-content-between">
                        <div class="slc-card-left d-flex">
                          <div class="slc-card-icon"><img src="assets/images/mastercard-logo.svg" alt="" /></div>
                          <div class="slc-card-nob form-group"><input required="required" type="text" name="card_number" id="card_number" maxlength="16" class="pay-input" placeholder="Card number"></div>
                        </div>
                        <div class="slc-card-right form-group">
                          <ul class="d-flex">
                            <li><input type="text" name="expiry_month" id="expiry_month" class="pay-input" maxlength="2" placeholder="MM" style="width: 50px;"></li>/
                            <li><input type="text" name="expiry_year" id="expiry_year" class="pay-input" maxlength="2" placeholder="YY" style="width: 50px;"></li>
                            <li><input type="text" class="pay-input" required="required" maxlength="4" name="cvc" id="cvc" placeholder="CVC" style="width: 35px;"></li>
                          </ul>
                        </div>
                      </div>
                      <div class="slc-name form-group"><input type="text" required="required" name="name_on_card" id="name_on_card" class="pay-input pay-input-2" placeholder="Name on card"></div>
                    </div>
                  </td>
                  
                  <td style="width: 90px; text-align: right;">
                    

                    <input type="button" name="submit" id="insert_payment_btn" class="btn btn-primary" value="Save" onclick="funAjaxPostSavePayment(this.form)" style="min-width: 86px;float: right;">

                    <button type="button" id="wait_insert_payment_btn" class="btn btn-default display-hide"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> Submit</button>

                  </td>
                  
            </tr>
            </tbody>
            </table>
            </form>
            </div>

            <form name="frmpaymentmethoddel" id="frmpaymentmethoddel" action="<?php echo $this->config->item("site_url");?>user/fn_delete_payment_method" method="post" data-toggle="validator" novalidate="true">
                <input type="hidden" name="payment_sk" id="payment_sk">
            </form>
              


            </div>
            </div>
          </div>
        </div>
        <div class="currency-container">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="payment-connect">
                  <p class="">DIGITAL CURRENCIES</p>
                  <ul class="dgtc-list d-flex flex-wrap align-items-center justify-content-between">
                    <li>
                      <div class="dgtc-list-box">
                        <div class="dgtc-icon">
                          <img src="assets/images/ethereum-logo.svg" alt="" />
                        </div>
                        <a href="#" class="span-connect">Connect</a>
                      </div>
                    </li>
                    <li>
                      <div class="dgtc-list-box">
                        <div class="dgtc-icon">
                          <img src="assets/images/bitcoin-logo.svg" alt="" />
                        </div>
                        <a href="#" class="span-connect">Connect</a>
                      </div>
                    </li>                    
                    <li>
                      <div class="dgtc-list-box">
                        <div class="dgtc-icon">
                          <img src="assets/images/stellar-logo.svg" alt="" />
                        </div>
                        <a href="#" class="span-connect">Connect</a>
                      </div>
                    </li>
                    <li>
                      <div class="dgtc-list-box">
                        <div class="dgtc-icon">
                          <img src="assets/images/yura-logo.svg" alt="" />
                        </div>
                        <a href="#" class="span-connect">Connect</a>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>      
    </div>
        <!-- ======================== Main container end ========================== -->
    
        <?php $this->load->view('inc/footer-include');?>
        <?php $this->load->view('inc/js/payment-method-js');?>
  </body>
</html>