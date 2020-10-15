<!doctype html>
    <html lang="en">
    <head>
        <?php $this->load->view('inc/header-include');?> 
        <link rel="stylesheet" href="<?php echo $this->config->item("site_url");?>assets/plugin/custom-scrollbar/jquery.scrollbar.css">       
    </head>
    <body class="show-notification1">
        <!-- ======================== Topbar start ========================== -->
            <?php $this->load->view('inc/header-menu');?>
        <!-- ======================== Topbar end ========================== -->

        <!-- ======================== Main container start ========================== -->
        <div class="main-container block-container d-flex scrollbar-dynamic">
            <div class="ajax-loader-container" style="position: static;">
                <div class="ajax-loader-div" id="ajax-loader-div">
                    <img class="ajax-loader-img loader-img fa-spin" src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg">
                </div>
                <div id="agreement_phase_list_container" class="agreement-phase-list-container"></div>                 
            </div>
            
            <div class="side-block-container display-hide" id="create_block_container_old">
                <div class="sb-head">
                    <h6><span class="sb-head-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/blocks.svg" alt="" /></span>Blocks</h6>
                    <div class="sb-right-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/help.svg" alt="" /></div>
                </div>
                <div class="sb-body scrollbar-dynamic">
                    <ul class="tasks-list">
                        <?php if($pre_defined_block_type_rows > 0){ ?>
                            <?php $i = 1; foreach($q_pre_defined_block_type_data as $pre_defined_block_type_data){ 
                                $block_type_sk = $pre_defined_block_type_data->block_type_sk;
                                $block_type_name = $pre_defined_block_type_data->block_type_name;
                                $block_type_code = $pre_defined_block_type_data->block_type_code; 
                                $description = $pre_defined_block_type_data->description;                                
                            ?>                        
                                <li class="tasks-item task-left-right-margin task-bottom-margin <?php if($i == 1){?>first-task-top-margin<?php } ?><?php if($i == 8){?>last-task-bottom-margin<?php } ?>">
                                    <div class="tasks-box <?php echo $block_type_code;?>">
                                        <div class="task-box-left">
                                			<div class="tasks-box-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/<?php echo $block_type_code;?>.png" alt="" /></div>
                                			<a href="javascript:void(0)" onClick="createBlock('<?php echo $project_sk;?>','<?php echo $block_type_sk;?>','<?php echo $block_type_code;?>');" >
                                                <div class="add-block-icon"><i class="fas fa-plus icon_<?php echo $block_type_code;?>"></i><img src="<?php echo $this->config->item("site_url");?>assets/images/loader.svg" alt="loader" class="loader-img fa-spin loader_icon_<?php echo $block_type_code;?>" style="height: 15px;display:none;"></div>
                                            </a>
                                		</div>
                                        <div class="task-box-right">
                                			<div class="tasks-box-upper">
                                				<h5><?php echo $block_type_name;?></h5>
                                				<strong>2 actions</strong>
                                			</div>
                                			<div class="task-box-lower"><?php echo $description;?></div>
                                		</div>
                                    </div>
                                </li>
                            <?php $i++; } ?>
                        <?php } ?>                            
                    </ul>
                </div>
            </div>
            
            
            <?php if($agreement_data[0]->created_by == $this->session->userdata('fe_user')){ ?>
                <div class="control-panel-container d-flex">
                    <div class="cpanel-left d-flex">
                        <div class="cpanel-head"><div class="cpanel-toggle"><img src="<?php echo $this->config->item("site_url");?>assets/images/arrow-icon.svg" alt="" /></div></div>
                        <div class="cpanel-body">
                            <ul class="cpanel-options d-flex align-items-center">
                                <?php if($agreement_sign_rows > 0){ ?>
                                    <?php 
                                        $first_signer_signed = $q_agreement_sign_data[0]->first_signer_signed;
                                        $second_signer_signed = $q_agreement_sign_data[0]->second_signer_signed;
                                    ?>
                                    <?php if($first_signer_signed == 1 && $second_signer_signed != 1){ ?>
                                        <li class="hover">
                                        	<a href="javascript:void(0);"><img src="<?php echo $this->config->item("site_url");?>assets/images/pending-icon.svg" alt="" /></a>
                                        	<div class="cpanel-popup"><p>Awaiting client signature</p><!--a href="javascript:void(0);" class="btn btn-primary">REMIND</a--></div>
                                        </li>
                                    <?php } ?>
                                    <?php if($first_signer_signed == 1 && $second_signer_signed == 1){ ?>
                                        <li class="hover">
                                        	<a href="javascript:void(0);"><img src="<?php echo $this->config->item("site_url");?>assets/images/approved-icon.svg" alt="" /></a>
                                        	<div class="cpanel-popup"><p>The agreement has been signed by both parties</p></div>
                                        </li>
                                    <?php } ?>
                                    <li class="has-link">
                                    	<a href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $project_sk;?>"><img src="<?php echo $this->config->item("site_url");?>assets/images/download-icon2.svg" alt="" /></a>
                                    </li>
                                <?php } ?>    
                                <?php if($agreement_sign_rows == 0){ ?>
                                        <li class="has-link display-hide" id="agency_download_before_sign">
                                        	<a href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $project_sk;?>"><img src="<?php echo $this->config->item("site_url");?>assets/images/download-icon2.svg" alt="" /></a>
                                        </li>    
                                <?php } ?>
                                <li class="no-link">
                                    <a href="#"><img src="<?php echo $this->config->item("site_url");?>assets/images/currency-icon.svg" alt="" /></a>
                                    <div class="cpanel-popup center cpanel-auto-popup flex-column"><p>Currency - US Dollars</p>
                                        <div class="currency-toggle">
                                            <div class="radio-toggle">
                                                <label class="active"><input type="radio" name="options" id="option1" checked><span>USD</span></label>
                                                <label><input type="radio" name="options" id="option2"><span>EURO</span></label> 
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="no-link add-left-border">
                                    <a href="#"><img src="<?php echo $this->config->item("site_url");?>assets/images/three-arrow-icon.svg" alt="" /></a>
                                    <div class="cpanel-popup center flex-column align-items-start cpanel-app-popup">
                                        <h6>Coming soon</h6>
                                        <p>Very soon we'll let you integrate your C-Flow with your favourite project management platforms like Jira, Asana and Trello.</p>
                                        <div class="cpanel-platforms">
                                            <img src="<?php echo $this->config->item("site_url");?>assets/images/platform-logo.svg" alt="" />
                                            <div class="platform-overlay"></div>
                                        </div>
                                        <img src="<?php echo $this->config->item("site_url");?>assets/images/platform-icon.svg" alt="" class="platform-icon" />
                                    </div>
                                </li>
                                <li class="no-link">
                                    <a href="javascript:void(0)"><img src="<?php echo $this->config->item("site_url");?>assets/images/dots-icon.svg" alt="" /></a>
                                    <div class="cpanel-popup center flex-column align-items-start">
                                        <div class="cpanel-more-nav">
                                            <p><a href="javascript:void(0)" onclick="fn_delete_project_from_detail('<?php echo $project_sk;?>')" >Delete</a></p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <?php if($agreement_sign_rows == 0){ ?>
                        <div class="cpanel-right d-flex1 align-items-center display-hide">
                            <?php if($logged_in_is_agency_verified == '1'){ ?>
                                <div class="align-items-center display-hide" id="review_sign_btn_div">
                                	<div class="cpanel-btn-parent"><a href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $project_sk;?>" class="btn btn-primary">REVIEW</a></div>
                                	<span class="cpanel-arrow"><img src="<?php echo $this->config->item("site_url");?>assets/images/arrow-next.svg" alt="" /></span>
                                	<div class="cpanel-btn-parent">
                                        <a href="javascript:void(0);" class="btn btn-primary show-popup">SIGN</a>
                                        <div class="cpanel-popup flex-column align-items-start cpanel-md-popup">
                                			<h6>Review before signing</h6>
                                			<p>If you haven't reviewed the agreement yet, please download and review first.</p>
                                			<div class="cpanel-cta d-flex mt-4 justify-content-end align-items-center">
                                				<a href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $project_sk;?>" class="link link2">Review</a>
                                				<a href="javascript:void(0);" onclick="funSignAgreementDocument('<?php echo $agreement_data[0]->project_sk;?>')" class="btn btn-primary btn-md ml-5 clk-sign-now">Sign now</a>
                                                <a href="javascript:void(0)" class="btn btn-primary btn-md ml-5 clk-please-wait" style="display:none;">Please wait...</a>
                                			</div> 
                                		</div>
                                    </div>
                                </div>
                            	<div class="cpanel-btn-parent"><a id="btn_finish_editing" href="javascript:void(0);" class="btn btn-primary">FINISH EDITING</a></div>
                            <?php }else{ ?>
                                <div class="align-items-center display-hide" id="review_sign_btn_div">
                                    <div class="cpanel-btn-parent"><a href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $project_sk;?>" class="btn btn-primary" >REVIEW</a></div>
                                    <span class="cpanel-arrow"><img src="<?php echo $this->config->item("site_url");?>assets/images/arrow-next.svg" alt="" /></span>
                                    <?php if($logged_in_is_agency_verified == '0'){ ?>
                          				<?php if($logged_in_verify_later == '1'){ ?>
                          					<div class="cpanel-btn-parent"><a href="javascript:void(0);" data-toggle="popover" class="btn btn-primary not-verified-sign-popover" >SIGN</a></div>
                          				<?php } ?>
                          				<?php if($logged_in_applied_for_manual_verification == '1'){ ?>
                          					<div class="cpanel-btn-parent"><a href="javascript:void(0);" data-toggle="popover" class="btn btn-primary pending-sign-popover" >SIGN</a></div>
                          				<?php } ?>                                                
                          			<?php }elseif($logged_in_is_agency_verified == '2'){ ?>
                          				<div class="cpanel-btn-parent"><a href="javascript:void(0);" data-toggle="popover" class="btn btn-primary not-verified-sign-popover" >SIGN</a></div>
                          			<?php } ?>                                    
                                </div>    
                                <div class="cpanel-btn-parent"><a id="btn_finish_editing" href="javascript:void(0);" class="btn btn-primary">FINISH EDITING</a></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if($agreement_sign_rows > 0){ ?>
                        <?php if($logged_in_is_agency_verified == '1'){ ?>
                            <?php 
                                $first_signer_signed = $q_agreement_sign_data[0]->first_signer_signed;
                                $second_signer_signed = $q_agreement_sign_data[0]->second_signer_signed;
                            ?>
                            <?php if(($first_signer_signed == 0 && $second_signer_signed == 0)){ ?>
                                <div class="cpanel-right d-flex1 align-items-center display-hide">
                                    <div class="align-items-center display-hide" id="review_sign_btn_div">
                                    	<div class="cpanel-btn-parent"><a href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $project_sk;?>" class="btn btn-primary">REVIEW</a></div>
                                    	<span class="cpanel-arrow"><img src="<?php echo $this->config->item("site_url");?>assets/images/arrow-next.svg" alt="" /></span>
                                    	<div class="cpanel-btn-parent">
                                            <a href="javascript:void(0);" class="btn btn-primary show-popup">SIGN</a>
                                            <div class="cpanel-popup flex-column align-items-start cpanel-md-popup">
                                    			<h6>Review before signing</h6>
                                    			<p>If you haven't reviewed the agreement yet, please download and review first.</p>
                                    			<div class="cpanel-cta d-flex mt-4 justify-content-end align-items-center">
                                    				<a href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $project_sk;?>" class="link link2">Review</a>
                                    				<a href="javascript:void(0);" onclick="funSignAgreementDocument('<?php echo $agreement_data[0]->project_sk;?>')" class="btn btn-primary btn-md ml-5 clk-sign-now">Sign now</a>
                                                    <a href="javascript:void(0)" class="btn btn-primary btn-md ml-5 clk-please-wait" style="display:none;">Please wait...</a>
                                    			</div> 
                                    		</div>
                                        </div>
                                    </div>
                                	<div class="cpanel-btn-parent"><a id="btn_finish_editing" href="javascript:void(0);" class="btn btn-primary">FINISH EDITING</a></div>
                                </div>                                        
                            <?php } ?>
                        <?php } ?>                                                                
                    <?php } ?>
                                                        
                </div>
            <?php } ?>
            
            <?php if($agreement_data[0]->created_by != $this->session->userdata('fe_user')){ ?>
                <?php
                    if($agreement_sign_rows > 0){
                        $agreement_sign_sk = $q_agreement_sign_data[0]->agreement_sign_sk;
                        $first_signer_signed = $q_agreement_sign_data[0]->first_signer_signed;
                        $second_signer_signed = $q_agreement_sign_data[0]->second_signer_signed;
                    }else{
                        $first_signer_signed = '0';
                        $second_signer_signed = '0';
                    }                        
                ?>
                <div class="control-panel-container d-flex">
                    <div class="cpanel-left d-flex">
                        <div class="cpanel-head"><div class="cpanel-toggle"><img src="<?php echo $this->config->item("site_url");?>assets/images/arrow-icon.svg" alt="" /></div></div>
                        <div class="cpanel-body">
                            <ul class="cpanel-options d-flex align-items-center">
                                <?php if($agreement_sign_rows == 0){ ?>
                                    <li class="hover">
                                    	<a href="javascript:void(0);"><img src="<?php echo $this->config->item("site_url");?>assets/images/pending-icon.svg" alt="" /></a>
                                    	<div class="cpanel-popup"><p>Awaiting agency to prepare and sign</p><!--a href="javascript:void(0);" class="btn btn-primary">REMIND</a--></div>
                                    </li>
                                <?php } ?>
                                <?php if($agreement_sign_rows > 0 && $first_signer_signed == 0){ ?>
                                    <li class="hover">
                                    	<a href="javascript:void(0);"><img src="<?php echo $this->config->item("site_url");?>assets/images/pending-icon.svg" alt="" /></a>
                                    	<div class="cpanel-popup"><p>Awaiting agency to prepare and sign</p><!--a href="javascript:void(0);" class="btn btn-primary">REMIND</a--></div>
                                    </li>
                                <?php } ?>
                                <?php if($first_signer_signed == 1 && $second_signer_signed == 1){ ?>
                                    <li class="hover">
                                    	<a href="javascript:void(0);"><img src="<?php echo $this->config->item("site_url");?>assets/images/approved-icon.svg" alt="" /></a>
                                    	<div class="cpanel-popup"><p>Agreement signed by both parties</p></div>
                                    </li>
                                <?php } ?>
                                <li class="has-link">
                                	<a href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $project_sk;?>"><img src="<?php echo $this->config->item("site_url");?>assets/images/download-icon2.svg" alt="" /></a>
                                </li>
                                <li class="no-link">
                                    <a href="javascript:void(0);"><img src="<?php echo $this->config->item("site_url");?>assets/images/currency-icon.svg" alt="" /></a>
                                    <div class="cpanel-popup"><p>Currency - US Dollars</p></div>
                                </li>
                                <li class="no-link add-left-border">
                                    <a href="#"><img src="<?php echo $this->config->item("site_url");?>assets/images/three-arrow-icon.svg" alt="" /></a>
                                    <div class="cpanel-popup center flex-column align-items-start cpanel-app-popup">
                                        <h6>Coming soon</h6>
                                        <p>Very soon we'll let you integrate your C-Flow with your favourite project management platforms like Jira, Asana and Trello.</p>
                                        <div class="cpanel-platforms">
                                            <img src="<?php echo $this->config->item("site_url");?>assets/images/platform-logo.svg" alt="" />
                                            <div class="platform-overlay"></div>
                                        </div>
                                        <img src="<?php echo $this->config->item("site_url");?>assets/images/platform-icon.svg" alt="" class="platform-icon" />
                                    </div>
                                </li>                                
                            </ul>
                        </div>
                    </div>
                    
                    <?php if($agreement_sign_rows > 0){ ?>                        
                        <?php if($first_signer_signed == 1 && $second_signer_signed != 1){ ?>
                            <div class="cpanel-right d-flex1 align-items-center display-hide">
                                <div class="d-flex align-items-center">
                                	<div class="cpanel-btn-parent"><a href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $project_sk;?>" class="btn btn-primary">REVIEW</a></div>
                                	<span class="cpanel-arrow"><img src="<?php echo $this->config->item("site_url");?>assets/images/arrow-next.svg" alt="" /></span>
                                	<div class="cpanel-btn-parent">
                                        <a href="javascript:void(0);" class="btn btn-primary show-popup">SIGN</a>
                                        <div class="cpanel-popup flex-column align-items-start cpanel-md-popup">
                                			<h6>Review before signing</h6>
                                			<p>If you haven't reviewed the agreement yet, please download and review first.</p>
                                			<div class="cpanel-cta d-flex mt-4 justify-content-end align-items-center">
                                				<a href="<?php echo $this->config->item("site_url");?>download-agreement/<?php echo $project_sk;?>" class="link link2">Review</a>
                                				<a href="javascript:void(0);" onclick="funSecondSignerSignAgreementDocument('<?php echo $agreement_sign_sk;?>')" class="btn btn-primary btn-md ml-5 clk-sign-now">Sign now</a>
                                                <a href="javascript:void(0)" class="btn btn-primary btn-md ml-5 clk-please-wait" style="display:none;">Please wait...</a>
                                			</div> 
                                		</div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                        
                </div>                    
            <?php } ?>
            
                         
            <div id="create_block_container"></div>
            <div id="add_html_in_block_container"></div>
        </div>
        <!-- ======================== Main container end ========================== -->
        
        <!-- ======================= Modal start ======================= -->
        <div class="modal fade create-phase-popup" id="create_phase_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content" id="create_phase_content"></div>
            </div>
        </div>         
        <!-- ======================= Modal end ======================= -->
        
        <?php $this->load->view('inc/footer-include');?>
        <script src="<?php echo $this->config->item("site_url");?>assets/plugin/custom-scrollbar/jquery.scrollbar.js"></script>        
        <?php $this->load->view('inc/js/project-detail-js');?>        
  </body>
</html>