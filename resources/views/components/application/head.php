<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $this->e($title); ?> - Website SQL v<?= $this->getStrings()->getVersion(); ?></title>
<link rel="icon" href="/wsql-contents/assets/websql.png">

<link rel="stylesheet" href="/wsql-contents/assets/tailwind.css">
<link rel="stylesheet" href="/wsql-contents/assets/fontawesome/css/all.min.css">
<?php foreach ($this->getModuleCustomCssFiles() as $custom_css_file): ?>
    <link rel="stylesheet" href="<?= $custom_css_file; ?>">
<?php endforeach; ?>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="/wsql-contents/assets/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/wsql-contents/assets/moment.js"></script>
<script type="text/javascript" src="/wsql-contents/assets/jquerydatetimepicker.js"></script>	
<script type="text/javascript" src="/wsql-contents/assets/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/wsql-contents/assets/site.js"></script>	