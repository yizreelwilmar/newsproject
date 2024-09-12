<?php $this->load->view('auth/templates/header') ?>

<body id="page-top">

   <div id="wrapper">

      <!-- Sidebar -->
      <?php $this->load->view('back/layouts/_sidebar') ?>

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

         <!-- Main Content -->
         <div id="content">

            <!-- Topbar -->
            <?php $this->load->view('back/layouts/_navbar') ?>

            <!-- Begin Page Content -->
            <div class="container">

               <div class="row">
                  <div class="col-md-8 offset-md-2">
                     <h1 class="my-4 text-center"><?php echo lang('create_user_heading');?></h1>
                     <p class="text-center"><?php echo lang('create_user_subheading');?></p>

                     <?php if ($message) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                           <div id="infoMessage"><?php echo $message; ?></div>
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                     <?php endif ?>

                     <?php echo form_open("auth/create_user"); ?>

                     <div class="form-group">
                        <label><?php echo lang('create_user_fname_label', 'first_name'); ?></label>
                        <?php echo form_input($first_name, '', ['class' => 'form-control', 'autocomplete' => 'off']); ?>
                     </div>

                     <div class="form-group">
                        <label><?php echo lang('create_user_lname_label', 'last_name'); ?></label>
                        <?php echo form_input($last_name, '', ['class' => 'form-control', 'autocomplete' => 'off']); ?>
                     </div>

                     <?php if ($identity_column !== 'email') : ?>
                        <div class="form-group">
                           <label><?php echo lang('create_user_identity_label', 'identity'); ?></label>
                           <?php echo form_error('identity'); ?>
                           <?php echo form_input($identity, '', ['class' => 'form-control', 'autocomplete' => 'off']); ?>
                        </div>
                     <?php endif ?>

                     <div class="form-group">
                        <label><?php echo lang('create_user_company_label', 'company'); ?></label>
                        <?php echo form_input($company, '', ['class' => 'form-control', 'autocomplete' => 'off']); ?>
                     </div>

                     <div class="form-group">
                        <label><?php echo lang('create_user_email_label', 'email'); ?></label>
                        <?php echo form_input($email, '', ['class' => 'form-control', 'autocomplete' => 'off']); ?>
                     </div>

                     <div class="form-group">
                        <label><?php echo lang('create_user_phone_label', 'phone'); ?></label>
                        <?php echo form_input($phone, '', ['class' => 'form-control', 'autocomplete' => 'off']); ?>
                     </div>

                     <div class="form-group">
                        <label><?php echo lang('create_user_password_label', 'password'); ?></label>
                        <?php echo form_input($password, '', ['class' => 'form-control']); ?>
                     </div>

                     <div class="form-group">
                        <label><?php echo lang('create_user_password_confirm_label', 'password_confirm'); ?></label>
                        <?php echo form_input($password_confirm, '', ['class' => 'form-control']); ?>
                     </div>

                     <div class="form-group text-center">
                        <?php echo form_submit('submit', lang('create_user_submit_btn'), ['class' => 'btn btn-primary']); ?>
                     </div>

                     <?php echo form_close(); ?>

                  </div>
               </div>

            </div>
            <!-- End Page Content -->

         </div>
         <!-- End Main Content -->

         <!-- Footer -->
         <?php $this->load->view('back/layouts/_footer') ?>

      </div>
      <!-- End Content Wrapper -->

   </div>
   <!-- End Page Wrapper -->

   <!-- Scroll to Top Button-->
   <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
   </a>

<?php $this->load->view('auth/templates/footer') ?>
