<?php /* WebSQL - Posts Module - V1.0.0 - (C) Copyright Alan Tiller 2020. */

// Register Module Details
function posts_register_details() {
	return array("ModuleName" => "Posts Module", "ModuleDescription" => "Adds news articles functionality.", "ModuleVersion" => "1.0.0", "ModuleAuthor" => "Alan Tiller");
}
register_module_details("posts_register_details");


// Register a Dashboard Tile
function posts_custom_dashboard_tiles() {
	global $DBWebSQL, $Web_Config, $TableContent;

	// Content Count Published
	$result = mysqli_query($DBWebSQL, "SELECT COUNT(*) AS `Rows` FROM " . WebsiteSQL_Table_Content . " WHERE `PostStatus` = 'publish' AND `PostType` = 'Posts'");
	$PostsPublished = mysqli_fetch_array($result, MYSQLI_ASSOC)['Rows'] ?? 0;

	// Content Count Published
	$result = mysqli_query($DBWebSQL, "SELECT COUNT(*) AS `Rows` FROM " . WebsiteSQL_Table_Content . " WHERE `PostStatus` = 'draft' AND `PostType` = 'Posts'");
	$PostsDraft = mysqli_fetch_array($result, MYSQLI_ASSOC)['Rows'] ?? 0;
	
	$posts_tile = '<div class="col-sm-6 col-lg-4">';
	$posts_tile .= '<a class="tile" style="background-color: #ff9800;" href="/' . WebsiteSQL_Admin_FilePath . 'view/Posts">';
	$posts_tile .= '<i class="fal fa-fw fa-newspaper" aria-hidden="true"></i><h1>Posts</h1>';
	$posts_tile .= '<p>' . $PostsPublished . ' published posts</p>';
	$posts_tile .= '<p>' . $PostsDraft . ' draft posts</p>';
	$posts_tile .= '</a>';
	$posts_tile .= '</div>';

	return $posts_tile;
}
register_custom_dashboard_tiles("posts_custom_dashboard_tiles");


// Register a Menu Item
register_custom_menu_items("Posts", "/" . WebsiteSQL_Admin_FilePath . "view/Posts");



function news_admin_details() {
	return array("ModuleName" => "Posts Module",
	"ModuleVersion" => "1.0.0",
	"ModuleAuthor" => "Alan Tiller");
}

function news_shortcode_template($page_string) {
	global $WebsiteSettings, $DBWebSQL, $TableContent;

	$news_articles_query = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE PostType='Posts' AND PostStatus='publish' ORDER BY PostDate DESC");

	$na6_0 = ''; 
	$na6_1 = ''; 
	$na6_2 = ''; 
	$na_0 = ''; 
	$na_1 = ''; 
	$na_2 = ''; 
	$na_count = 0; 
	$na_row_count = 0;

	while ($news_articles_row = mysqli_fetch_array($news_articles_query, MYSQLI_ASSOC)) {
		if ($news_articles_row['PostImage'] !== '') {
			$image = '<div class="img_container"><img class="background" src="' . $news_articles_row['PostImage'] . '" alt="' . $news_articles_row['PostTitle'] . '"></div>';
		}
		$article = '<a class="article" href="' . $WebsiteSettings['WebsiteRoot'] . $news_articles_row['PostSlug'] .'">' . $image . '<div class="text"><h1>' . $news_articles_row['PostTitle'] . '</h1><h2>' . $news_articles_row['PostExcerpt'] . '</h2></div></a>';
		if ($na_count < 1) {
			$na1 = $article;
		}
		if ($na_row_count == 0) {
			if ($na_count < 6) {
				$na6_0 .= $article;
			}
			$na_0 .= $article;
			$na_row_count++;
		} elseif ($na_row_count == 1) {
			if ($na_count < 6) {
				$na6_1 .= $article;
			}
			$na_1 .= $article;
			$na_row_count++;
		} elseif ($na_row_count == 2) {
			if ($na_count < 6) {
				$na6_2 .= $article;
			}
			$na_2 .= $article;
			$na_row_count = 0;
		}
		$na_count++;
		$article = '';
	}

	$news_articles_6 = '<div class="news_articles"><div class="row"><div class="col-md-4">' . $na6_0 . '</div><div class="col-md-4">' . $na6_1 . '</div><div class="col-md-4">' . $na6_2 . '</div></div></div>';
	$news_articles_1 = '<div class="news_articles"><div class="row"><div class="col-md-12">' . $na1 . '</div></div></div>';
	$news_articles = '<div class="news_articles"><div class="row"><div class="col-md-4">' . $na_0 . '</div><div class="col-md-4">' . $na_1 . '</div><div class="col-md-4">' . $na_2 . '</div></div></div>';

	$page_string = str_ireplace('{{news_articles_6}}', $news_articles_6, $page_string);
	$page_string = str_ireplace('{{news_articles_1}}', $news_articles_1, $page_string);
	return str_ireplace('{{news_articles}}', $news_articles, $page_string);
}


 ?>