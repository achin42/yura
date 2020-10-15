<div class="new-logo-container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="new-logo-box"><img src="<?php echo $this->config->item("site_url");?>assets/images/yuraio.svg" alt="" /></div>
            </div>
        </div>
    </div>
</div>        
<div class="new-company-detail-container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="new-company-detail-box">
                    <div class="ncd-head d-flex align-items-center px-0">
                        <div class="ncd-head-desc">
                            <h2>Agency or Client</h2>
                            <p>If you decide to act as the other role as well, that can be configured later as well.</p>
                        </div>
                    </div>
                    <div class="ncd-body">
                        <div class="new-card-container px-0 py-0 signup-profile-form">
                            <form name="frm_new_signup_step_3" id="frm_new_signup_step_3" action="<?php echo $this->config->item("site_url");?>login/fn_insert_new_signup_step_3" method="post" data-toggle="validator" novalidate="true">
                                <p>How do you want to use this platform?</p>
                                <div class="form-group">
                                    <label class="has-radio">As an <b> Agency </b> or <b> Freelancer </b> 
                                        <input type="radio" name="user_type" value="agency" <?php if($sess_user_type == 'agency'){ ?>checked="checked"<?php } ?> >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="has-radio">As a <b> Client </b>
                                        <input type="radio" name="user_type" value="client" <?php if($sess_user_type == 'client'){ ?>checked="checked"<?php } ?> >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="has-radio">As both <b> Agency </b> and  <b> Client </b>
                                        <input type="radio" name="user_type" value="both" <?php if($sess_user_type == 'both'){ ?>checked="checked"<?php } ?> >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <span class="hr-line"></span>
                                <div class="form-group">
                                    <label class="has-radio square-radio"> Send me useful emails and updates on this platform
                                        <input type="checkbox" name="marketing_allowed" id="marketing_allowed" value="1" >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group" id="agency_div" <?php if($sess_user_type == 'agency'){ ?><?php }else{ ?>style="display:none;"<?php } ?> >
                                    <label class="has-radio square-radio">Yes, I've read the <a href="https://www.yura.io/legal/Terms-of-Use-for-Agencies-and-freelancers" target="_blank" >terms of use</a>, <a href="https://www.yura.io/legal/community-standards" target="_blank" >community standards</a> and <a href="https://www.yura.io/legal/Services-Privacy-Notice-AND-Cookie-Policy" target="_blank" >privacy and cookie policy</a>
                                        <input type="checkbox" name="terms_condition" id="agency_terms_condition" value="1" >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group" id="client_div" <?php if($sess_user_type == 'client'){ ?><?php }else{ ?>style="display:none;"<?php } ?> >
                                    <label class="has-radio square-radio">Yes, I've read the <a href="https://www.yura.io/legal/Subscription-Agreement-for-Customers" target="_blank" >subscription agreement</a>, <a href="https://www.yura.io/legal/community-standards" target="_blank" >community standards</a> and <a href="https://www.yura.io/legal/Services-Privacy-Notice-AND-Cookie-Policy" target="_blank" >privacy and cookie policy</a>
                                        <input type="checkbox" name="terms_condition" id="client_terms_condition" value="1" >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group" id="both_div" <?php if($sess_user_type == 'both'){ ?><?php }else{ ?>style="display:none;"<?php } ?> >
                                    <label class="has-radio square-radio">Yes, I've read the <a href="https://www.yura.io/legal/Terms-of-Use-for-Agencies-and-freelancers" target="_blank" >terms of use</a>, <a href="https://www.yura.io/legal/community-standards" target="_blank" >community standards</a>, <a href="https://www.yura.io/legal/Subscription-Agreement-for-Customers" target="_blank" >subscription agreement</a> and <a href="https://www.yura.io/legal/Services-Privacy-Notice-AND-Cookie-Policy" target="_blank" >privacy and cookie policy</a>
                                        <input type="checkbox" name="terms_condition" id="both_terms_condition" value="1" >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group form-button">
                                    <input type="button" name="submit" id="btn_signup_step_3" class="btn btn-default" value="NEXT" onclick="funAjaxPostNewSignUpStep3(this.form)">
                                    <button type="button" id="wait_btn_signup_step_3" class="btn btn-default display-hide ml-0"><b class="btn-loader"><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin" style="height: 15px;" /></b> NEXT</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>