<?php /* WebSQL - Search Module - V1.0.0 - (C) Copyright Alan Tiller 2020. */

// Register Module Details
function search_register_details() {
	return array("ModuleName" => "Search Module", "ModuleDescription" => "Adds a search function to the website.", "ModuleVersion" => "1.0.0", "ModuleAuthor" => "Alan Tiller");
}
register_module_details("search_register_details");

function search_admin_details() {
	return array("ModuleName" => "Search Module",
	"ModuleVersion" => "1.0.0",
	"ModuleAuthor" => "Alan Tiller");
}

function search_shortcode_template($page_string) {
	global $WebsiteSettings, $DBWebSQL, $_GET, $TableContent;

	$search_text = string_filter($_GET['q']);

	if ($search_text !== '') {
		if (!empty($_GET['types'])) {
			$types = string_filter($_GET['types']);
			$search_query = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE PostTitle LIKE '%$search_text%' AND PostType='$types' ORDER BY id DESC");
			$extra = 'with the content filter <b>' . $types . '</b>';
		} else {
			$search_query = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE PostTitle LIKE '%$search_text%' ORDER BY id DESC");	
		}

		if (mysqli_num_rows($search_query) > 0) {
			$search_results = '<div class="search-top"><div class="results">' . mysqli_num_rows($search_query) . ' results found</div></div>';
			while ($search_row = mysqli_fetch_array($search_query, MYSQLI_ASSOC)) { 
				$search_results .= '<div class="search-result"><a href="' . $WebsiteSettings['WebsiteRoot'] . $search_row['PostSlug'] . '" class="item">' . $search_row['PostTitle'] . '</a><h5>' . $WebsiteSettings['WebsiteRoot'] . $search_row['PostSlug'] . '</h5><p>' . $search_row['PostExcerpt'] . '</p></div>';
			}
		} else {
			$search_results = '<div class="search-top"><div class="results">0 results found</div></div>';
			$search_results .= '<p style="margin-top: 16px;">We couldnt find anything for “' . $search_text . '” ' . $extra . '.</p>';
		}
	}

	$page_string = str_ireplace('{{search_text}}', $search_text, $page_string);
	return str_ireplace('{{search_results}}', $search_results, $page_string);
}


 ?>