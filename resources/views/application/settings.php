<?php $this->layout('layout::application_old', ['title' => $title]); ?>

<div class="page wrapper">
    <?php $this->insert('component::old/menu', array('title' => $title)); ?>
	<form method="POST">
		<div class="button-board">
			<button type="submit" name="doSaveSettings" class="btn btn-success"> Save All </button>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
		</div>
		<script>
			$(function(){
				$( "#tabs" ).tabs();
			});
		</script>
		<div id="tabs">
			<ul>
				<li><a href="#general-settings">General Settings</a></li>
                <li><a href="#content-management">Content Management</a></li>
				<li><a href="#email">Email</a></li>
				<li><a href="#roles">Roles</a></li>
				<li><a href="#licencing">Licencing</a></li>
				<li><a href="#services">Services</a></li>
			</ul>
			<div id="general-settings">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="PostProtected">Website Root</label>
							<input type="text" class="form-control" name="WebsiteSettings[WebsiteRoot]" value="<?php echo $this->e($this->getSetting('WebsiteRoot')); ?>" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="DateFormat">Disable Content Management</label>
							<input type="text" class="form-control" name="WebsiteSettings[DateFormat]" value="<?php echo $this->e($this->getSetting('DateFormat')); ?>" required>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="DateFormat">Date Format</label>
							<input type="text" class="form-control" name="WebsiteSettings[DateFormat]" value="<?php echo $this->e($this->getSetting('DateFormat')); ?>" required>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="DefaultTimezone">Timezone</label>
							<input type="text" class="form-control" name="WebsiteSettings[DefaultTimezone]" value="<?php echo $this->e($this->getSetting('DefaultTimezone')); ?>" required>
						</div>
					</div>
                </div>
			</div>
			<div id="content-management">
                <div>
                    <h2>Website Settings</h2>
                    <p></p>
                </div>
                <div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="PostStatus">Website Name</label>
							<input type="text" class="form-control" name="WebsiteSettings[WebsiteName]" value="<?php echo $this->e($this->getSetting('WebsiteName')); ?>" required>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="WebsiteAnalyticsCode">Website Analytics Code</label>
							<textarea rows="5" class="form-control" name="WebsiteSettings[WebsiteAnalyticsCode]"><?php echo $this->e($this->getSetting('WebsiteAnalyticsCode')); ?></textarea>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="WebsiteLogo">Website Logo</label>
							<select name="WebsiteSettings[WebsiteLogo]" class="form-control">
									<option value="">No image selected</option>
									<?php //$UploadsQuery = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Uploads . "");
									//while ($UploadsRow = mysqli_fetch_array($UploadsQuery, MYSQLI_ASSOC)) {
									//	if (in_array($UploadsRow['FileExtension'], array('gif', 'GIF', 'png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG'))) {
									//		$URL = $WebsiteSettings['WebsiteRoot'] . 'uploads/' . $UploadsRow['FileHash'] . '/' . $UploadsRow['FileName'];
									//		echo '<option '.($URL === $WebsiteSettings['WebsiteLogo'] ? 'selected' : '').' value="' . $URL . '">' . $UploadsRow['FileDescription'] . '</option>';
									//	}
									//} ?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="ApplicationProgrammingInterfaceMode">API Mode</label>
							<select name="WebsiteSettings[ApplicationProgrammingInterfaceMode]" class="form-control">
								<option <?php if ($this->e($this->getSetting('ApplicationProgrammingInterfaceMode')) === 'true') {echo 'selected';} ?> value="true">Yes</option>
								<option <?php if ($this->e($this->getSetting('ApplicationProgrammingInterfaceMode')) === 'false') {echo 'selected';} ?> value="false">No</option>
							</select>	
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="MaintenanceMode">Maintenance Mode</label>
							<select name="WebsiteSettings[MaintenanceMode]" class="form-control">
								<option <?php if ($this->e($this->getSetting('MaintenanceMode')) === 'true') {echo 'selected';} ?> value="true">Yes</option>
								<option <?php if ($this->e($this->getSetting('MaintenanceMode')) === 'false') {echo 'selected';} ?> value="false">No</option>
							</select>	
						</div>
					</div>
				</div>
                <div>
                    <h2>User Service</h2>
                    <p></p>
                </div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="UserAutoApproval">Auto Accept Users</label>
							<select name="WebsiteSettings[UserAutoApproval]" class="form-control">
								<option <?php if ($this->e($this->getSetting('UserAutoApproval')) === 'true') {echo 'selected';} ?> value="true">Yes</option>
								<option <?php if ($this->e($this->getSetting('UserAutoApproval')) === 'false') {echo 'selected';} ?> value="false">No</option>
							</select>	
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="PostStatus">Authenticated Redirect Path</label>
							<input type="text" class="form-control" name="WebsiteSettings[UserAuthenticatedRedirectPath]" value="<?php echo $this->e($this->getSetting('UserAuthenticatedRedirectPath')); ?>">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="PostProtected">Unauthenticated Redirect Path</label>
							<input type="text" class="form-control" name="WebsiteSettings[UserUnauthenticatedRedirectPath]" value="<?php echo $this->e($this->getSetting('UserUnauthenticatedRedirectPath')); ?>">
						</div>
					</div>
				</div>
                <div>
                    <h2>TinyMCE</h2>
                    <p></p>
                </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="PostStatus">Body Class</label>
							<input type="text" class="form-control" name="WebsiteSettings[TinyMCEBodyClass]" value="<?php echo $this->e($this->getSetting('TinyMCEBodyClass')); ?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="PostProtected">CSS Path (within Theme)</label>
							<input type="text" class="form-control" name="WebsiteSettings[TinyMCECSSPath]" value="<?php echo $this->e($this->getSetting('TinyMCECSSPath')); ?>">
						</div>
					</div>
				</div>
			</div>
			<div id="email">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="MailserverHost">Hostname</label>
										<input type="text" class="form-control" name="WebsiteSettings[MailserverHost]" value="<?php echo $this->e($this->getSetting('MailserverHost')); ?>" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="MailserverPort">Port</label>
										<input type="text" class="form-control" name="WebsiteSettings[MailserverPort]" value="<?php echo $this->e($this->getSetting('MailserverPort')); ?>" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="MailserverUseSSL">Use SSL</label>
										<select name="WebsiteSettings[MailserverUseSSL]" class="form-control">
											<option <?php if ($this->e($this->getSetting('MailserverUseSSL')) === 'true') {echo 'selected';} ?> value="true">Yes</option>
											<option <?php if ($this->e($this->getSetting('MailserverUseSSL')) === 'false') {echo 'selected';} ?> value="false">No</option>
										</select>	
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="MailserverUsername">Username</label>
										<input type="text" class="form-control" name="WebsiteSettings[MailserverUsername]" value="<?php echo $this->e($this->getSetting('MailserverUsername')); ?>" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="MailserverPassword">Password</label>
										<input type="password" class="form-control" name="WebsiteSettings[MailserverPassword]" value="<?php echo $this->e($this->getSetting('MailserverPassword')); ?>" required>
									</div>
								</div>
							</div>
			</div>
            <div id="roles">
            </div>
			<div id="licencing">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="PostStatus">Application Licence Key</label>
							<input type="text" class="form-control" name="WebsiteSettings[ApplicationLicenceKey]" value="<?php echo $this->e($this->getSetting('ApplicationLicenceKey')); ?>" required>
						</div>
					</div>
				</div>
				<?php $licence_key = openssl_decrypt(base64_decode( $this->e($this->getSetting('ApplicationLicenceKey')) ), 'AES-256-CBC', '6db407e07779b58df95aadae1a1a4d24a685c5101c7dcb7c998a87356f0b554f', 0, '2b09f666e23fc970');
				$licence_key = explode("/", $licence_key);
				
				$removeDomainItems = array("https://", "http://", "www.", "/");
				$timenow = time();
				
				if (($licence_key[0] === str_replace($removeDomainItems, "", $this->e($this->getSetting('WebsiteRoot')))) && ($licence_key[1] === 'WebSQLv2') && ($licence_key[3] > $timenow)) {
					?><div class="alert alert-success" role="alert">
						<h4 class="alert-heading">Valid Licence</h4>
						<p>Success we were able to validate the licence on the system for the domain "<b style="font-weight: 900;"><?php echo $licence_key[0]; ?></b>" and the licence is valid until "<b style="font-weight: 900;"><?php echo date("d/m/Y", $licence_key[3]); ?></b>". This product is only valid for the following product "<b style="font-weight: 900;"><?php echo $licence_key[1]; ?></b>" and you are eligibility status for updates is "<b style="font-weight: 900;"><?php echo $licence_key[2]; ?></b>".</p>
						<hr>
						<p class="mb-0">This message will only change when you have saved the licence or refreshed the page.</p>
					</div><?php
				} else {
					?><div class="alert alert-danger" role="alert">
						<h4 class="alert-heading">Invalid Licence</h4>
						<p>Sorry we were not able to validate the application licence key for this installation, it is however valid for the domain "<b style="font-weight: 900;"><?php echo $licence_key[0]; ?></b>"</p>
    					<hr>
						<p class="mb-0">This message will only change when you have saved the licence or refreshed the page.</p>
					</div><?php
				} ?>
			</div>
			<div id="services">
				<div class="row">
                    <div class="col-md-12 mb-4">
                        <div>
                            <h2>Logging Services</h2>
                            <p>Various logs are kept when users take actions and when pages are loaded these are helpful when investigating an incident or tracking bugs.</p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#websiteLogModal">Website Log</button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#adminLogModal">Admin Log</button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#transactionLogModal">Transaction Log</button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#messagesLogModal">Messages Log</button>
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#loggingControlModal">Logging Control</button>
                        </div>
                    </div>

                    <!-- SentryGuard -->
                    <div class="col-md-12 mb-4">
                        <h2><i class="fa-solid fa-fw fa-person-military-to-person"></i> SentryGuard</h2>
                        <p>SentryGuard is the new logging service that will soon replace the existing logging system, new feature, better filtering and more configuration options provides a more robust, better formatted and morden way to audit system activity.</p>
						<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#sentryGuardApp">Access SentryGuard</button>
                    </div>

                    <!-- WSQL Connect -->
                    <div class="col-md-12">
                        <h2><i class="fa-solid fa-fw fa-cloud"></i> WSQL Connect</h2>
                        <p>WSQL Connect is a new feature (Coming Soon) that allows you to connect to our Website SQL connect services to access feature like advanced security scanning, uptime monitoring, etc.</p>
                    </div>
				</div>
				<!-- Website Log Modal -->
				<div class="modal fade" id="websiteLogModal" tabindex="-1" role="dialog" aria-labelledby="websiteLogModalTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="websiteLogModalTitle">Website Log</h5>
							</div>
							<div class="modal-body">
								<?php if ($this->e($this->getSetting('loggingWebsiteLogs')) === "false") { ?>
								    <div class="alert alert-warning" role="alert">This logging service has been disabled. Please enable through the config.</div>
							    <?php } ?>
								<p>The website log keeps a log of the index file loading and is used to catch any errors with modules and code.</p>
								<table class="table table-sm">
									<tbody>
										<tr>
											<th>Value</th>
											<th style="width: 70px;">Version</th>
											<th style="width: 165px;">Timestamp</th>
										</tr>
										<?php foreach ($websiteLogs as $websiteLog) {
                                            echo '<tr>';
                                            echo '<td>'.$websiteLog['Value'].'</td>';
                                            echo '<td>'.$websiteLog['Version'].'</td>';
                                            echo '<td>'.$websiteLog['TimeStamp'].'</td>';
                                            echo '</tr>';
                                        } ?>
									</tbody>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Admin Log Modal -->
				<div class="modal fade" id="adminLogModal" tabindex="-1" role="dialog" aria-labelledby="adminLogModalTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="adminLogModalTitle">Admin Log</h5>
							</div>
							<div class="modal-body">
                                <?php if ($this->e($this->getSetting('loggingAdminLogs')) === "false") { ?>
                                    <div class="alert alert-warning" role="alert">This logging service has been disabled. Please enable through the config.</div>
                                <?php } ?>
								<p>The admin log keeps a log of the admin file loading and is used to catch any errors with modules and code.</p>
								<table class="table table-sm">
									<tbody>
										<tr>
											<th>Value</th>
											<th style="width: 70px;">Version</th>
											<th style="width: 165px;">Timestamp</th>
										</tr>
										<?php foreach ($adminLogs as $adminLog) {
                                            echo '<tr>';
                                            echo '<td>'.$adminLog['Value'].'</td>';
                                            echo '<td>'.$adminLog['Version'].'</td>';
                                            echo '<td>'.$adminLog['TimeStamp'].'</td>';
                                            echo '</tr>';
                                        } ?>
									</tbody>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Transaction Log Modal -->
				<div class="modal fade" id="transactionLogModal" tabindex="-1" role="dialog" aria-labelledby="transactionLogModalTitle" aria-hidden="true">
					<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="transactionLogModalTitle">Transaction Log</h5>
							</div>
							<div class="modal-body">
                                <?php if ($this->e($this->getSetting('loggingTransactionLogs')) === "false") { ?>
								    <div class="alert alert-warning" role="alert">This logging service has been disabled. Please enable through the config.</div>
							    <?php } ?>
								<p>The transaction log keeps a log of everything that happens on the admin console.</p>
								<table class="table table-sm">
									<tbody>
										<tr>
											<th>User</th>
											<th style="">Action</th>
											<th style="">Details</th>
											<th style="width: 170px;">Timestamp</th>
										</tr>
                                        <?php foreach ($transactionLogs as $transactionLog) {
                                            // Get the user
                                            switch ($transactionLog['UserID']) {
                                                case 0:
                                                    $username = 'Website SQL';
                                                    break;
                                                default:
                                                    $User = array_search($transactionLog['UserID'], array_column($users, 'id'));
                                                    if ($User !== false) {
                                                        $username = $users[$User]['firstname'].' '.$users[$User]['lastname'].' (ID #'.$users[$User]['id'].')';
                                                    } else {
                                                        $username = 'Deleted User (ID #'.$transactionLog['UserID'].')';
                                                    }
                                                    break;
                                            }
                                            
                                            // Works out the action made
                                            switch ($transactionLog['Action']) {
                                                case 'database_upgrade':
                                                    $Action = 'Database Upgrade';
                                                    $Details = $transactionLog['Content'];
                                                    break;
                                                case 'login.success':
                                                    $Action = 'Successful Login';
                                                    $Details = $transactionLog['Content'];
                                                    break;
                                                case 'login.failure':
                                                    $Action = 'Failed Login';
                                                    $Details = $transactionLog['Content'];
                                                    break;
                                                case 'successful_logout':
                                                    $Action = 'Logout Attempt';
                                                    $Details = $transactionLog['Content'];
                                                    break;
                                                case 'successful_file_upload':
                                                    $Action = 'File Upload';
                                                    $Details = $transactionLog['Content'];
                                                    break;
                                                case (preg_match('/content_edit_\d+/', $transactionLog['Action']) ? true : false):
                                                    $Action = 'Edit Content';
                                                    $JsonDecode = json_decode($transactionLog['Content'], true);
                                                    $Details = str_replace('content_edit_', '', $transactionLog['Action']).': '.$JsonDecode['PostTitle'].' - <a style="color: #ffffff;padding: 0px 8px;" class="btn btn-primary btn-sm" role="button" href="/admin.phpedit&id='.str_replace('content_edit_', '', $transactionLog['Action']).'">View Content</a>';
                                                    break;
                                                case (preg_match('/module_install_\d+/', $transactionLog['Action']) ? true : false):
                                                    $Action = 'Module Install';
                                                    $Details = $transactionLog['Content'];
                                                    break;
                                                case (preg_match('/module_update_\d+/', $transactionLog['Action']) ? true : false):
                                                    $Action = 'Module Update';
                                                    $Details = $transactionLog['Content'];
                                                    break;
                                                default:
                                                    $Action = 'Unknown';
                                                    $Details = 'We couldn\'t find what this entry contained!';
                                                    break;
                                            }

                                            echo '<tr>';
                                            echo '<td>'.$username.'</td>';
                                            echo '<td>'.$Action.'</td>';
                                            echo '<td>'.$Details.'</td>';
                                            echo '<td>'.$transactionLog['TimeStamp'].'</td>';
                                            echo '</tr>';
                                        } ?>
									</tbody>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Messages Log Modal -->
								<div class="modal fade" id="messagesLogModal" tabindex="-1" role="dialog" aria-labelledby="messagesLogModalTitle" aria-hidden="true">
									<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="messagesLogModalTitle">Messages Log</h5>
											</div>
											<div class="modal-body">
												<p>The messages log keeps a log of everything that happens on the admin console.</p>
												<table class="table table-sm">
													<tbody>
														<tr>
															<th style="width: 160px;">Sender</th>
															<th style="width: 160px;">Recipient</th>
															<th>Message</th>
															<th>Viewed</th>
															<th style="width: 170px;">Timestamp</th>
														</tr>
														<?php
                                                        // $MessagesLogQuery = mysqli_query($DBWebSQL,"SELECT * FROM " . WebsiteSQL_Table_MessagesLog . " ORDER BY TimeStamp DESC");
														// while ($MessagesLogRow = mysqli_fetch_array($MessagesLogQuery, MYSQLI_ASSOC)) {
														// 	// Display Sender's Name
														// 	$SenderID = $MessagesLogRow['SenderID'];
														// 	$SenderQuery = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Users . " WHERE ID = '$SenderID'");
														// 	$SenderRow = mysqli_fetch_array($SenderQuery, MYSQLI_ASSOC);
														// 	if ($SenderID == 0) {$SenderRow = array("FirstName" => "Website", "LastName" => "SQL");}

														// 	// Display Sender's Name
														// 	$RecipientID = $MessagesLogRow['RecipientID'];
														// 	$RecipientQuery = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Users . " WHERE ID = '$RecipientID'");
														// 	$RecipientRow = mysqli_fetch_array($RecipientQuery, MYSQLI_ASSOC);
														// 	if ($RecipientID == 0) {$RecipientRow = array("FirstName" => "Website", "LastName" => "SQL");}

														// 	echo '<tr>';
														// 	echo '<td>'.$SenderRow['FirstName'].' '.$SenderRow['LastName'].'</td>';
														// 	echo '<td>'.$RecipientRow['FirstName'].' '.$RecipientRow['LastName'].'</td>';
														// 	echo '<td>'.$MessagesLogRow['Description'].'</td>';
														// 	echo '<td>'.($MessagesLogRow['Viewed'] == "true" ? 'Yes': 'No').'</td>';
														// 	echo '<td>'.$MessagesLogRow['TimeStamp'].'</td>';
														// 	echo '</tr>';
														// } ?>
													</tbody>
												</table>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
				<!-- Logging Control Modal -->
				<div class="modal fade" id="loggingControlModal" tabindex="-1" role="dialog" aria-labelledby="loggingControlModalTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="loggingControlModalTitle">Logging Control</h5>
							</div>
							<div class="modal-body">
								<div class="alert alert-info" role="alert">This form does not Auto Save, please make sure you click 'Save All' to apply changes.</div>
								<p>You can enable or disable the following logging services, these are used to track user actions and website errors. To disable a service simply uncheck the box, check to enable.</p>
                                <div class="checkbox">
									<label>
                                        <input type="checkbox" name="WebsiteSettings[loggingWebsiteLogs]" value="true" <?php if ($this->e($this->getSetting('loggingWebsiteLogs')) === 'true') {echo 'checked';} ?>>
										Website Logs - Used to track website access and error logs.
									</label>
								</div>
								<div class="checkbox">
									<label>
                                        <input type="checkbox" name="WebsiteSettings[loggingAdminLogs]" value="true" <?php if ($this->e($this->getSetting('loggingAdminLogs')) === 'true') {echo 'checked';} ?>>
										Admin Logs - Used to track admin access and error logs.
									</label>
								</div>
								<div class="checkbox">
									<label>
                                        <input type="checkbox" name="WebsiteSettings[loggingTransactionLogs]" value="true" <?php if ($this->e($this->getSetting('loggingTransactionLogs')) === 'true') {echo 'checked';} ?>>
										Transaction Logs - Used to track admin/system actions and changes.
									</label>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>

                <!-- SentryGuard App -->
				<div class="modal fade" id="sentryGuardApp" tabindex="-1" role="dialog" aria-labelledby="sentryGuardAppTitle" aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="sentryGuardAppTitle"><i class="fa-solid fa-fw fa-person-military-to-person"></i> SentryGuard</h5>
							</div>
							<div class="modal-body">
								<!-- Top Bar (Left: Search Bar, Filter Options Right: Config button) -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Search for..." aria-label="Search for..." aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-secondary">Filter</button>
                                            <button type="button" class="btn btn-secondary">Config</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Main Content (Table) -->
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Channel</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Details</th>
                                            <th scope="col">Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Transactional</td>
                                            <td>Mark</td>
                                            <td>content_edit</td>
                                            <td>content_edit_1 - <a style="color: #ffffff;padding: 0px 8px;" class="btn btn-primary btn-sm" role="button" href="/admin.phpedit&id=1">View Content</a></td>
                                            <td>2021-10-10 12:00:00</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Transactional</td>
                                            <td>Jacob</td>
                                            <td>database_upgrade</td>
                                            <td>Database Upgrade to v2.0</td>
                                            <td>2021-10-10 12:00:00</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>Transactional</td>
                                            <td>Alan Tiller</td>
                                            <td>successful_login</td>
                                            <td>The user successfully logged into thier account from IP address: 8.8.8.8</td>
                                            <td>2021-10-10 12:00:00</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Pagination -->
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                    </ul>
                                </nav>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>