<h1><?= $title ?></h1>
<div class="vertical-menu">
	<ul>
		<li class="<?= ($title === "Dashboard" ? 'active' : '') ?>"><a href="/<?= $this->e($this->getStrings()->getAdminFilePath()); ?>?page=dashboard">Dashboard</a></li>
		<li class="<?= ($title === "Pages" ? 'active' : '') ?>"><a href="/<?= $this->e($this->getStrings()->getAdminFilePath()); ?>?page=view&type=Pages">Pages</a></li>
		<?php foreach ($this->getModuleCustomMenuItems() as $custom_menu_item): ?>
            <li class="<?= ($title === $custom_menu_item['name'] ? 'active' : '') ?>"><a href="/<?= $this->e($this->getStrings()->getAdminFilePath()); ?><?= $custom_menu_item['slug']; ?>"><?= $custom_menu_item['name'] ?></a></li>
        <?php endforeach; ?>
		<li class="<?= ($title === "Media" ? 'active' : '') ?>"><a href="/<?= $this->e($this->getStrings()->getAdminFilePath()); ?>?page=media">Media</a></li>
		<li class="<?= ($title === "Modules" ? 'active' : '') ?>"><a href="/<?= $this->e($this->getStrings()->getAdminFilePath()); ?>?page=modules">Modules</a></li>
		<li class="<?= ($title === "Users" ? 'active' : '') ?>"><a href="/<?= $this->e($this->getStrings()->getAdminFilePath()); ?>?page=users">Users</a></li>
		<li class="<?= ($title === "Settings" ? 'active' : '') ?>"><a href="/<?= $this->e($this->getStrings()->getAdminFilePath()); ?>?page=settings">Settings</a></li>
	</ul>
</div>