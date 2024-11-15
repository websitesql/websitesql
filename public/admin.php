<?php /* Website SQL - Admin - V2.1.0 - (C) Copyright Alan Tiller 2024. */

// Import autoload
require dirname(__DIR__) . '/vendor/autoload.php';

use WebsiteSQL\WebsiteSQL\App;

// Create a new instance of the App
$app = new App();

// Initialize the application
$app->init();

// Get the page from the request
$page = $_GET['page'] ?? null;

// Handle the request
if ($app->getAuth()->check($app->getAuth()->getSessionToken()) == true) { // User Logged In	
	if ($page === "view" && isset($_GET['type'])) { // View Content
		// Get the content type
        $postType = htmlspecialchars($_GET['type']) ?? 'Pages';

        // Get the content for the type
        $contentData = $app->getDatabase()->select($app->getStrings()->getTableContent(), '*', ['PostType' => $postType], ['PostDate' => 'DESC']);

        // Render the view
        echo $app->getRenderer()->render('application::content/view', [
            'title' => $postType,
            'contentData' => $contentData,
            'postType' => $postType
        ]);
	} elseif (($page === "edit" && isset($_GET['id'])) || ($page === "new" && isset($_GET['type']))) { // Add/Edit Content 
			// if ($explode_slug[0] === "edit") {
			// 	$PostID = $explode_slug[1];
			// 	$EditorQuery = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE ID='$PostID'");
			// 	$EditorRow = mysqli_fetch_array($EditorQuery, MYSQLI_ASSOC);
	
			// 	if (isset($_POST['contentUpdate'])) {
			// 		$PostDate = date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $_POST['PostDate'])));
			// 		$PostParent = string_filter($_POST['PostParent']);
	
			// 		if ($EditorRow['PostType'] === 'Pages') {
			// 			$PostParentQuery = mysqli_query($DBWebSQL,"SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE id='$PostParent'");
			// 			$PostParentRow = mysqli_fetch_array($PostParentQuery, MYSQLI_ASSOC);
			// 			$PostSlug = $PostParentRow['PostSlug'] . string_filter($_POST['PostSlug']) . '/';
			// 		} else {
			// 			$PostType = $EditorRow['PostType'];
			// 			$ParentQuery = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE PostTemplate='$PostType' LIMIT 1");
			// 			$ParentRow = mysqli_fetch_array($ParentQuery, MYSQLI_ASSOC);
			// 			$PostSlug = $ParentRow['PostSlug'] . string_filter($_POST['PostSlug']) . '/';
			// 			$PostParent = $ParentRow['ID'];
			// 		}
					
			// 		$PostCustom = array();
			// 		$PostCustomValue = $_POST['PostCustomValue'];
			// 		foreach($_POST['PostCustomField'] as $uqid => $field) {;
			// 			$value = $PostCustomValue[$uqid];
			// 			$PostCustom[$field] = string_filter($value);
			// 		}
			// 		foreach($_POST['PostCustom'] as $field => $value) {;
			// 			$PostCustom[$field] = string_filter($value);
			// 		}
			// 		$PostCustom = json_encode($PostCustom);
	
			// 		$LogAction = 'content_edit_' . $PostID;
			// 		$LogData = json_encode(array("PostTitle" => $EditorRow['PostTitle'], "PostStatus" => $EditorRow['PostStatus'], "PostProtected" => $EditorRow['PostProtected'], "PostDate" => $EditorRow['PostDate'], "PostTemplate" => $EditorRow['PostTemplate'], "PostParent" => $EditorRow['PostParent'], "PostSlug" => $EditorRow['PostSlug'], "PostImage" => $EditorRow['PostImage'], "PostExcerpt" => $EditorRow['PostExcerpt'], "PostContent" => $EditorRow['PostContent'], "PostCustom" => $EditorRow['PostCustom']));
	
			// 		// Writes the update to the system log
			// 		$update_log = $DBWebSQL->prepare("INSERT INTO " . WebsiteSQL_Table_TransactionLog. " (`UserID`, `Action`, `Content`) VALUES (?, ?, ?)");
			// 		$update_log->bind_param("iss", $UserRow['ID'], $LogAction, $LogData);
						
			// 		if ($update_log->execute()) {
			// 			// Writes the update to the database
			// 			$update_post = $DBWebSQL->prepare("UPDATE " . WebsiteSQL_Table_Content . " SET PostTitle=?, PostStatus=?, PostProtected=?, PostDate=?, PostTemplate=?, PostParent=?, PostSlug=?, PostImage=?, PostExcerpt=?, PostCustom=?, PostContent=? WHERE id=?");
			// 			$update_post->bind_param("sssssssssssi", $_POST['PostTitle'], $_POST['PostStatus'], $_POST['PostProtected'], $PostDate, $_POST['PostTemplate'], $PostParent, $PostSlug, $_POST['PostImage'], $_POST['PostExcerpt'], $PostCustom, $_POST['PostContent'], $PostID);
			// 			if ($update_post->execute()) {
			// 				$error = '<div class="alert alert-success" role="alert"><strong>Success</strong> This entry has been successfully updated.</div>';
			// 			} else {
			// 				$error = '<div class="alert alert-danger" role="alert"><strong>Sorry</strong> An error occurred whilst trying to update this record. Error Code: E2</div>';
			// 			}
			// 		} else {
			// 			$error = '<div class="alert alert-danger" role="alert"><strong>Sorry</strong> An error occurred whilst trying to update this record. Error Code: E1</div>';
			// 		}
			// 	}
	
			// 	$EditorQuery = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE ID='$PostID'");
			// 	$EditorRow = mysqli_fetch_array($EditorQuery, MYSQLI_ASSOC); 
			// } elseif ($explode_slug[0] === "new") {

			// 	if (isset($_POST['contentCreate'])) {
			// 		$PostDate = date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $_POST['PostDate'])));
			// 		$PostParent = string_filter($_POST['PostParent']);
	
			// 		if ($explode_slug[1] === 'Pages') {
			// 			$PostParentQuery = mysqli_query($DBWebSQL,"SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE id='$PostParent'");
			// 			$PostParentRow = mysqli_fetch_array($PostParentQuery, MYSQLI_ASSOC);
			// 			$PostSlug = $PostParentRow['PostSlug'] . slug_generator($_POST['PostTitle']) . '/';
			// 		} else {
			// 			$PostType = $explode_slug[1];
			// 			$ParentQuery = mysqli_query($DBWebSQL,"SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE PostTemplate='$PostType' LIMIT 1");
			// 			$ParentRow = mysqli_fetch_array($ParentQuery, MYSQLI_ASSOC);
			// 			$PostSlug = $ParentRow['PostSlug'] . slug_generator($_POST['PostTitle']) . '/';
			// 			$PostParent = $ParentRow['ID'];
			// 		}

			// 		$PostCustom = array();
			// 		foreach($_POST['PostCustom'] as $field => $value) {
			// 			$PostCustom[$field] = string_filter($value);
			// 		}
			// 		$PostCustom = json_encode($PostCustom);
					
			// 		$create_post = $DBWebSQL->prepare("INSERT INTO " . WebsiteSQL_Table_Content . " (AuthorID, PostTitle, PostStatus, PostProtected, PostDate, PostTemplate, PostParent, PostSlug, PostImage, PostExcerpt, PostCustom, PostContent, PostType) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			// 		$create_post->bind_param("issssssssssss", $UserRow['ID'], $_POST['PostTitle'], $_POST['PostStatus'], $_POST['PostProtected'], $PostDate, $_POST['PostTemplate'], $PostParent, $PostSlug, $_POST['PostImage'], $_POST['PostExcerpt'], $PostCustom, $_POST['PostContent'], $explode_slug[1]);
					
			// 		if ($create_post->execute()) {
			// 			$PostID = $DBWebSQL->insert_id;
			// 			header("location: /" . $app->getStrings()->getAdminFilePath() . "edit/" . $PostID);
			// 		} else {
			// 			$error = '<div class="alert alert-danger" role="alert"><strong>Sorry</strong> An error occurred whilst trying to add this record. Error Code: A1</div>';
			// 		}
			// 	}	
				
			// } 

            // Get the editor data
            $editorData = null;

            // Get the editor data if we are editing
            if ($page === 'edit') {
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                $editorData = $app->getDatabase()->get($app->getStrings()->getTableContent(), '*', ['ID' => $id]);

                // Check if the editor data exists
                if (!$editorData) {
                    throw new Exception('The content you are trying to edit does not exist.');
                }
            }

            // Get media from the database
            $media = $app->getDatabase()->select($app->getStrings()->getTableUploads(), ['ID', 'FileName', 'FileHash', 'FileExtension', 'FileSize', 'FileDescription', 'TimeStamp'], ['ORDER' => ['ID' => 'DESC']]);

            // Render the editor
            echo $app->getRenderer()->render('application::content/editor', [
                'title' => 'Editor',
				'mode' => ($page === 'edit' ? 'edit' : ($page === 'new' ? 'new' : null)),
                'editorData' => $editorData,
                'error' => $error ?? null,
                'postType' => htmlspecialchars($_GET['type']) ?? null,
                'media' => $media
            ]);
	} elseif ($page === "media") { // Media
			echo IncludeTemplate("header"); ?>
			<div class="page wrapper">
				<?php echo IncludeTemplate("menu", "Media");
				if ($explode_slug[1] === "upload") {
					if (isset($_POST['doUpload'])) {
						$file_name = $_FILES['FileData']['name'];
						$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
						$file_size = $_FILES['FileData']['size'];
						$FileDescription = string_filter($_POST['FileDescription']);
						$file_slug = generateRandomString(40);
						$allowed_extensions = array('gif', 'GIF', 'png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG', 'pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'odt', 'ODT', 'rtf', 'RTF', 'txt', 'TXT', 'mp3', 'MP3', 'zip', 'ZIP', '7z', '7Z', 'xlsx', 'XLSX', 'xls', 'XLS');
			
						if (in_array($file_extension, $allowed_extensions)) {
							if($file_size <= 52428800) {
								$MediaQuery = $DBWebSQL->prepare("INSERT INTO " . WebsiteSQL_Table_Uploads . " (AuthorID, FileSize, FileHash, FileName, FileExtension, FileDescription) VALUES (?, ?, ?, ?, ?, ?)");
								$MediaQuery->bind_param("isssss", $UserRow['ID'], $file_size, $file_slug, $file_name, $file_extension, $FileDescription);

								if ($MediaQuery->execute()) {

									mkdir('uploads/' . $file_slug, 0777, true);

									if (move_uploaded_file($_FILES['FileData']['tmp_name'], 'uploads/' . $file_slug . '/' . $_FILES['FileData']['name'])) {
										$messageLog = 'The file (' . $file_name . ') was uploaded successfully.';
										$MediaTransactionLog = $DBWebSQL->prepare("INSERT INTO " . WebsiteSQL_Table_TransactionLog . " (`UserID`, `Action`, `Content`) VALUES (?, 'successful_file_upload', ?)");
										$MediaTransactionLog->bind_param("is", $UserRow['ID'], $messageLog);
										$MediaTransactionLog->execute();

										$error = WebsiteSQL_Message_MediaUpload_Success;
									} else {
										$error = WebsiteSQL_Message_MediaUpload_InsertFailure;
									}
								} else {
									$error = WebsiteSQL_Message_MediaUpload_UploadFailure;
								}
							} else {
								$error = WebsiteSQL_Message_MediaUpload_TooBig;
							}
						} else {
							$error = WebsiteSQL_Message_MediaUpload_ForbiddenFile;
						}
					} ?>
					<form action="/<?php echo $app->getStrings()->getAdminFilePath(); ?>media/upload" method="POST" enctype="multipart/form-data">
						<h3>Upload a file</h3>
						<?php echo $error; ?>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="PostStatus">File</label>
									<input type="file" class="form-control-file" name="FileData">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="PostStatus">Description</label>
									<input type="text" class="form-control" name="FileDescription">
								</div>
							</div>
							<div class="col-md-12">
								<button type="submit" name="doUpload" class="btn btn-success"> Upload </button>
							</div>
						</div>	
					</form>
				</div><?php 
				} else {
					$PostType = $explode_slug[1];
					$UploadQuery = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Uploads . " ORDER BY TimeStamp DESC"); ?>
					<div class="button-board">
						<a href="/<?php echo $app->getStrings()->getAdminFilePath(); ?>media/upload" class="btn btn-success"> Upload </a>
					</div>
					<div class="card-columns">
						<?php if (mysqli_num_rows($UploadQuery) > 0) {
							while ($UploadRow = mysqli_fetch_array($UploadQuery, MYSQLI_ASSOC)) {
								echo '<div class="card">';
								if (in_array($UploadRow['FileExtension'], array('gif', 'GIF', 'png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG'))) {
									echo '<div style="max-height: 200px;overflow: hidden;"><img class="card-img-top" src="' . $WebsiteSettings['WebsiteRoot'] . 'uploads/' . $UploadRow['FileHash'] . '/' . $UploadRow['FileName'] . '" alt=""></div>';
								}
								echo '<div class="card-body"><h5>' . $UploadRow['FileDescription'] . '</h5><p class="card-text"><small class="text-muted">Last updated ' . timeago($UploadRow['TimeStamp']) . '</small></p><a href="#" class="card-link">Edit</a><a href="#" class="card-link">Delete</a><a target="_blank" href="' . $WebsiteSettings['WebsiteRoot'] . 'uploads/' . $UploadRow['FileHash'] . '/' . $UploadRow['FileName'] . '" class="card-link float-right">Open</a></div>';
								echo '</div>';
							}
						} else {
							echo '<tr><td colspan="5">No content could be found.</td></tr>';
						} ?>
					</div>
				</div><?php 
			}
			echo IncludeTemplate("footer");   
	} elseif ($page === "users") { // Users
		// Get the action
        $action = isset($_GET['action']) ? $_GET['action'] : null;

        // The users page can have multiple pages so we need to check the action
        switch ($action) {
            case 'edit':
                // Get the user ID
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

                // Get the user data
                $user = $app->getDatabase()->get($app->getStrings()->getTableUsers(), '*', ['ID' => $id]);

                // Check if the user exists
                if (!$user) {
                    throw new Exception('The user you are trying to edit does not exist.');
                }

                // Process the update
                if (isset($_POST['doUpdateUser'])) {
                    // Check the CRSF token

                }

                // Render the edit
                echo $app->getRenderer()->render('application::users/edit', [
                    'title' => 'Edit User',
                    'user' => $user
                ]);
                break;

            case 'add':
                // Process the add
                if (isset($_POST['doAddUser'])) {
                    // Check the CRSF token

                }

                // Render the add
                echo $app->getRenderer()->render('application::users/add', [
                    'title' => 'Add User'
                ]);
                break;

            case 'delete':
                // Get the user ID
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

                // Get the user data
                $user = $app->getDatabase()->get($app->getStrings()->getTableUsers(), '*', ['ID' => $id]);

                // Check if the user exists
                if (!$user) {
                    throw new Exception('The user you are trying to delete does not exist.');
                }

                // Process the delete
                if (isset($_POST['doDeleteUser'])) {
                    // Check the CRSF token
                }
                break;

            default:
                // Get all users
                $users = $app->getDatabase()->select($app->getStrings()->getTableUsers(), '*', ['ORDER' => ['ID' => 'ASC']]);

                // Render the users
                echo $app->getRenderer()->render('application::users/index', [
                    'title' => 'Users',
                    'users' => $users
                ]);
                break;
        }
	} elseif ($page === "settings") { // Settings
		// Save the settings
        if (isset($_POST['doSaveSettings'])) {
            // Get the new settings 
            $newSettings = $_POST['WebsiteSettings'];

            // Deal with checkboxes
            $newSettings['loggingWebsiteLogs'] = isset($newSettings['loggingWebsiteLogs']) ? 'true' : 'false';
            $newSettings['loggingAdminLogs'] = isset($newSettings['loggingAdminLogs']) ? 'true' : 'false';
            $newSettings['loggingTransactionLogs'] = isset($newSettings['loggingTransactionLogs']) ? 'true' : 'false';

            // Loop through the settings,  check if they have been changed and update the database
            foreach($newSettings as $field => $value) {
                if ($value != $app->getSetting($field)) {
                    $app->getDatabase()->update($app->getStrings()->getTableSettings(), ['Value' => $value, 'TimeStamp' => $app->getUtilities()->getDateTime()], ['ID' => $field]);
                }
            }

            // Reload the settings table
            $app->reloadSettings();
        }

        // Load the different log types from the database tables
        $websiteLogs = $app->getDatabase()->select($app->getStrings()->getTableWebsiteLog(), ['ID', 'Value', 'Version', 'TimeStamp'], ['ORDER' => ['ID' => 'DESC']]);
        $transactionLogs = $app->getDatabase()->select($app->getStrings()->getTableTransactionLog(), ['ID', 'UserID', 'Action', 'Content', 'TimeStamp'], ['ORDER' => ['ID' => 'DESC']]);
        $adminLogs = $app->getDatabase()->select($app->getStrings()->getTableAdminLog(), ['ID', 'Value', 'Version', 'TimeStamp'], ['ORDER' => ['ID' => 'DESC']]);

        // Get all users
        $users = $app->getDatabase()->select($app->getStrings()->getTableUsers(), '*', ['ORDER' => ['ID' => 'ASC']]);

		// Render the settings
        echo $app->getRenderer()->render('application::settings', [
            'title' => 'Settings',
            'websiteLogs' => $websiteLogs,
            'transactionLogs' => $transactionLogs,
            'adminLogs' => $adminLogs,
            'users' => $users
        ]);	
	} elseif ($page === "logout") { // Logout
		// Destroy the session
        $app->getAuth()->destroy($app->getAuth()->getSessionToken());

        // Unset the session
        $app->getAuth()->setSessionToken(null);
                
        // Redirect to the login page
		header('location: /' . $app->getStrings()->getAdminFilePath());
	} elseif ($page === "messages") { // Messages
			$UserID = $UserRow['ID'];
			if ($_GET['action'] === "unread") { 
				$unread_messages_query = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_MessagesLog . " WHERE UserID = '$UserID' AND Viewed = 'false'");
				if (mysqli_num_rows($unread_messages_query) > 0) {
					$unread_messages_row = mysqli_fetch_array($unread_messages_query, MYSQLI_ASSOC);
					echo IncludeTemplate("header");
					echo '<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="false">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="messageModalLabel">' . $unread_messages_row['Title'] . '</h5>
								</div>
								<div class="modal-body">
									' . $unread_messages_row['Description'] . '
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" onclick="window.location = \'' . $app->getStrings()->getAdminFilePath() . 'messages&action=unread\'">Dismiss Message</button>
								</div>
							</div>
						</div>
					</div>
					<script type="text/javascript">$(window).on(\'load\',function(){$(\'#messageModal\').modal(\'show\');});</script>';
					$MsgID = $unread_messages_row['ID'];
					mysqli_query($DBWebSQL, "UPDATE " . WebsiteSQL_Table_MessagesLog . " SET Viewed = 'true' WHERE ID = '$MsgID'");
				} else {
					header('location: /' . $app->getStrings()->getAdminFilePath() . 'dashboard');
				}
			} else {
				echo IncludeTemplate("header");
				header('location: /' . $app->getStrings()->getAdminFilePath() . 'dashboard');				
			}
	} elseif ($page === "api") { // JSON API
			if ($_GET['action'] === "users") { // User
				if ($_GET['sub_action'] === "user_view") { 
					$ID = $_GET['id'];
					$query = mysqli_query($DBWebSQL, "SELECT ID, FirstName, LastName, Email, Approved, Locked, Verified, TimeStamp  FROM " . WebsiteSQL_Table_Users . " WHERE ID = '$ID'");
					if (mysqli_num_rows($query) == 1) {
						header("Content-Type: application/json;charset=utf-8");
						echo json_encode(mysqli_fetch_array($query, MYSQLI_ASSOC));
					} else {
						header("Content-Type: application/json;charset=utf-8");
						echo json_encode(array("code" => "user_not_found", "message" => "Sorry, the user could not be found."));
					}
				} elseif ($_GET['sub_action'] === "user_save") { 
	
				} elseif ($_GET['sub_action'] === "user_add") { 

				} elseif ($_GET['sub_action'] === "user_password_reset") { 
	
				}
			} elseif ($_GET['action'] === "") { 

			}
    } elseif ($page === "custom") { // Custom pages
        try {
            // Get the PID from the URL
            $pid = htmlspecialchars($_GET['pid']);

            // Get the page from the application
            $page = $app->getModules()->getModuleCustomPageCallback($pid);

            // Run the callback function
            $content = $page['callback']();

            // Render the page
            echo $app->getRenderer()->render('application::custom', [
                'title' => $page['name'],
                'content' => $content
            ]);
        } catch (\Throwable $th) {
            error_log($th, 0);
            // Redirect to the dashboard
            header('location: /' . $app->getStrings()->getAdminFilePath() . '?page=dashboard');
        }
    } elseif ($page === "account") { // Account
        // Check if the action is set
        $action = isset($_GET['action']) ? $_GET['action'] : null;

        // The account page can have multiple pages so we need to check the action
        switch ($action) {
            case 'setup_mfa':
                // Process the setup MFA
                if (isset($_POST['doSetupMFA'])) {
                    // Check the CRSF token

                }

                // Generate the secret
                

                // Render the setup MFA page
                echo $app->getRenderer()->render('application::account/setup_mfa', [
                    'title' => 'Setup MFA'
                ]);
                break;

            default:
                // Process the account update
                if (isset($_POST['doUpdateAccount'])) {
                    // Check the CRSF token

                }

                // Render the account page
                echo $app->getRenderer()->render('application::account/index', [
                    'title' => 'Account'
                ]);
                break;
        }
	} else { // Redirect
		header('location: /' . $app->getStrings()->getAdminFilePath() . '?page=dashboard');
	}
}