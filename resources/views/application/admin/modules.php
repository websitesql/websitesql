<?php $this->layout('layout::application', ['title' => $title]); ?>

<div class="relative py-5">
    <div class="relative w-full max-w-5xl mx-auto px-8 text-gray-800 dark:text-white">
        <!-- Title -->
        <div class="font-baloo pt-6 mb-8">
            <h2 class="font-semibold text-lg"><?= $this->e($title); ?></h2>
            <h1 class="font-extrabold text-4xl"><?= $this->e($subtitle); ?></h1>
            <p class="font-light leading-4"><?= $this->e($description); ?></p>
        </div>
        
        <!-- Action buttons -->
        <div class="flex justify-between items-center h-full ">
            <div class="flex items-center gap-3">
                
            </div>
            <div class="flex items-center gap-3">
                
            </div>
        </div>

        <!-- Modules -->
        <div class="overflow-hidden shadow ring-1 ring-gray-600 dark:ring-zinc-600 ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="w-2"></th>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-base font-semibold text-gray-900 dark:text-white sm:pl-6 font-baloo font-regular">Name</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-base font-semibold text-gray-900 dark:text-white font-baloo font-regular">Version</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-base font-semibold text-gray-900 dark:text-white font-baloo font-regular">Author</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 bg-white dark:bg-zinc-800">
                    <!-- If no modules are available -->
                    <?php if (empty($modules)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-base font-normal text-gray-900 dark:text-white text-center">No modules available</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($modules as $module): ?>
                            <tr class="<?= ($module['enabled'] ? 'bg-blue-50 dark:bg-zinc-700' : '') ?>">
                                <td class="w-2 p-0" bgcolor="<?= ($module['enabled'] ? '60a5fa' : '') ?>"></td>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-base text-gray-700 dark:text-white font-baloo font-regular active">
                                    <span class="block"><?= $this->e($module['name']) ?></span>
                                    <span class="text-gray-400 font-light text-sm whitespace-break-spaces"><?= $this->e($module['description']) ?></span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-base text-gray-700 dark:text-white font-baloo font-regular"><?= $this->e($module['version']) ?></td>
                                <td class="whitespace-nowrap px-3 py-4 text-base text-gray-700 dark:text-white font-baloo font-regular"><?= $this->e($module['author']) ?></td>
                                <td class="relative whitespace-nowrap px-3 py-4 text-right text-base font-baloo sm:pr-6">
                                    <form method="POST" action="" class="align-middle">
                                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                        <input type="hidden" name="module" value="<?= $module['id'] ?>">
                                        <?php if ($module['enabled']): ?>
                                            <button type="submit" name="doDisable" class="flex w-auto items-center gap-3 h-10 py-2 px-3 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-2xl shadow-sm transition-all duration-100">
                                                <i class="fa-solid fa-ban"></i>
                                                <span>Disable</span>
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" name="doEnable" class="flex w-auto items-center gap-3 h-10 py-2 px-3 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-2xl shadow-sm transition-all duration-100">
                                                <i class="fa-regular fa-circle-check"></i>
                                                <span>Enable</span>
                                            </button>
                                        <?php endif; ?>
                                </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

	</div>			
</div>