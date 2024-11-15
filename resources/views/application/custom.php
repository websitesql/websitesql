<?php $this->layout('layout::application_old', ['title' => $title]); ?>

<div class="page wrapper">
    <?php $this->insert('component::old/menu', array('title' => $title)); ?>

    <!-- Error Messages -->
    <?php if (isset($error) && !empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

	<div>
        <?= $content ?>
    </div>
</div>