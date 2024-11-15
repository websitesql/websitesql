<?php /* WebSQL - Training Module - V1.0.0 - (C) Copyright Alan Tiller 2020. */

// Register Module Details
function training_register_details() {
	return array("ModuleName" => "Training Module", "ModuleDescription" => "Training diary with booking manager and reporting functions.", "ModuleVersion" => "0.0.1 Alpha", "ModuleAuthor" => "Alan Tiller");
}
register_module_details("training_register_details");


// Register a Dashboard Tile
function training_custom_dashboard_tiles() {
	global $DBWebSQL, $Web_Config, $TableContent;

	// Content Count Published
	$result = mysqli_query($DBWebSQL, "SELECT COUNT(*) AS `Rows` FROM " . WebsiteSQL_Table_Content . " WHERE `PostStatus` = 'publish' AND `PostType` = 'Training'");
	$TrainingPublished = mysqli_fetch_array($result, MYSQLI_ASSOC)['Rows'] ?? 0;

	// Content Count Published
	$result = mysqli_query($DBWebSQL, "SELECT COUNT(*) AS `Rows` FROM " . WebsiteSQL_Table_Content . " WHERE `PostStatus` = 'draft' AND `PostType` = 'Training'");
	$TrainingDraft = mysqli_fetch_array($result, MYSQLI_ASSOC)['Rows'] ?? 0;
	
	$training_tile = '<div class="col-sm-6 col-lg-4">';
	$training_tile .= '<a class="tile" style="background-color: #9c27b0;" href="/' . WebsiteSQL_Admin_FilePath . 'view/Training">';
	$training_tile .= '<i class="fal fa-fw fa-award" aria-hidden="true"></i><h1>Training</h1>';
	$training_tile .= '<p>' . $TrainingPublished . ' published sessions</p>';
	$training_tile .= '<p>' . $TrainingDraft . ' draft sessions</p>';
	$training_tile .= '</a>';
	$training_tile .= '</div>';

	return $training_tile;
}
register_custom_dashboard_tiles("training_custom_dashboard_tiles");


// Register a Menu Item
register_custom_menu_items("Training", "/" . WebsiteSQL_Admin_FilePath . "view/Training");

function training_page_overview() {
	global $DBWebSQL, $Web_Config, $TableContent;

}
register_custom_page("training_overview", "training_page_overview");

?>