<?php $this->layout('layout::application_old', ['title' => $title]); ?>

<div class="page wrapper">
    <?php $this->insert('component::old/menu', array('title' => $title)); ?>

					<!-- Edit User Modal -->
					<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="editUserModalLabel">Edit User : {{USERFULLNAME}}</h5>
						</div>
						<div class="modal-body">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item" role="presentation"><a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Details</a></li>
								<li class="nav-item" role="presentation"><a class="nav-link" id="permissions-tab" data-toggle="tab" href="#permissions" role="tab" aria-controls="permissions" aria-selected="false">Permissions</a></li>
								<li class="nav-item" role="presentation"><a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">Password</a></li>
							</ul>
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="message-text" class="col-form-label">Firstname</label>
												<input type="text" class="form-control" id="recipient-name">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="message-text" class="col-form-label">Lastname</label>
												<input type="text" class="form-control" id="recipient-name">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label for="message-text" class="col-form-label">Email Address</label>
												<input type="text" class="form-control" id="recipient-name">
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">

								</div>
								<div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="message-text" class="col-form-label">New Password</label>
												<input type="text" class="form-control" id="recipient-name">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="message-text" class="col-form-label">Repeat New Password</label>
												<input type="text" class="form-control" id="recipient-name">
											</div>
										</div>
										<div class="col-md-12">
											<button type="button" class="btn btn-success">Reset Password</button>
											<div class="alert alert-success" style="display: inline-block;padding: 6px 20px;vertical-align: top;margin-left: 15px;" role="alert">The password has now been reset.</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-success">Save changes</button>
						</div>
						</div>
					</div>
					</div>
					<!-- Add User Modal -->
					<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="addUserModalLabel">Add User</h5>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="message-text" class="col-form-label">Firstname</label>
										<input type="text" class="form-control" id="recipient-name">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="message-text" class="col-form-label">Lastname</label>
										<input type="text" class="form-control" id="recipient-name">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="message-text" class="col-form-label">Email Address</label>
										<input type="text" class="form-control" id="recipient-name">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-success">Add</button>
						</div>
						</div>
					</div>
					</div>
					
					<script>
						$(function () {
							$('[data-toggle="tooltip"]').tooltip();
						});
					</script>
					<div class="button-board">
						<button role="button" data-toggle="modal" data-target="#addUserModal" class="btn btn-success"> Add User </button>
					</div>
					<div style="overflow-x: auto;">
						<table class="table table-sm">
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Realm <span title="Realms allow multiple user account systems to be hosted through Website SQl, the default realm for this admin panel is: websitesql">(?)</span></th>
								<th>Created at</th>
                                <th>Status</th>
								<th style="width: 175px;"></th>
							</tr>
							<?php foreach ($users as $row) {
								if ($row['approved'] == 0) {
									$status = '<span style="color: #ff5722;"><i class="fas fa-circle" style="font-size: 14px;vertical-align: top;margin: 5px 10px 0 0;color: inherit;"></i>Unapproved</span>';
								} elseif ($row['locked'] == 1) {
									$status = '<span style="color: #f44336;"><i class="fas fa-circle" style="font-size: 14px;vertical-align: top;margin: 3px 10px 0 0;color: inherit;"></i>Locked</span>';					
								} elseif ($row['email_verified'] == 0) {
									$status = '<span style="color: #ff9800;"><i class="fas fa-circle" style="font-size: 14px;vertical-align: top;margin: 5px 10px 0 0;color: inherit;"></i>Email Unverified</span>';
								} else {
									$status = '<span style="color: #009688;"><i class="fas fa-circle" style="font-size: 14px;vertical-align: top;margin: 5px 10px 0 0;color: inherit;"></i>Active</span>';
								}
								echo '<tr>';
								echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
								echo '<td>' . $row['email'] . '</td>';
								echo '<td>' . $row['realm'] . '</td>';
                                echo '<td>' . $row['created_at'] . '</td>';
								echo '<td>' . $status . '</td>';
								echo '<td class="buttons"><button role="button" style="margin: 0 5px 0 0;" data-toggle="modal" data-target="#editUserModal" class="btn btn-info btn-sm">Manage</button>
								<button role="button" data-toggle="modal" data-target="#deleteUserModal" class="btn btn-danger btn-sm">Delete</button></td>';
								echo '</tr>';
							} ?>
						</table>
					</div>
</div>