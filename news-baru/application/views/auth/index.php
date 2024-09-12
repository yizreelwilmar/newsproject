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
                <h1 class="mb-4"><?php echo lang('index_heading');?></h1>
                <p><?php echo lang('index_subheading');?></p>

                <?php if($message): ?>
                    <div id="infoMessage" class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo $message;?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th><?php echo lang('index_fname_th');?></th>
                                <th><?php echo lang('index_lname_th');?></th>
                                <th><?php echo lang('index_email_th');?></th>
                                <th><?php echo lang('index_groups_th');?></th>
                                <th><?php echo lang('index_status_th');?></th>
                                <th><?php echo lang('index_action_th');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <?php foreach ($user->groups as $group): ?>
                                            <?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8'), ['class' => 'badge badge-info']); ?><br>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php echo ($user->active) ? 
                                        anchor("auth/deactivate/".$user->id, lang('index_active_link'), ['class' => 'badge badge-success']) : 
                                        anchor("auth/activate/".$user->id, lang('index_inactive_link'), ['class' => 'badge badge-danger']); ?>
                                    </td>
                                    <td><?php echo anchor("auth/edit_user/".$user->id, 'Edit', ['class' => 'btn btn-sm btn-primary']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo anchor('auth/create_user', lang('index_create_user_link'), ['class' => 'btn btn-sm btn-success mr-2']); ?>
                    <?php echo anchor('auth/create_group', lang('index_create_group_link'), ['class' => 'btn btn-sm btn-secondary']); ?>
                </div>
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
