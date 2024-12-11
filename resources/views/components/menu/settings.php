<div class="w-full overflow-y-hidden overflow-x-auto mb-8">
    <div class="flex gap-3 border-b-4 border-neutral-200 text-lg font-baloo font-medium leading-4 w-full">
        <a href="<?= $this->getRoute('app.admin.settings'); ?>" class="flex items-center gap-3 h-12 px-4 py-2 border-b-4 -mb-1 <?= ($this->isActive('app.admin.settings') ? 'border-neutral-950' : 'border-transparent') ?>">
            <i class="fa-solid fa-cog"></i>
            <span>General</span>
        </a>
        <a href="<?= $this->getRoute('app.admin.users'); ?>" class="flex items-center gap-3 h-12 px-3 border-b-4 -mb-1 <?= ($this->isActive('app.admin.users') ? 'border-neutral-950' : 'border-transparent') ?>">
            <i class="fa-solid fa-users"></i>
            <span>Users</span>
        </a>
        <a href="<?= $this->getRoute('app.admin.access-control'); ?>" class="flex items-center gap-3 h-12 px-3 border-b-4 -mb-1 <?= ($this->isActive('app.admin.access-control') ? 'border-neutral-950' : 'border-transparent') ?>">
            <i class="fa-solid fa-shield"></i>
            <span>Access Control</span>
        </a>
        <a href="<?= $this->getRoute('app.admin.modules'); ?>" class="flex items-center gap-3 h-12 px-3 border-b-4 -mb-1 <?= ($this->isActive('app.admin.modules') ? 'border-neutral-950' : 'border-transparent') ?>">
            <i class="fa-solid fa-plug"></i>
            <span>Modules</span>
        </a>
    </div>
</div>