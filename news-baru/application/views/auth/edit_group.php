<?php $this->load->view('auth/templates/header') ?>

<body id="page-top">

   <!-- Page Wrapper -->
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
            <div class="container-fluid mt-3">
                <h1 class="mb-4"><?php echo lang('edit_group_heading');?></h1>
                <p><?php echo lang('edit_group_subheading');?></p>

                <?php if($message): ?>
                    <div id="infoMessage" class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo $message;?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?= form_open(current_url(), ['class' => 'form-horizontal']); ?>
                
                <div class="form-row mt-3">
                    <div class="form-group col-md-6">
                        <label for="group_name"><?php echo lang('edit_group_name_label'); ?></label>
                        <?= form_input($group_name, '', ['class' => 'form-control', 'id' => 'group_name', 'autocomplete' => 'off']); ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="description"><?php echo lang('edit_group_desc_label'); ?></label>
                        <?= form_input($group_description, '', ['class' => 'form-control', 'id' => 'description', 'autocomplete' => 'off']); ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <?= form_submit('submit', lang('edit_group_submit_btn'), ['class' => 'btn btn-primary']); ?>
                    </div>
                </div>

                <?= form_close(); ?>

                <a href="<?= base_url('auth') ?>" class="btn btn-secondary btn-sm mt-3">Kembali</a>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php $this->load->view('back/layouts/_footer') ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<?php $this->load->view('auth/templates/footer') ?>
