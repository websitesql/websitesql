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
                <!-- <a href="" class="flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer">
                    <i class="fas fa-clock-rotate-left"></i>
                    <span>Return to old dashboard</span>
                </a> -->
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
            <?php if (!empty($dashboardTiles)):
                foreach ($dashboardTiles as $tile): ?>
                    <div id="<?= (!empty($tile['id']) ? $tile['id'] : '') ?>" class="border-2 <?= $tile['borderColor']['class'] ?> text-gray-700 dark:text-white p-6 rounded-xl shadow-md <?= $tile['height']['class'] ?> <?= $tile['width']['class'] ?> font-baloo">
                    <?= $tile['content_html']; ?>
                    </div>
                <?php endforeach; 
            endif; ?>
        </div>
	</div>			
</div>