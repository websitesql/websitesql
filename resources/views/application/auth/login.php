<?php $this->layout('layout::auth', ['title' => $title]); ?>

<div class="font-baloo text-gray-800 dark:text-white transition-all duration-300">
    <h1 class="text-3xl font-bold mb-1">Sign in to your Account</h1>
    <p class="text-base">Welcome back to <?= $this->getApplicationName(false); ?>, please enter your email address and password to sign into your account.</p>

    <?php if(isset($notices)):
        foreach ($notices as $notice): ?>
            <div class="mt-5">
                <div class="bg-blue-100 dark:bg-blue-600 border border-blue-400 dark:border-blue-600 text-blue-700 dark:text-blue-100 px-4 py-3 rounded relative transition-all duration-300" role="alert">
                    <span class="block text-lg font-semibold font-baloo"><?= $this->e($notice['title']); ?></span>
                    <span class="block text-base font-normal font-baloo sm:inline"><?= $this->e($notice['content']); ?></span>
                </div>
            </div>
        <?php endforeach;
    endif; ?>

    <?php if(isset($error)): ?>
        <div class="mt-5">
            <div class="bg-red-100 dark:bg-red-600 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded relative transition-all duration-300" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        </div>
    <?php endif; ?>

    <form action="" method="post" class="mt-5">
        <div class="mb-4">
            <label for="email" class="block text-base font-medium text-gray-700 dark:text-white transition-all duration-300">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-transparent" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-base font-medium text-gray-700 dark:text-white transition-all duration-300">Password</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-transparent" required>
        </div>
        <div class="mt-6">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
            <button type="submit" name="doLogin" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
                Sign in
            </button>
        </div>
    </form>
    <div class="mt-5 flex flex-col gap-3">
        <a href="" class="text-base text-indigo-600 dark:text-white hover:text-indigo-700 dark:hover:text-neutral-300 transition-all duration-300">Forgot your password?</a>
        <a href="<?= $this->getRoute('app.register'); ?>" class="text-base text-indigo-600 dark:text-white hover:text-indigo-700 dark:hover:text-neutral-300 transition-all duration-300">Don't have an account? Register here.</a>
    </div>
</div>