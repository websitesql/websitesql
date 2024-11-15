<?php $this->layout('layout::application', ['title' => $title]); ?>

<div class="relative py-5">
    <div class="relative w-full max-w-5xl mx-auto px-8 text-gray-800 dark:text-white">
        <!-- Title -->
        <div class="font-baloo py-6">
            <h1 class="font-extrabold text-4xl">Dashboard</h1>
            <p class="font-light leading-4">Welcome to the dashboard. From here you can manage your website content, users and settings.</p>
        </div>

        <!-- Action buttons -->
        <div class="flex justify-between items-center h-full mb-10">
            <div class="flex items-center gap-3">
                <a href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=dashboard&layout=old" class="flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer">
                    <i class="fas fa-clock-rotate-left"></i>
                    <span>Return to old dashboard</span>
                </a>
            </div>
            <div class="flex items-center gap-3">
                <a class="flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer">
                    <i class="fas fa-sliders"></i>
                    <span>Customise</span>
                </a>
            </div>
        </div>

        <!-- Grid-based dashboard -->
        <div class="grid grid-cols-1 sm:grid-cols-4 md:grid-cols-8 lg:grid-cols-12 lg:auto-rows-dashboard gap-5">
            <!-- Welcome to the beta dashboard message -->
            <div class="border-2 border-yellow-500 text-gray-700 dark:text-white p-6 rounded-xl shadow-md col-span-1 sm:col-span-4 md:col-span-8 font-baloo">
                <div class="flex justify-between gap-4">
                    <div>
                        <h1 class="font-medium mb-1 text-lg">Welcome to the beta dashboard</h1>
                        <p class="font-normal leading-5">After 6 years of development and innovation Website SQL is outgrowing its current User Inverface for the admin dashboard, to reflect this we are bringing in a new and improved UI.</p>
                    </div>
                    <i class="fas fa-tachometer-alt fa-fw text-3xl"></i>
                </div>
            </div>
            
        
            <a class="border-2 border-blue-500 text-gray-700 dark:text-white p-6 rounded-xl shadow-md col-span-1 sm:col-span-4 font-baloo" href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=view/Pages">
                <div class="flex justify-between">
                    <div>
                        <h1 class="font-bold mb-3 text-2xl">Pages</h1>
                        <p class="font-normal leading-5"><?php echo $contentCountPublished['Pages'] ?? 0; ?> published pages</p>
                        <p class="font-normal leading-5"><?php echo $contentCountDraft['Pages'] ?? 0; ?> draft pages</p>
                    </div>
                    <i class="fas fa-file fa-fw text-3xl"></i>
                </div>
            </a>
            <?php // echo get_custom_dashboard_tiles(); ?>
            <a class="border-2 border-orange-500 text-gray-700 dark:text-white p-6 rounded-xl shadow-md col-span-1 sm:col-span-4 font-baloo" href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=media">
                <div class="flex justify-between">
                    <div>
                        <h1 class="font-bold mb-3 text-2xl">Media</h1>
                        <p class="font-normal leading-5"><?php echo number_format($mediaUsage / 1048576, 1); ?>MB used</p>
                    </div>
                    <i class="fas fa-photo-film fa-fw text-3xl"></i>
                </div>
            </a>
            <a class="border-2 border-indigo-500 text-gray-700 dark:text-white p-6 rounded-xl shadow-md col-span-1 sm:col-span-4 font-baloo" href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=users">
                <div class="flex justify-between">
                    <div>
                        <h1 class="font-bold mb-3 text-2xl">Users</h1>
                        <p class="font-normal leading-5">Users & Permissions</p>
                        <p class="font-normal leading-5">Active Users: <?php echo $activeUsers; ?></p>
                    </div>
                    <i class="fas fa-users fa-fw text-3xl"></i>
                </div>
            </a>
            <a class="border-2 border-green-500 text-gray-700 dark:text-white p-6 rounded-xl shadow-md col-span-1 sm:col-span-4 font-baloo" href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=settings">
                <div class="flex justify-between">
                    <div>
                        <h1 class="font-bold mb-3 text-2xl">Settings</h1>
                        <p class="font-normal leading-5">Licence vaild until:</p>
                        <p class="font-normal leading-5"><?php echo (!empty($licenceKey[3]) ? date("d/m/Y", $licenceKey[3]) : 'No licence key found') ?></p>
                    </div>
                    <i class="fas fa-gear fa-fw text-3xl"></i>
                </div>
            </a>
        </div>
	</div>			
</div>