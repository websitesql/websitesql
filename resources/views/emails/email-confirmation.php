<?php $this->layout('email::base'); ?>

<p>Hi <?= $this->e($firstname); ?>,</p>
<p>Thank you for registering with us. Please click the link below to verify your email address.</p>
<p><a href="<?= $this->e($url); ?>">Verify Email Address</a></p>
<p>If you did not register with us, please ignore this email.</p>
<p>Thank you!</p>
<p><em>The Team</em></p>

<p><small>If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:</small></p>
<p><small><?= $this->e($url); ?></small></p>