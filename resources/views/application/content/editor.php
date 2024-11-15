<?php $this->layout('layout::application_old', ['title' => $title]); ?>

<div class="page wrapper">
    <?php $this->insert('component::old/menu', array('title' => $title)); ?>

    <?= $this->e($error); ?>

    <form method="POST" class="page_editor">
        <div class="row">
			<div class="col">
				<div class="form-group">
					<input type="text" class="form-control" value="<?php echo $editorData['PostTitle'] ?? ''; ?>" class="postname" name="PostTitle" placeholder="Add a title" required="">
				</div>
			</div>
			<div class="col text-right">
				<?php if ($mode === "edit") { ?>
					<a href="/<?php echo $app->getStrings()->getAdminFilePath(); ?>delete&id=<?php echo $explode_slug[1]; ?>" class="btn btn-danger">Delete</a>
					<button type="submit" class="btn btn-success" name="contentUpdate">Update</button>
				<?php } elseif ($mode === "new") { ?>
					<button type="submit" class="btn btn-success" name="contentCreate">Create</button>
				<?php } ?>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="form-group">
					<textarea name="PostContent" id="PostContent" style="height: 500px;">
						<?php echo $editorData['PostContent'] ?? ''; ?>
					</textarea>
				</div>
			</div>
		</div>
					
        
        <div class="row">
			<div class="col">
				<div class="accordion">
					<h3>Status &amp; visibility</h3>
					<div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="PostStatus">Status</label>
									<select name="PostStatus" id="PostStatus" class="form-control">
										<option <?php if ($editorData['PostStatus'] ?? '' === 'publish') {echo 'selected';} ?> value="publish">Published</option>
										<option <?php if ($editorData['PostStatus'] ?? '' === 'draft') {echo 'selected';} ?> value="draft">Draft</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="PostProtected">Visibility</label>
									<select name="PostProtected" id="PostProtected" class="form-control">
										<option <?php if ($editorData['PostProtected'] ?? '' === '0') {echo 'selected';} ?> value="0">Public</option>
										<option <?php if ($editorData['PostProtected'] ?? '' === '1') {echo 'selected';} ?> value="1">Authenticated Users Only</option>
										<option <?php if ($editorData['PostProtected'] ?? '' === '2') {echo 'selected';} ?> value="2">Non Authenticated Users Only</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="PostDate">Date Published</label>
									<input type="text" class="form-control" id="PostDate" name="PostDate" value="<?php echo date('d/m/Y h:i:s', (!empty($editorData['PostDate']) ? strtotime($editorData['PostDate']) : time()) ); ?>" required>
								</div>	
							</div>
						</div>
					</div>
								<?php if ((isset($editorData['PostType']) && $editorData['PostType'] === 'Pages') || $postType === 'Pages') { ?>
									<h3>Page Attributes</h3>
									<div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="PostTemplate">Template</label>
													<select name="PostTemplate" id="PostTemplate" class="form-control">
														<option <?php if ($editorData['PostTemplate'] ?? '' === 'Default') {echo 'selected';} ?> value="Default">Default</option>
														<option <?php if ($editorData['PostTemplate'] ?? '' === 'Groups') {echo 'selected';} ?> value="Groups">Groups</option>
														<option <?php if ($editorData['PostTemplate'] ?? '' === 'Homepage') {echo 'selected';} ?> value="Homepage">Homepage</option>
														<option <?php if ($editorData['PostTemplate'] ?? '' === 'Posts') {echo 'selected';} ?> value="Posts">Posts</option>
														<option <?php if ($editorData['PostTemplate'] ?? '' === 'Events') {echo 'selected';} ?> value="Events">Events</option>
														<option <?php if ($editorData['PostTemplate'] ?? '' === 'People') {echo 'selected';} ?> value="People">People</option>
														<option <?php if ($editorData['PostTemplate'] ?? '' === 'Training') {echo 'selected';} ?> value="Training">Training</option>
														<option <?php if ($editorData['PostTemplate'] ?? '' === 'Redirects') {echo 'selected';} ?> value="Redirects">Redirect</option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="PostParent">Parent</label>
													<select name="PostParent" id="PostParent" class="form-control">
														<option value="0">No Parent</option>
														<?php // $PostType = $editorData['PostType'] ?: $explode_slug[1];
														// $post_query_parent = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Content . " WHERE PostType='$PostType'");
														// while ($post_row_parent = mysqli_fetch_array($post_query_parent, MYSQLI_ASSOC)) {
														// 	if ($post_row_parent['ID'] == $PostID) {} elseif ($post_row_parent['PostSlug'] == '/') {} else {
														// 		echo '<option '.($post_row_parent['ID'] == $editorData['PostParent'] ? 'selected' : '').' value="' . $post_row_parent['ID'] . '">' . $post_row_parent['PostTitle'] . '</option>';
														// 	}
														// 	$act = '';
														// } ?>
													</select>
												</div>
											</div>
											<div class="col-4"></div>
										</div>
									</div>
								<?php } ?>
								<?php if (isset($editorData['PostSlug'])) { ?>
									<h3>Permalink</h3>
									<div>
										<div class="row">
											<div class="col">
												<?php if ($editorData['PostSlug'] !== '/') {
													$slug_split = explode('/', $editorData['PostSlug']);
													$slug_reverse = array_reverse( $slug_split );
													$slug_edit = $slug_reverse[1];
													$slug_before = str_replace($slug_edit . "/", "", $editorData['PostSlug']); ?>
													<label for="PostSlug">URL Slug</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<div class="input-group-text"><?php echo $WebsiteSettings['WebsiteRoot'] . $slug_before; ?></div>
														</div>
														<input type="text" onchange="update_slug_preview();" class="form-control" id="PostSlug" name="PostSlug" placeholder="Slug" value="<?php echo $slug_edit; ?>">
														<div class="input-group-append">
															<div class="input-group-text">/</div>
														</div>
													</div>
												<?php } else { ?>
													<label>View Page
														<a target="_blank" href="<?php echo $WebsiteSettings['WebsiteRoot'] . '/' . $editorData['PostSlug']; ?>">
															<?php echo $WebsiteSettings['WebsiteRoot'] . '/' . $editorData['PostSlug']; ?> <i class="fal fa-external-link-square-alt"></i>
														</a>
													</label>	
												<?php } ?>
											</div>
										</div>
									</div>
								<?php } ?>
					<h3>Featured image</h3>
					<div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="PostImage">Featured Image</label>
									<select name="PostImage" id="PostImage" style="width:100%;" class="form-control">
										<option value="">No image selected</option>
										<?php foreach ($media as $mediaItem):
										    if (in_array($mediaItem['FileExtension'], array('gif', 'GIF', 'png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG'))):
										        $URL = rtrim($this->getSetting('WebsiteRoot'), '/') . '/wsql-contents/uploads/' . $mediaItem['FileHash'] . '/' . $mediaItem['FileName'];
										        echo '<option '.($URL === $editorData['PostImage'] ? 'selected' : '').' value="' . $URL . '">' . $mediaItem['FileDescription'] . '</option>';
                                            endif;
										endforeach; ?>
									</select>
								</div>	
							</div>
						</div>
					</div>
					<h3>Excerpt</h3>
					<div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="PostExcerpt">Write an excerpt</label>
									<textarea name="PostExcerpt" id="PostExcerpt" class="form-control" rows="5"><?php echo $editorData['PostExcerpt'] ?? ''; ?></textarea>
								</div>	
							</div>
						</div>
                    </div>
								<?php // $PostType = $editorData['PostType'] ?: $explode_slug[1];
								// echo get_custom_editor_fields($PostType, json_decode($editorData['PostCustom'], true)); ?>
								<h3>Custom Fields</h3>
								<div>
									<script>
										function addOption() {
											var item = Math.random().toString(36).substring(7);
											$("#CustomFieldsItems").append($('<tr><td><input type="text" class="form-control" id="" name="PostCustomField[' + item + ']" value=""></td><td><input type="text" class="form-control" id="" name="PostCustomValue[' + item + ']" value=""></td><td><button type="button" class="btn btn-danger" onclick="$(this).closest(\'tr\').remove();"><i class="fal fa-trash-alt"></i></button></td></tr>'));
										}
										function removeOption() {
											
										}
									</script>
									<div class="row">
										<div class="col-md-12">
											<table id="CustomFieldsItems" class="table table-sm">
												<thead>
													<tr>
														<th style="width:30%;">Slug (Shortcode)</th>
														<th>Value</th>
														<th style="width:50px;"></th>
													</tr>
												</thead>
												<tbody>
													<?php $CustomFields = json_decode($editorData['PostCustom'], true);
													foreach($CustomFields as $Field => $Value) {
														echo '<tr>';
														echo '<td><input type="text" class="form-control" id="" name="PostCustomField[' . $Field . ']" value="' . $Field . '"></td>';
														echo '<td><input type="text" class="form-control" id="" name="PostCustomValue[' . $Field . ']" value="' . $Value . '"></td>';
														echo '<td><button type="button" class="btn btn-danger" onclick="$(this).closest(\'tr\').remove();"><i class="fal fa-trash-alt"></i></button></td>';
														echo '</tr>';
													} ?>
												</tbody>
											</table>
										</div>
										<div class="col-md-12 text-right">
											<button type="button" class="btn btn-success" onclick="addOption();">Add Field</button>
										</div>
									</div>
								</div>
				</div>
			</div>
		</div>
	</form>
</div>
			<script>
					// More settings 
					$( function() {
						$( ".accordion" ).accordion({
							heightStyle: "content",
							collapsible: true
						});
					});
					// Slug Preview
					function update_slug_preview() {
						$( "#slug_preview" ).html( $( "#post_slug" ).val() );
					}
					// Datepicker
					$( function() {
						var $fp = $( "#PostDate" ), now = moment( ).subtract( "seconds", 1 );
						$fp.filthypillow({
							enable24HourTime: true
						});
						$fp.on( "focus", function( ) {
							$fp.filthypillow( "show" );
						});
						$fp.on( "fp:save", function( e, dateObj ) {
							$fp.val( dateObj.format( "DD/MM/YYYY hh:mm:ss" ) );
							$fp.filthypillow( "hide" );
						});
					} );
					// Main Content Editor
					tinymce.init({
						selector: "#PostContent",
						plugins: "image fullscreen link lists media code table",
						branding: false,
						body_class : "<?php echo $WebsiteSettings['TinyMCEBodyClass'] ?? ''; ?>",
						content_css : "",
						contextmenu: 'copy paste | link inserttable | cell row column deletetable',
						menubar: "edit insert format view",
						toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | numlist bullist | outdent indent | link media | image alertbox | fullscreen',
						image_list: [
							<?php // $images = ''; 
							// $UploadsQuery = mysqli_query($DBWebSQL, "SELECT * FROM " . WebsiteSQL_Table_Uploads . "");
							// while ($UploadsRow = mysqli_fetch_array($UploadsQuery, MYSQLI_ASSOC)) {
							// 	if (in_array($UploadsRow['FileExtension'], array('gif', 'GIF', 'png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG'))) {
							// 		$images .= "{title: '".$UploadsRow['FileDescription']."', value: '".$WebsiteSettings['WebsiteRoot']."uploads/".$UploadsRow['FileHash']."/" . $UploadsRow['FileName'] . "'},";
							// 	}
							// } 
							// echo substr($images, 0, -1); ?>
						],
						convert_urls: true,
						relative_urls: false,
						remove_script_host: false,
						color_map: ["000000", "Black","FFFFFF", "White","7413dc", "Scouts Purple","00a794", "Scouts Teal","e22e12", "Scouts Red","ffb4e5", "Scouts Pink","23a950", "Scouts Green","003982", "Scouts Navy","006ddf", "Scouts Blue","ffe627", "Scouts Yellow"],
						setup: (editor) => {editor.ui.registry.addButton('alertbox', {icon: 'info',tooltip: 'Insert Alert Box', onAction: function (_) {editor.insertContent("<div class='editor-alert-box'><h4>This is an Alert Box</h4><p>Some subtext</p></div>");}});}
					});
			</script>