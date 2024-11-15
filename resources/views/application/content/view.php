<?php $this->layout('layout::application_old', ['title' => $title]); ?>

<div class="page wrapper">
    <?php $this->insert('component::old/menu', array('title' => $title)); ?>
    <div class="button-board">
		<a class="btn btn-success" href="/<?= $this->e($this->getStrings()->getAdminFilePath()); ?>?page=new&type=<?php echo $_GET['type']; ?>"> Create </a>
		<button type="button" class="btn btn-primary" style="float: right;" data-toggle="modal" data-target="#ContentEditorModal">Content Editor V2</button>
		<button class="btn btn-secondary" style="float: right;"><i class="fa-solid fa-fw fa-wrench"></i></button>
	</div>
	<div style="overflow-x: auto;">
		<table id="ViewItems" class="table table-sm">
			<col class="col1"/>
			<col class="col2"/>
			<col class="col3"/>
			<col class="col4"/>
			<col class="col5"/>
			<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col">Title</th>
					<th scope="col">Slug</th>
					<th scope="col">Status</th>
					<th scope="col">Date</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($contentData):
                    foreach ($contentData as $PostTypeRow):
						echo '<tr>';
						echo '	<th scope="row"><a href="/' . $app->getStrings()->getAdminFilePath() . 'edit/' . $PostTypeRow['ID'] . '">' . $PostTypeRow['ID'] . '</a></th>';
						echo '	<td>' . $PostTypeRow['PostTitle'] . '</td>';
						echo '	<td>' . $PostTypeRow['PostSlug'] . '</td>';
						echo '	<td>' . $PostTypeRow['PostStatus'] . '</td>';
						echo '	<td>' . date("d/m/Y", strtotime($PostTypeRow['PostDate'])) . '</td>';
						echo '</tr>';
                    endforeach;
                else:
					echo '<tr><td colspan="5">No content could be found.</td></tr>';
                endif; ?>
			</tbody>
		</table>
	</div>
</div>