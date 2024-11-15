<?php $this->layout('layout::application_old', ['title' => $title]);
$locationArray = unserialize(file_get_contents("http://ip-api.com/php/".$_SERVER['REMOTE_ADDR'])); ?>

<div class="message-box">
	<h2>Website SQL v<?php echo $this->getStrings()->getVersion(); ?></h2>
	<p style="font-size: 15px;text-align: start;">
		<b>Browser:</b> <?php echo $_SERVER['HTTP_USER_AGENT']; ?><br>
		<b>User IP:</b> <?php echo $_SERVER['REMOTE_ADDR']; ?><br>
		<b>User Location:</b> <?php echo $locationArray['city']; ?>, <?php echo $locationArray['countryCode']; ?><br>
		<b>User ISP:</b> <?php echo $locationArray['isp']; ?><br>
		<b>Database Version:</b> <?php echo $this->getSetting('DatabaseVersion'); ?><br>
		<b>Application Version:</b> <?php echo $this->getStrings()->getVersion(); ?><br>
		<b>Server OS:</b> <?php echo PHP_OS; ?><br>
		<b>Website Name:</b> <?php echo $this->getSetting('WebsiteName'); ?><br>
		<b>Website Theme:</b> <?php echo $this->getSetting('WebsiteTheme'); ?><br>
		<b>System Time:</b> <?php echo date("Y-m-d H:i:s"); ?><br>
	</p>
</div>