<?php $this->layout('layout::auth', ['title' => $title]); ?>

<div class="font-baloo text-gray-800 dark:text-white transition-all duration-300">
    <h1 class="text-3xl font-bold mb-1">Register for an Account</h1>
    <p class="text-base">Welcome to <?= $this->getApplicationName(false); ?>, please enter your details below to create an account.</p>

    <?php if(isset($error)): ?>
        <div class="mt-5">
            <div class="bg-red-100 dark:bg-red-600 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded relative transition-all duration-300" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if(isset($success)): ?>
        <div class="mt-5">
            <div class="bg-green-100 dark:bg-green-600 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-100 px-4 py-3 rounded relative transition-all duration-300" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"><?php echo $success; ?></span>
            </div>
        </div>
    <?php endif; ?>

    <form action="" method="post" class="mt-5">
        <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="firstname" class="block text-base font-medium text-gray-700 dark:text-white transition-all duration-300">Firstname</label>
                <input type="text" name="firstname" id="firstname" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-transparent" required>
            </div>
            <div>
                <label for="lastname" class="block text-base font-medium text-gray-700 dark:text-white transition-all duration-300">Lastname</label>
                <input type="text" name="lastname" id="lastname" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-transparent" required>
            </div>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-base font-medium text-gray-700 dark:text-white transition-all duration-300">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-transparent" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-base font-medium text-gray-700 dark:text-white transition-all duration-300">Password</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-transparent" required>
        </div>
        <div class="mb-4">
            <label for="confirm_password" class="block text-base font-medium text-gray-700 dark:text-white transition-all duration-300">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border indigo-500 sm:text-sm bg-transparent" required>
        </div>
        <div class="mt-6">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
            <button type="submit" name="doRegister" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
                Register
            </button>
        </div>
    </form>

    <div class="mt-5 flex flex-col gap-3">
        <a href="<?= $this->getRoute('app.login'); ?>" class="text-base text-indigo-600 dark:text-white hover:text-indigo-700 dark:hover:text-neutral-300 transition-all duration-300">Already have an account? Sign in here.</a>
    </div>
</div>