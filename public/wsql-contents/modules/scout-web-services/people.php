<?php /* WebSQL - People Module - V1.0.1 - (C) Copyright Alan Tiller 2020. */

// Register Module Details
function people_register_details() {
	return array("ModuleName" => "People Module", "ModuleDescription" => "Meet the team style page with details on members.", "ModuleVersion" => "1.0.0", "ModuleAuthor" => "Alan Tiller");
}
register_module_details("people_register_details");


// Register Custom Fields for Editor
function people_custom_editor_fields($CustomValues) {
	return '<h3>Person Settings</h3>
	<div>
		<div class="row">
			<div class="col-md-4"><div class="form-group"><label for="people_position">Person Position</label><input type="text" class="form-control" id="people_position" name="PostCustom[people_position]" value="' . $CustomValues['people_position'] . '" required><div class="invalid-feedback" id="group_postcode_feedback"></div></div></div>
			<div class="col-md-4"><div class="form-group"><label for="people_email_text">Email Text</label><input type="text" class="form-control" id="people_email_text" name="PostCustom[people_email_text]" value="' . $CustomValues['people_email_text'] . '" readonly required></div></div>
			<div class="col-md-4"><div class="form-group"><label for="people_email_address">Email Address</label><input type="text" class="form-control" id="people_email_address" name="PostCustom[people_email_address]" value="' . $CustomValues['people_email_address'] . '" readonly required></div></div>
		</div>
	</div>';
}
register_custom_editor_fields("People", "people_custom_editor_fields");


// Register a Dashboard Tile
function people_custom_dashboard_tiles() {
	global $DBWebSQL, $Web_Config, $TableContent;

	// Content Count Published
	$result = mysqli_query($DBWebSQL, "SELECT COUNT(*) AS `Rows` FROM " . WebsiteSQL_Table_Content . " WHERE `PostStatus` = 'publish' AND `PostType` = 'People'");
	$PeoplePublished = mysqli_fetch_array($result, MYSQLI_ASSOC)['Rows'] ?? 0;

	// Content Count Published
	$result = mysqli_query($DBWebSQL, "SELECT COUNT(*) AS `Rows` FROM " . WebsiteSQL_Table_Content . " WHERE `PostStatus` = 'draft' AND `PostType` = 'People'");
	$PeopleDraft = mysqli_fetch_array($result, MYSQLI_ASSOC)['Rows'] ?? 0;
	
	$people_tile = '<div class="col-sm-6 col-lg-4">';
	$people_tile .= '<a class="tile" style="background-color: #ff5722;" href="/' . WebsiteSQL_Admin_FilePath . 'view/People">';
	$people_tile .= '<i class="fal fa-fw fa-users"></i><h1>People</h1>';
	$people_tile .= '<p>' . $PeoplePublished . ' published people</p>';
	$people_tile .= '<p>' . $PeopleDraft . ' draft people</p>';
	$people_tile .= '</a>';
	$people_tile .= '</div>';

	return $people_tile;
}
register_custom_dashboard_tiles("people_custom_dashboard_tiles");


// Register a Menu Item
register_custom_menu_items("People", "/" . WebsiteSQL_Admin_FilePath . "view/People");



function people_admin_details() {
	return array("ModuleName" => "People Module",
	"ModuleVersion" => "1.0.0",
	"ModuleAuthor" => "Alan Tiller");
}

if (mysqli_query($DBWebSQL, "DESCRIBE `WebSQL_PeopleSettings`") == "") {
    people_install();
}

function people_install() {
	global $DBWebSQL, $time;

	WriteToLogFile('basic', 'Function Module Install Start: People');
	WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.people, function call, modules/people.php, install');

	$people_table = "CREATE TABLE IF NOT EXISTS `WebSQL_PeopleSettings` (
		`ID` varchar(225) NOT NULL,
		`Value` text NOT NULL,
		`TimeStamp` varchar(40) NOT NULL,
		`AutoLoad` int(11) NOT NULL DEFAULT '1',
		PRIMARY KEY  (ID)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8";

	$people_table_result = mysqli_query($DBWebSQL, $people_table);

	if ($people_table_result) {
		WriteToLogFile('basic', 'Function Module SQL Query Success: People');
		WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.people, DBCall, modules/people.php, CREATE TABLE, success');
	} else {
		WriteToLogFile('basic', 'Function Module SQL Query Failed: People');
		WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.people, DBCall, modules/people.php, CREATE TABLE, Failed');
		WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.people, DBCall, modules/people.php, CREATE TABLE, ' . mysqli_error($DBWebSQL));
		exit();	
	}

	$people_table_fields = array("SQLTable" => "WebSQL_PeopleSettings",
	"SQLArguments" => "PostType='People' AND PostStatus='publish'",
	"NameField" => "PostTitle",
	"ImageField" => "PostImage",
	"PositionField" => "PostCustom => people_position",
	"DescriptionField" => "PostContent",
	"ButtonTextField" => "PostCustom => people_email_text",
	"ButtonLinkField" => "PostCustom => people_email_address");
	
	foreach($people_table_fields as $key => $val) {
		$field_entry_result = mysqli_query($DBWebSQL, 'INSERT INTO WebSQL_PeopleSettings (ID, Value, TimeStamp) VALUES ("' . $key . '", "' . $val . '", "' . $time . '")');

		if ($field_entry_result) {
			WriteToLogFile('basic', 'Function Module SQL Query Success: People');
			WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.people, DBCall, modules/people.php, INSERT INTO, success, ' . $key);
		} else {
			WriteToLogFile('basic', 'Function Module SQL Query Failed: People');
			WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.people, DBCall, modules/people.php, INSERT INTO, Failed, ' . $key);
			WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.people, DBCall, modules/people.php, INSERT INTO, ' . mysqli_error($DBWebSQL));
			exit();	
		}
	}

	WriteToLogFile('basic', 'Function Module Install Success: People');
	WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.people, function success, modules/people.php, install');
}

function people_shortcode_template($page_string) {
	global $WebSQL_PeopleSettings, $DBWebSQL;

	$SettingsQuery = mysqli_query($DBWebSQL,"SELECT * FROM WebSQL_PeopleSettings WHERE AutoLoad=1");
	$WebSQL_PeopleSettings = array();
	while ($SettingsRow = mysqli_fetch_array($SettingsQuery, MYSQLI_ASSOC)) {
		$SName = $SettingsRow['ID'];
		$WebSQL_PeopleSettings[$SName] = $SettingsRow['Value'];
	}

	$peoples_array = array(); // Starts the array ready for output

	$tablename = $WebSQL_PeopleSettings['SQLTable'];
	if ($WebSQL_PeopleSettings['SQLArguments'] != '') {
		$tablearguments = $WebSQL_PeopleSettings['SQLArguments'];
		$sql_query = mysqli_query($DBWebSQL, "SELECT * FROM `$tablename` WHERE $tablearguments");
	} else {
		$sql_query = mysqli_query($DBWebSQL, "SELECT * FROM `$tablename`");
	}
	while ($sql_row = mysqli_fetch_array($sql_query, MYSQLI_ASSOC)) {
			// Name Field
			if (strpos($WebSQL_PeopleSettings['NameField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_PeopleSettings['NameField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$name = $nested[$values[1]];
			} else { // If not an nested array
				$name = $sql_row[$WebSQL_PeopleSettings['NameField']];
			}
			// Image Field
			if (strpos($WebSQL_PeopleSettings['ImageField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_PeopleSettings['ImageField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$image = $nested[$values[1]];
			} else { // If not an nested array
				$image = $sql_row[$WebSQL_PeopleSettings['ImageField']];
			}
			// Position Field
			if (strpos($WebSQL_PeopleSettings['PositionField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_PeopleSettings['PositionField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$position = $nested[$values[1]];
			} else { // If not an nested array
				$position = $sql_row[$WebSQL_PeopleSettings['PositionField']];
			}
			// Description Field
			if (strpos($WebSQL_PeopleSettings['DescriptionField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_PeopleSettings['DescriptionField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$description = $nested[$values[1]];
			} else { // If not an nested array
				$description = $sql_row[$WebSQL_PeopleSettings['DescriptionField']];
			}
			// Button Text Field
			if (strpos($WebSQL_PeopleSettings['ButtonTextField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_PeopleSettings['ButtonTextField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$button_text = $nested[$values[1]];
			} else { // If not an nested array
				$button_text = $sql_row[$WebSQL_PeopleSettings['ButtonTextField']];
			}
			// Button Link Field
			if (strpos($WebSQL_PeopleSettings['ButtonLinkField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_PeopleSettings['ButtonLinkField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$button_link = $nested[$values[1]];
			} else { // If not an nested array
				$button_link = $sql_row[$WebSQL_PeopleSettings['ButtonLinkField']];
			}
		array_push($peoples_array, array("name" => $name, "image" => $image, "position" => $position, "description" => $description, "button_text" => $button_text, "button_link" => $button_link));		
	}

	// Creates the People and stores in the include template
	for ($i = 0; $i < count($peoples_array); $i++)  {
		$people_array = $peoples_array[$i];

		$team_members .= '<div class="col-lg-6">
			<div class="team-member">
				<img src="' . $people_array['image'] . '" alt="' . $people_array['name'] . '" class="background">
				<div class="text">
					<h1>' . $people_array['position'] . ' - ' . $people_array['name'] . '</h1>
					<p>' . $people_array['description'] . '</p>
					<a href="' . $people_array['button_link'] . '" class="button black">' . $people_array['button_text'] . '</a>
				</div>
			</div>
		</div>';
	}
	return str_ireplace('{{team_members}}', $team_members, $page_string);
}


 ?>