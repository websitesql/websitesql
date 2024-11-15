<?php $this->layout('layout::application_old', ['title' => $title]); ?>

<div class="page wrapper">
    <?php $this->insert('component::old/menu', array('title' => $title)); ?>
	<div class="row" style="margin: 0 -3px;">
        <!-- Beta dashboard (Try out the beta dashboard) -->
        <div class="col-sm-12 col-lg-12">
            <a class="tile" style="background-color: #ff9800;" href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=beta">
                <i class="fa-solid fa-fw fa-tachometer-alt"></i>
                <h1>Try the Beta Dashboard</h1>
                <p>Experience the new features and improvements</p>
            </a>
        </div>
    
        <div class="col-sm-6 col-lg-4">
			<a class="tile" style="background-color: #2196f3;" href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=view/Pages">
                <i class="fa-solid fa-fw fa-file"></i>
                <h1>Pages</h1>
                <p><?php echo $contentCountPublished['Pages'] ?? 0; ?> published pages</p>
                <p><?php echo $contentCountDraft['Pages'] ?? 0; ?> draft pages</p>
            </a>
		</div>
		<?php // echo get_custom_dashboard_tiles(); ?>
		<div class="col-sm-6 col-lg-4">
			<a class="tile" style="background-color: #3f51b5;" href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=media">
                <i class="fa-solid fa-fw fa-folder"></i>
                <h1>Media</h1>
                <p><?php echo number_format($mediaUsage / 1048576, 1); ?>MB used</p>
            </a>
		</div>
		<div class="col-sm-6 col-lg-4">
			<a class="tile" style="background-color: #03a9f4;" href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=users">
                <i class="fa-solid fa-fw fa-user-gear"></i>
                <h1>Users</h1>
                <p>Users & Permissions</p>
                <p>Active Users: <?php echo $activeUsers; ?></p>
            </a>
		</div>
		<div class="col-sm-6 col-lg-4">
			<a class="tile" style="background-color: #4caf50;" href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=settings">
                <i class="fa-solid fa-fw fa-wrench"></i>
                <h1>Settings</h1><p>Licence vaild until:</p>
                <p><?php echo (!empty($licenceKey[3]) ? date("d/m/Y", $licenceKey[3]) : 'No licence key found') ?></p>
            </a>
		</div>
	</div>			
</div>