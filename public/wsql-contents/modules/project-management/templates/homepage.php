<!-- Error Messages -->
<?php if (isset($error) && !empty($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<!-- Homepage -->
<div class="mt-8 grid grid-cols-12 gap-2">
    <div class="col-span-8">
        <h1 class="text-2xl font-bold">Project Management</h1>
    </div>
    <div class="col-span-4">
        <div class="border border-gray-100 p-6 rounded-xl shadow-sm">
            <h1 class="!text-2xl mb-3">Create Project</h1>
            <form method="post">
                <div class="form-group">
                    <label for="project-name">Project Name</label>
                    <input type="text" name="project-name" id="project-name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="project-description">Project Description</label>
                    <textarea name="project-description" id="project-description" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>