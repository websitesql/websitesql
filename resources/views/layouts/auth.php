<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->insert('component::auth/head', array('title' => $title)); ?>
    </head>
    <body class="dark:bg-neutral-800 transition-all duration-300 <?php if (isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] === 'true') {echo 'dark';} ?>">

        <div class="h-svh flex flex-wrap items-center">
            <div class="relative w-full max-w-xl mx-auto p-8">
                <?php $this->insert('component::auth/header', array('title' => $title)); ?>
                <?php echo $this->section('content'); ?>
                <?php $this->insert('component::auth/footer'); ?>     
            </div>
        </div>
    </body>
</html>