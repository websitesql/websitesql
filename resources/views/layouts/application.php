<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->insert('component::application/head', array('title' => $title)); ?>
    </head>
    <body class="dark:bg-zinc-900 <?php if ($_COOKIE['darkmode'] === "true") {echo 'dark';} ?>">
        <?php $this->insert('component::application/header', array('title' => $title)); ?>
		<div class="pt-16 sm:pt-0 pl-0 sm:pl-72 w-full h-full">
            <?php echo $this->section('content'); ?>
            <?php $this->insert('component::application/footer'); ?>
        </div>
    </body>
</html>