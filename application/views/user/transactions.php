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
      <div class="main-content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="payment-mode-box">
              <div class="payment-mode-header">
                  <?php $this->load->view('inc/profile-common-menu');?>
                  
              </div>

              <div class="account-box <?php if ($user_rows == 0){?>empty-account-box<?php } ?>" id="paymentmethods">
                  <div id="loadingdata" style="text-align: center;"><b><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> </div>
                
              </div>              
            </div>
            </div>
          </div>
        </div>
        
      </div>      
    </div>
        <!-- ======================== Main container end ========================== -->
    
        <?php $this->load->view('inc/footer-include');?>
        <?php $this->load->view('inc/js/transactions-js');?>
  </body>
</html>