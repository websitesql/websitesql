<?php $user = $this->getCurrentUser(); ?>

<div data-component="wsql-header" class="fixed group w-72 h-full top-0 -left-72 sm:left-0 m-0 z-20 overflow-hidden transition-all border-r border-zinc-200 bg-white dark:border-none dark:bg-zinc-800 shadow">
    <!-- Logo -->
    <div class="invisible sm:visible h-0 sm:h-24 flex items-center px-5">
        <a href="/" class="no-underline">
            <?= $this->getLogo(); ?>
        </a>
    </div>
    <!-- Main Menu -->
    <div class="absolute w-full left-0 transition-all overflow-x-hidden overflow-y-auto max-h-[calc(100vh-150px)] sm:max-h-[calc(100vh-246px)] px-5 pt-6 sm:pt-0">
        <?php foreach ($this->getMainMenuItems() as $item): ?>
            <?php if ($item['is_divider']): ?>
                <?php if ($item['title']): ?>
                    <div class="mt-4 mb-3 mx-auto block h-0 w-11/12 no-underline border-b border-zinc-200 overflow-hidden"></div>
                    <h1 class="font-baloo font-normal m-0 mb-4 mx-auto w-10/12 text-base text-zinc-500 dark:text-white leading-3"><?= $item['title']; ?></h1>
                <?php else: ?>
                    <div class="my-4 mx-auto block h-0 w-11/12 no-underline border-b border-zinc-200 overflow-hidden"></div>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?= $this->getRoute($item['route']); ?>" class="px-4 h-10 w-full transition-all mb-2 flex items-center no-underline cursor-pointerpy-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 <?= ($this->isActive($item['route'], true) ? 'bg-zinc-100 dark:bg-neutral-700 font-medium' : '') ?>">
                    <div class="w-4 h-4 align-top mr-5 flex items-center justify-center no-underline">
                        <i class="<?= $item['icon']; ?> text-zinc-800 dark:text-white"></i>
                    </div>
                    <div class="inline-block">
                        <h1 class="font-baloo font-normal m-0 text-base leading-5 text-zinc-800 dark:text-white"><?= $item['title']; ?></h1>
                    </div>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <!-- Account -->
    <div class="absolute w-full bottom-0 left-0 transition-all overflow-hidden">
        <div class="grid grid-cols-2 gap-3 px-5 py-4">
            <div class="col-span-2">
                <a href="<?= $this->getRoute('app.account'); ?>" class="cursor-pointer w-full flex items-center gap-3 py-3 px-4 border border-zinc-100 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-700 dark:text-white text-sm font-baloo font-normal leading-3 rounded-xl transition-all duration-100">
                    <div class="w-10 h-10 bg-zinc-200 dark:bg-neutral-700 rounded-full">
                        <img src="https://gravatar.com/avatar/<?= hash('sha256', strtolower(trim($user['email']))); ?>?size=40&d=mp" alt="Avatar" class="w-full h-full object-cover rounded-full shadow">
                    </div>
                    <div class="flex flex-col">
                        <h1 class="font-baloo font-semibold m-0 text-lg leading-5 text-zinc-800 dark:text-white">
                            <?= $this->e($user['firstname']) . ' ' . $this->e($user['lastname']); ?>
                            <p class="flex gap-2 font-baloo font-normal m-0 text-xs leading-3 text-zinc-600 dark:text-zinc-300">Manage account</p>
                        </h1>
                    </div>
                </a>
            </div>
            <!-- Dark Mode and Logout -->
            <div>
                <div data-action="wsqlToggleDarkMode" class="cursor-pointer w-full flex items-center justify-center gap-2 h-10 py-2 px-4 border border-zinc-100 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-700 dark:text-white text-base font-baloo font-normal leading-3 rounded-xl transition-all duration-100">
                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 80 80" class="fill-black dark:fill-white animate-spin-fast">
                        <g transform="translate(0.000000,85.000000) scale(0.100000,-0.100000)" stroke="none">
                            <path d="M385 823 c125 -14 222 -79 272 -181 24 -49 28 -69 28 -143 0 -82 -2 -91 -44 -172 -48 -96 -49 -123 -5 -151 37 -25 77 -13 108 33 104 149 83 369 -47 503 -73 75 -179 119 -281 117 -34 -1 -47 -4 -31 -6z"/>
                            <path d="M96 670 c-53 -63 -79 -148 -78 -250 2 -109 45 -203 130 -282 60 -56 123 -86 208 -100 69 -10 121 -6 69 6 -103 24 -136 39 -184 84 -73 69 -96 125 -96 239 0 88 2 94 43 173 23 45 42 88 42 96 0 8 -11 26 -25 39 -34 35 -76 33 -109 -5z"/>
                        </g>
                    </svg>
                </div>
            </div>
            <div>
                <div data-action="wsqlLogout" class="cursor-pointer w-full flex items-center justify-center gap-2 h-10 py-2 px-4 border border-zinc-100 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-700 dark:text-white text-base font-baloo font-normal leading-3 rounded-xl transition-all duration-100">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Menu -->
<div data-component="wsql-header-mobile" class="block sm:hidden fixed shadow w-full h-16 top-0 left-0 z-10 bg-white dark:bg-zinc-800 transition-all duration-300 overflow-hidden">
    <div class="flex items-center justify-between h-full px-8">
        <div class="flex items-center justify-between w-full gap-3">
            <a href="/" class="no-underline">
                <?= $this->getLogo(); ?>
            </a>
            <div>
                <div data-action="wsql-ToggleMobileMenu" class="flex flex-wrap flex-row-reverse content-between items-center w-8 h-6 cursor-pointer">
                    <span class="w-3/4 h-0.5 bg-zinc-900 dark:bg-white transition-all duration-300 origin-top"></span>
                    <span class="w-full h-0.5 bg-zinc-900 dark:bg-white transition-all duration-300"></span>
                    <span class="w-1/2 h-0.5 bg-zinc-900 dark:bg-white transition-all duration-300 origin-bottom"></span>
                </div>
            </div>
        </div>
    </div>
</div>