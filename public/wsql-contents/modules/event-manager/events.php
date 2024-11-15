<?php /* WebSQL - Event Module - V1.0.0 - (C) Copyright Alan Tiller 2020. */

// Register Module Details
function events_register_details() {
	return array("ModuleName" => "Events Module", "ModuleDescription" => "Provides the Events Manager part of the website.", "ModuleVersion" => "1.0.0", "ModuleAuthor" => "Alan Tiller");
}
register_module_details("events_register_details");


// Register Custom Fields for Editor
function events_custom_editor_fields($CustomValues) {
	return '<h3>Event Settings</h3>
	<div>
		<div class="row">
			<div class="col-md-4"><div class="form-group"><label for="event_location">Event Location</label><input type="text" class="form-control" id="event_location" name="PostCustom[event_location]" value="' . $CustomValues['event_location'] . '" required><div class="invalid-feedback" id="group_postcode_feedback"></div></div></div>
			<div class="col-md-4"><div class="form-group"><label for="event_website">Event Website</label><input type="text" class="form-control" id="event_website" name="PostCustom[event_website]" value="' . $CustomValues['event_website'] . '" readonly required></div></div>
			<div class="col-md-4"><div class="form-group"><label for="event_contact_email">Event Contact Email</label><input type="text" class="form-control" id="event_contact_email" name="PostCustom[event_contact_email]" value="' . $CustomValues['event_contact_email'] . '" readonly required></div></div>
		</div>
		<div class="row">
			<div class="col-md-6"><div class="form-group"><label for="event_resource_text_1">Event Resource Text 1</label><input type="text" class="form-control" id="event_resource_text_1" name="PostCustom[event_resource_text_1]" value="' . $CustomValues['event_resource_text_1'] . '"></div></div>
			<div class="col-md-6"><div class="form-group"><label for="event_resource_link_1">Event Resource Link 1</label><input type="text" class="form-control" id="event_resource_link_1" name="PostCustom[event_resource_link_1]" value="' . $CustomValues['event_resource_link_1'] . '"></div></div>
		</div>
		<div class="row">
			<div class="col-md-6"><div class="form-group"><label for="event_resource_text_2">Event Resource Text 2</label><input type="text" class="form-control" id="event_resource_text_2" name="PostCustom[event_resource_text_2]" value="' . $CustomValues['event_resource_text_2'] . '"></div></div>
			<div class="col-md-6"><div class="form-group"><label for="event_resource_link_2">Event Resource Link 2</label><input type="text" class="form-control" id="event_resource_link_2" name="PostCustom[event_resource_link_2]" value="' . $CustomValues['event_resource_link_2'] . '"></div></div>
		</div>
		<div class="row">
			<div class="col-md-6"><div class="form-group"><label for="event_resource_text_3">Event Resource Text 3</label><input type="text" class="form-control" id="event_resource_text_3" name="PostCustom[event_resource_text_3]" value="' . $CustomValues['event_resource_text_3'] . '"></div></div>
			<div class="col-md-6"><div class="form-group"><label for="event_resource_link_3">Event Resource Link 3</label><input type="text" class="form-control" id="event_resource_link_3" name="PostCustom[event_resource_link_3]" value="' . $CustomValues['event_resource_link_3'] . '"></div></div>
		</div>
	</div>';
}
register_custom_editor_fields("Events", "events_custom_editor_fields");


// Register a Dashboard Tile
function events_custom_dashboard_tiles() {
	global $DBWebSQL, $Web_Config, $TableContent;

	// Content Count Published
	$result = mysqli_query($DBWebSQL, "SELECT COUNT(*) AS `Rows` FROM " . WebsiteSQL_Table_Content . " WHERE `PostStatus` = 'publish' AND `PostType` = 'Events'");
	$EventsPublished = mysqli_fetch_array($result, MYSQLI_ASSOC)['Rows'] ?? 0;

	// Content Count Published
	$result = mysqli_query($DBWebSQL, "SELECT COUNT(*) AS `Rows` FROM " . WebsiteSQL_Table_Content . " WHERE `PostStatus` = 'draft' AND `PostType` = 'Events'");
	$EventsDraft = mysqli_fetch_array($result, MYSQLI_ASSOC)['Rows'] ?? 0;
	
	$events_tile = '<div class="col-sm-6 col-lg-4">';
	$events_tile .= '<a class="tile" style="background-color: #009688;" href="/' . WebsiteSQL_Admin_FilePath . 'view/Events">';
	$events_tile .= '<i class="fal fa-fw fa-calendar-star" aria-hidden="true"></i><h1>Events</h1>';
	$events_tile .= '<p>' . $EventsPublished . ' published events</p>';
	$events_tile .= '<p>' . $EventsDraft . ' draft events</p>';
	$events_tile .= '</a>';
	$events_tile .= '</div>';

	return $events_tile;
}
register_custom_dashboard_tiles("events_custom_dashboard_tiles");


// Register a Menu Item
register_custom_menu_items("Events", "/" . WebsiteSQL_Admin_FilePath . "view/Events");


// Register Shortcode





function events_shortcode_template($page_string) {
	global $current_config, $DBWebSQL, $TableContent;

	$events_query = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE PostType='event' AND PostStatus='publish' ORDER BY PostDate DESC");

	$event = '';

	while ($events_row = mysqli_fetch_array($events_query, MYSQLI_ASSOC)) {
		$event .= '<div class="event"><div class="row"><div class="col-2 date"><span>' . date('j', $events_row['PostDate']) . '</span><br>' . date('M', $events_row['PostDate']) . '</div><div class="col-7 text"><h1>' . $events_row['PostTitle'] . '</h1></div><div class="col-3"><a href="' . $root . $events_row['PostSlug'] . '" class="button outline blue">Learn More</a></div></div></div>';
	}

	$events = '<div class="events">' . $event . '</div>';
	return str_ireplace('{{events}}', $events, $page_string);
}


 ?>