<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $this->e($title); ?> - Website SQL v<?= $this->getStrings()->getVersion(); ?></title>
<link rel="icon" href="/wsql-contents/assets/websql.png">

<link rel="stylesheet" href="/wsql-contents/assets/tailwind.css">
<link rel="stylesheet" href="/wsql-contents/assets/fontawesome/css/all.min.css">

<script>
    // Function: toggleDarkMode
    document.addEventListener('DOMContentLoaded', function() {
        // Get button with data-action="toggleDarkMode"
        const darkmodetoggle = document.querySelector('[data-action="toggleDarkMode"]');

        // Check if darkmode cookie is set
        if (document.cookie.includes('darkmode=true')) {
            darkmodetoggle.innerHTML = '<i class="fa-regular fa-moon align-top"></i>';
        }

        // Detect when darkmode button is clicked
        darkmodetoggle.addEventListener('click', function() {
            if (darkmodetoggle.innerHTML.includes('fa-sun')) {
                darkmodetoggle.innerHTML = '<i class="fa-regular fa-moon align-top"></i>';
                document.body.classList.add('dark');
                document.cookie = 'darkmode=true;path=/';
            } else {
                darkmodetoggle.innerHTML = '<i class="fa-regular fa-sun align-top"></i>';
                document.body.classList.remove('dark');
                document.cookie = 'darkmode=false;path=/';
            }
        });
    });
</script>