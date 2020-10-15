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
                  <div class="right-filter-bax ml-auto d-flex align-items-center" id="show_add_payment_btn" style="display:none !important;">
                      <span class="v-line"></span>
                      
                      <span class="v-space"></span>
                      <a href="javascript:" onclick="showaddpaymentblock();"  class="btn btn-second" >Link Bank Account</a>
                    </div>
              </div>

              <div class="account-box mar-50 <?php if ($user_rows == 0){?>empty-account-box<?php } ?>" id="paymentmethods">
                    
                    <div id="loadingdata" style="text-align: center;"><b><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="ajax-loader-img loader-img fa-spin" /></b> </div>           
              </div>
              <div class="account-box empty-account-box <?php if ($user_rows > 0){?>pl-0<?php } ?>" id="listing_div">
              <form name="frmpaymentmethod" id="frmpaymentmethod" action="<?php echo $this->config->item("site_url");?>user/fn_insert_bank_account" method="post" data-toggle="validator" novalidate="true">

                
              <table class="table acc-list-table" id="addpaymentblock" <?php if ($user_rows == 0){?>style="display:none;"<?php } else { ?>style="display:none;"<?php } ?>>
              <tbody>        
              <tr>
                  
                  <td>
                    <div class="card-deatil d-flex align-items-center">
                      
                      <div class="select-card d-flex justify-content-between" style="min-width: 262px;">
                        <div class="slc-card-left d-flex form-group">
                          <div class="slc-card-nob"><input required="required" type="text" name="bank_name" id="bank_name" class="pay-input" placeholder="Bank Name"></div>
                        </div>
                      </div>

                      <div class="select-card d-flex justify-content-between" style="min-width: 262px;">
                            <div class="slc-card-left d-flex form-group">
                              <div class="slc-card-nob"><input required="required" name="account_number" id="account_number" type="text" class="pay-input" placeholder="Account number"></div>
                            </div>
                        </div>
                      <div class="slc-name form-group"><input type="text" name="account_name" id="account_name" required="required" class="pay-input pay-input-2" placeholder="Account Name"></div>

                        
                      
                      
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
            <form name="frmpaymentmethoddel" id="frmpaymentmethoddel" action="<?php echo $this->config->item("site_url");?>user/fn_delete_bank_account" method="post" data-toggle="validator" novalidate="true">
                <input type="hidden" name="bank_sk" id="bank_sk">
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
                  <p class="">DIGITAL WALLETS</p>
                  <ul class="dwlt-list d-flex flex-wrap align-items-center">
                    <li>
                      <a href="" class="dwlt-list-box d-flex flex-wrap" style="background-image: url(<?php echo $this->config->item("site_url");?>assets/images/wl-bg-1.png);">
                        <div class="d-flex flex-wrap">
                            <div class="dwlt-icon">
                            <img src="<?php echo $this->config->item("site_url");?>assets/images/exodus_logo_white.png" alt="" />
                          </div>
                          <div class="wallet-btn">
                             <button  class="btn btn-primary">COLLECT</button>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="" class="dwlt-list-box d-flex flex-wrap" style="background-image: url(<?php echo $this->config->item("site_url");?>assets/images/wl-bg-2.png);">
                        <div class="d-flex flex-wrap">
                            <div class="dwlt-icon">
                            <img src="<?php echo $this->config->item("site_url");?>assets/images/edge.png" alt="" />
                          </div>
                          <div class="wallet-btn wallet-btn-edge      ">
                             <button  class="btn btn-primary">COLLECT</button>
                          </div>
                        </div>
                      </a>
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
        <?php $this->load->view('inc/js/bank-account-js');?>
  </body>
</html>