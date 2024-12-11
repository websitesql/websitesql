<?php $this->layout('layout::application', ['title' => $title]); ?>

<div class="page wrapper">
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