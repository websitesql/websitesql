<?php /* WebSQL - Groups Module - V1.0.1 - (C) Copyright Alan Tiller 2020. */

// Register Module Details
function groups_register_details() {
	return array("ModuleName" => "Groups Module", "ModuleDescription" => "Adds individual group pages and group search functions.", "ModuleVersion" => "1.0.0", "ModuleAuthor" => "Alan Tiller");
}
register_module_details("groups_register_details");


// Register Custom Fields for Editor
function groups_custom_editor_fields($CustomValues) {
	return '<h3>Group Settings</h3>
	<div>
		<div class="row">
			<div class="col-md-6"><div class="form-group"><label for="GroupWebsiteURL">Group Website URL</label><input type="text" class="form-control" id="GroupWebsiteURL" name="PostCustom[group_url]" value="' . $CustomValues['group_url'] . '"></div></div>
			<div class="col-md-6"><div class="form-group"><label for="GroupWebsiteURL">Group Contact Email</label><input type="text" class="form-control" id="GroupWebsiteURL" name="PostCustom[group_contact]" value="' . $CustomValues['group_contact'] . '"></div></div>
		</div>
		<div class="row">
			<div class="col-md-4"><div class="form-group"><label for="group_postcode">Group Postcode</label><input type="text" class="form-control" id="group_postcode" name="PostCustom[group_postcode]" value="' . $CustomValues['group_postcode'] . '" required><div class="invalid-feedback" id="group_postcode_feedback"></div></div></div>
			<div class="col-md-4"><div class="form-group"><label for="group_longitude">Group Longitude</label><input type="text" class="form-control" id="group_longitude" name="PostCustom[group_longitude]" value="' . $CustomValues['group_longitude'] . '" readonly required></div></div>
			<div class="col-md-4"><div class="form-group"><label for="group_latitude">Group Latitude</label><input type="text" class="form-control" id="group_latitude" name="PostCustom[group_latitude]" value="' . $CustomValues['group_latitude'] . '" readonly required></div></div>
		</div>
		<div class="row">
			<div class="col-md-6"><div class="form-group"><label for="beaver_meeting_times">Beaver Meeting Times</label><input type="text" class="form-control" id="beaver_meeting_times" name="PostCustom[beaver_meeting_times]" value="' . $CustomValues['beaver_meeting_times'] . '"></div></div>
			<div class="col-md-6"><div class="form-group"><label for="cub_meeting_times">Cub Meeting Times</label><input type="text" class="form-control" id="cub_meeting_times" name="PostCustom[cub_meeting_times]" value="' . $CustomValues['cub_meeting_times'] . '"></div></div>
		</div>
		<div class="row">
			<div class="col-md-6"><div class="form-group"><label for="scout_meeting_times">Scout Meeting Times</label><input type="text" class="form-control" id="scout_meeting_times" name="PostCustom[scout_meeting_times]" value="' . $CustomValues['scout_meeting_times'] . '"></div></div>
			<div class="col-md-6"><div class="form-group"><label for="explorer_meeting_times">Explorer Meeting Times</label><input type="text" class="form-control" id="explorer_meeting_times" name="PostCustom[explorer_meeting_times]" value="' . $CustomValues['explorer_meeting_times'] . '"></div></div>
		</div>
		<script>
			// Group Settings Auto Long and Lat
			$( "#group_postcode" ).keyup(function() {
				jQuery.ajax({
					url: "https://api.postcodes.io/postcodes/" + $( "#group_postcode" ).val(),
					data: "",
					type: "GET",
					success:function(data) {$( "#group_postcode_feedback" ).html( "" );$( "#group_postcode" ).removeClass( "is-invalid" );$( "#group_longitude" ).val( data.result.longitude );$( "#group_latitude" ).val( data.result.latitude );},
					error: function() {$( "#group_postcode_feedback" ).html( "Please enter a vaild postcode." );$( "#group_postcode" ).addClass( "is-invalid" );$( "#group_longitude" ).val( "" );$( "#group_latitude" ).val( "" );}
				});
			});
		</script>
	</div>';
}
register_custom_editor_fields("Groups", "groups_custom_editor_fields");


// Register a Dashboard Tile
function groups_custom_dashboard_tiles() {
	global $DBWebSQL, $Web_Config, $TableContent;

	// Content Count Published
	$result = mysqli_query($DBWebSQL, "SELECT COUNT(*) AS `Rows` FROM " . WebsiteSQL_Table_Content . " WHERE `PostStatus` = 'publish' AND `PostType` = 'Groups'");
	$EventsPublished = mysqli_fetch_array($result, MYSQLI_ASSOC)['Rows'] ?? 0;

	// Content Count Published
	$result = mysqli_query($DBWebSQL, "SELECT COUNT(*) AS `Rows` FROM " . WebsiteSQL_Table_Content . " WHERE `PostStatus` = 'draft' AND `PostType` = 'Groups'");
	$EventsDraft = mysqli_fetch_array($result, MYSQLI_ASSOC)['Rows'] ?? 0;
	
	$groups_tile = '<div class="col-sm-6 col-lg-4">';
	$groups_tile .= '<a class="tile" style="background-color: #607d8b;" href="/' . WebsiteSQL_Admin_FilePath . 'view/Groups">';
	$groups_tile .= '<i class="fal fa-fw fa-users-class" aria-hidden="true"></i><h1>Groups</h1>';
	$groups_tile .= '<p>' . $EventsPublished . ' published groups</p>';
	$groups_tile .= '<p>' . $EventsDraft . ' draft groups</p>';
	$groups_tile .= '</a>';
	$groups_tile .= '</div>';

	return $groups_tile;
}
register_custom_dashboard_tiles("groups_custom_dashboard_tiles");


// Register a Menu Item
register_custom_menu_items("Groups", "/" . WebsiteSQL_Admin_FilePath . "view/Groups");


if (mysqli_query($DBWebSQL, "DESCRIBE `WebSQL_GroupsSettings`") == "") {
    groups_install();
}

function groups_install() {
	global $DBWebSQL, $time;

	WriteToLogFile('basic', 'Function Module Install Start: Groups');
	WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.groups, function call, modules/groups.php, install');

	$groups_table = "CREATE TABLE IF NOT EXISTS `WebSQL_GroupsSettings` (
		`ID` varchar(225) NOT NULL,
		`Value` text NOT NULL,
		`TimeStamp` varchar(40) NOT NULL,
		`AutoLoad` int(11) NOT NULL DEFAULT '1',
		PRIMARY KEY  (ID)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8";

	$groups_table_result = mysqli_query($DBWebSQL, $groups_table);

	if ($groups_table_result) {
		WriteToLogFile('basic', 'Function Module SQL Query Success: Groups');
		WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.groups, DBCall, modules/groups.php, CREATE TABLE, success');
	} else {
		WriteToLogFile('basic', 'Function Module SQL Query Failed: Groups');
		WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.groups, DBCall, modules/groups.php, CREATE TABLE, Failed');
		WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.groups, DBCall, modules/groups.php, CREATE TABLE, ' . mysqli_error($DBWebSQL));
		exit();	
	}

	$groups_table_fields = array("SQLTable" => "WebSQL_WebsiteContent",
	"SQLArguments" => "PostType='Groups' AND PostStatus='publish'",
	"NameField" => "PostTitle",
	"URLField" => "PostSlug",
	"LongitudeField" => "PostCustom => group_longitude",
	"LatitudeField" => "PostCustom => group_latitude",
	"BeaverMeetingTimesField" => "PostCustom => beaver_meeting_times",
	"CubMeetingTimesField" => "PostCustom => cub_meeting_times",
	"ScoutMeetingTimesField" => "PostCustom => scout_meeting_times",
	"ExplorerMeetingTimesField" => "PostCustom => explorer_meeting_times");
	
	foreach($groups_table_fields as $key => $val) {
		$field_entry_result = mysqli_query($DBWebSQL, 'INSERT INTO WebSQL_GroupsSettings (ID, Value, TimeStamp) VALUES ("' . $key . '", "' . $val . '", "' . $time . '")');

		if ($field_entry_result) {
			WriteToLogFile('basic', 'Function Module SQL Query Success: Groups');
			WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.groups, DBCall, modules/groups.php, INSERT INTO, success, ' . $key);
		} else {
			WriteToLogFile('basic', 'Function Module SQL Query Failed: Groups');
			WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.groups, DBCall, modules/groups.php, INSERT INTO, Failed, ' . $key);
			WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.groups, DBCall, modules/groups.php, INSERT INTO, ' . mysqli_error($DBWebSQL));
			exit();	
		}
	}

	WriteToLogFile('basic', 'Function Module Install Success: Groups');
	WriteToLogFile('enhanced', 'MDLAUTOLOAD: mdl.groups, function success, modules/groups.php, install');
}

function groups_shortcode_template($page_string) {
	global $DBWebSQL, $WebsiteSettings;

	$SettingsQuery = mysqli_query($DBWebSQL,"SELECT * FROM WebSQL_GroupsSettings WHERE AutoLoad=1");
	$WebSQL_GroupsSettings = array();
	while ($SettingsRow = mysqli_fetch_array($SettingsQuery, MYSQLI_ASSOC)) {
		$SName = $SettingsRow['ID'];
		$WebSQL_GroupsSettings[$SName] = $SettingsRow['Value'];
	}


	$groups_array = array(); // Starts the array ready for output

	$tablename = $WebSQL_GroupsSettings['SQLTable'];
	if ($WebSQL_GroupsSettings['SQLArguments'] != '') {
		$tablearguments = $WebSQL_GroupsSettings['SQLArguments'];
		$sql_query = mysqli_query($DBWebSQL, "SELECT * FROM `$tablename` WHERE $tablearguments");
	} else {
		$sql_query = mysqli_query($DBWebSQL, "SELECT * FROM `$tablename`");
	}

	while ($sql_row = mysqli_fetch_array($sql_query, MYSQLI_ASSOC)) {
		// Name Field
			if (strpos($WebSQL_GroupsSettings['NameField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_GroupsSettings['NameField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$name = $nested[$values[1]];
			} else { // If not an nested array
				$name = $sql_row[$WebSQL_GroupsSettings['NameField']];
			}
		// URL Field
			if (strpos($WebSQL_GroupsSettings['URLField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_GroupsSettings['URLField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$url = $nested[$values[1]];
			} else { // If not an nested array
				$url = $sql_row[$WebSQL_GroupsSettings['URLField']];
			}
		// Longitude Field
			if (strpos($WebSQL_GroupsSettings['LongitudeField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_GroupsSettings['LongitudeField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$longitude = $nested[$values[1]];
			} else { // If not an nested array
				$longitude = $sql_row[$WebSQL_GroupsSettings['LongitudeField']];
			}
		// Latitude Field
			if (strpos($WebSQL_GroupsSettings['LatitudeField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_GroupsSettings['LatitudeField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$latitude = $nested[$values[1]];
			} else { // If not an nested array
				$latitude = $sql_row[$WebSQL_GroupsSettings['LatitudeField']];
			}
		// Beaver Meeting Times Field
			if (strpos($WebSQL_GroupsSettings['BeaverMeetingTimesField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_GroupsSettings['BeaverMeetingTimesField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$beaver_meeting_times = $nested[$values[1]];
			} else { // If not an nested array
				$beaver_meeting_times = $sql_row[$WebSQL_GroupsSettings['BeaverMeetingTimesField']];
			}
		// Cub Meeting Times Field
			if (strpos($WebSQL_GroupsSettings['CubMeetingTimesField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_GroupsSettings['CubMeetingTimesField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$cub_meeting_times = $nested[$values[1]];
			} else { // If not an nested array
				$cub_meeting_times = $sql_row[$WebSQL_GroupsSettings['CubMeetingTimesField']];
			}
		// Scout Meeting Times Field
			if (strpos($WebSQL_GroupsSettings['ScoutMeetingTimesField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_GroupsSettings['ScoutMeetingTimesField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$scout_meeting_times = $nested[$values[1]];
			} else { // If not an nested array
				$scout_meeting_times = $sql_row[$WebSQL_GroupsSettings['ScoutMeetingTimesField']];
			}
		// Expolrer Meeting Times Field
			if (strpos($WebSQL_GroupsSettings['ExplorerMeetingTimesField'], ' => ')) { // Detects if it is a nested array value
				$values = explode(" => ", $WebSQL_GroupsSettings['ExplorerMeetingTimesField']);
				$nested = json_decode($sql_row[$values[0]], true);
				$explorer_meeting_times = $nested[$values[1]];
			} else { // If not an nested array
				$explorer_meeting_times = $sql_row[$WebSQL_GroupsSettings['ExplorerMeetingTimesField']];
			}
		array_push($groups_array, array("name" => $name, "url" => $url, "longitude" => $longitude, "latitude" => $latitude, "beaver_meeting_times" => $beaver_meeting_times, "cub_meeting_times" => $cub_meeting_times, "scout_meeting_times" => $scout_meeting_times, "explorer_meeting_times" => $explorer_meeting_times));		
	}

	// Creates the People and stores in the include template
	for ($i = 0; $i < count($groups_array); $i++)  {
		$group_array = $groups_array[$i];

		if ($group_array['beaver_meeting_times']) {
			$sections .= 'Beavers, ';
		}
		if ($group_array['cub_meeting_times']) {
			$sections .= 'Cubs, ';
		}
		if ($group_array['scout_meeting_times']) {
			$sections .= 'Scouts, ';
		}
		if ($group_array['explorer_meeting_times']) {
			$sections .= 'Explorers, ';
		}
		$group_data .= '{"type": "Feature","geometry": {"type": "Point", "coordinates": [' . $group_array['longitude'] . ', ' . $group_array['latitude'] . ']},"properties": {"title": "' . $group_array['name'] . '", "url": "' . $WebsiteSettings['WebsiteRoot'] . $group_array['url'] . '", "sections": "' . substr($sections, 0, -2) . '"}},';
		$sections = '';

	}
	return str_ireplace('{{group_data}}', $group_data, $page_string);
} ?>