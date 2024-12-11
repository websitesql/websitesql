<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->insert('component::application/head', array('title' => $title)); ?>
    </head>
    <body class="dark:bg-neutral-800 <?php if (isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] === 'true') {echo 'dark';} ?>">
        <?php $this->insert('component::application/header', array('title' => $title)); ?>
        <!-- Content -->
        <div class="fixed overflow-x-hidden overflow-y-auto pt-16 sm:pt-0 pl-0 sm:pl-72 w-full h-full">
            <div class="flex flex-col h-full">
                <div>
                    <?php echo $this->section('content'); ?>
                </div>
                <div class="mt-auto"></div>
                <div>
                    <?php $this->insert('component::application/footer'); ?>
                </div>
            </div>
        </div>
    </body>
</html>