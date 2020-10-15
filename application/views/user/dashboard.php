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
        <div class="main-container dashboard-container d-flex">
            <div class="dashboard-content">
                <div class="dashboard-left d-flex align-items-center">
                    <div class="dl-box">
                        <?php 
                            if($user_login_mode == 'AGENCY'){
                                $all_default_desc_text = 'Clients';                                                    
                            }else{
                                $all_default_desc_text = 'Agencies';                                                                                    
                            }
                        ?>
                        <div class="dl-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/Project.svg" alt="" /></div>
                        <h5>Glad you're here. Create a project!</h5>
                        <p class="cluster-pink-text">A <strong>CLUSTER</strong> would let you manage multiple PROJECTS with it.<br /><strong>PROJECTS</strong> are visually composed through C-FLOWS (C for Collaboration).<br /><strong>C-FLOWS</strong> can be changed. Also after signing.</p>
                        <p><strong>For example:</strong> A Cluster title could be: 'Rebranding 2020'<br />Then Project titles could be: 'Brandbook' and 'Website' as these are part of 'Rebranding 2020'. Both projects will have separate C-flows. If you don't need to use a cluster as an umbrella above your projects, then we advise you to repeat the CLIENT NAME.</p> 
                        <?php if(strtolower($user_login_mode) == 'agency'){ ?><div class="cp-btn"><a href="javascript:void(0)" class="btn btn-primary" onClick="openCreateClusterModal();" >Create your first project</a></div><?php } ?>
                    </div>
                </div> 
                <div class="dashboard-right">
                    <div class="dr-box add-border">
                        <div class="dr-icon"><img src="<?php echo $this->config->item("site_url");?>assets/images/Tasks.svg" alt="" /></div>
                        <p>So this is where we'll list your <strong>Tasks</strong> once you create and start executing C-Flows.</p>
                        <p>At some stage we will create integrations with your favorite task management platform like <strong>Jira,</strong> <strong> Asana</strong> or <strong>Trello</strong> and will make your life sweet as honey!</p>
                    </div>
                </div>             
            </div>
        </div>
        <!-- ======================== Main container end ========================== -->
        
        <!-- ======================= Modal start ======================= -->
        <div class="modal fade client-detail-popup" id="create_project_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content" id="create_project_content"></div>
            </div>
        </div> 
        <div class="modal fade create-agreement-popup" id="create_agreement_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content" id="create_agreement_content"></div>
            </div>
        </div>
        <div class="modal fade cluster-popup-modal" id="create_cluster_popup" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content cluster-content" id="create_cluster_content">
                </div>
            </div>
        </div>
        <!-- ======================= Modal end ======================= -->
        
        <?php $this->load->view('inc/footer-include');?>
        <?php $this->load->view('inc/js/dashboard-js');?>
        <?php $this->load->view('inc/js/cluster-list-js');?>
  </body>
</html>