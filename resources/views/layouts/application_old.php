<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->insert('component::old/head', array('title' => $title)); ?>
    </head>
    <body class="<?php if ($_COOKIE['darkmode'] === "true") {echo 'dark';} ?>">
        <?php $this->insert('component::old/header'); ?>
		<?php echo $this->section('content'); ?>
        <?php $this->insert('component::old/footer'); ?>
    </body>
</html>