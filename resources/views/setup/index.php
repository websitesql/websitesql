<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Setup - <?= $this->getApplicationName(); ?></title>

        <link rel="stylesheet" href="/wsql-contents/assets/css/app.min.css">
        <script type="text/javascript" src="/wsql-contents/assets/js/app.min.js"></script>	

        <script>
            // Toggle view details on error
            function toggleViewDetails() {
                var details = document.getElementById('error_details');

                if (details.style.display === 'none') {
                    details.style.display = 'block';
                } else {
                    details.style.display = 'none';
                }
            }
        </script>
    </head>
    <body>
        <!-- Header -->
        <div class="relative py-7">
            <div class="relative w-full max-w-5xl mx-auto px-8">
                <div class="flex justify-between items-center font-baloo text-gray-600 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 492 92" class="h-9">
                        <g id="a16f57fb-9955-44f0-b080-4aad988676c8" class="fill-dark dark:fill-white" transform="matrix(4.934210588231973,0,0,4.934210588231973,99.93281521856602,-7.820207889927069)">
                            <path d="M4.83 8.29L4.83 8.29Q4.98 8.22 5.31 8.13Q5.64 8.04 5.98 8.04L5.98 8.04Q6.57 8.04 6.96 8.21Q7.35 8.39 7.43 8.72L7.43 8.72Q7.59 9.30 7.72 9.79Q7.85 10.29 7.98 10.75Q8.11 11.20 8.22 11.65Q8.34 12.10 8.46 12.59L8.46 12.59L8.53 12.59Q8.65 11.73 8.75 10.96Q8.85 10.18 8.93 9.43Q9.02 8.68 9.10 7.95Q9.18 7.21 9.27 6.45L9.27 6.45Q9.79 6.16 10.37 6.16L10.37 6.16Q10.89 6.16 11.27 6.38Q11.65 6.61 11.65 7.14L11.65 7.14Q11.65 7.45 11.59 8.02Q11.52 8.58 11.42 9.28Q11.31 9.98 11.17 10.75Q11.03 11.52 10.88 12.22Q10.72 12.92 10.56 13.49Q10.40 14.06 10.26 14.36L10.26 14.36Q10.05 14.56 9.58 14.69Q9.10 14.81 8.60 14.81L8.60 14.81Q7.94 14.81 7.48 14.64Q7.03 14.46 6.93 14.14L6.93 14.14Q6.75 13.58 6.54 12.81Q6.33 12.04 6.09 11.12L6.09 11.12Q5.89 12.03 5.68 12.89Q5.47 13.76 5.31 14.36L5.31 14.36Q5.10 14.56 4.68 14.69Q4.26 14.81 3.75 14.81L3.75 14.81Q3.12 14.81 2.61 14.64Q2.10 14.46 1.95 14.14L1.95 14.14Q1.82 13.89 1.67 13.39Q1.53 12.89 1.37 12.26Q1.22 11.62 1.06 10.88Q0.90 10.14 0.76 9.39Q0.63 8.65 0.52 7.95Q0.41 7.24 0.35 6.65L0.35 6.65Q0.55 6.47 0.90 6.31Q1.25 6.16 1.65 6.16L1.65 6.16Q2.18 6.16 2.53 6.39Q2.87 6.62 2.95 7.17L2.95 7.17Q3.18 8.60 3.33 9.55Q3.47 10.50 3.56 11.11Q3.65 11.72 3.70 12.05Q3.74 12.39 3.78 12.59L3.78 12.59L3.85 12.59Q3.98 12.03 4.09 11.55Q4.20 11.07 4.32 10.59Q4.44 10.11 4.56 9.55Q4.69 9.00 4.83 8.29ZM16.41 14.92L16.41 14.92Q15.58 14.92 14.88 14.69Q14.17 14.46 13.64 14.00Q13.12 13.54 12.82 12.84Q12.52 12.14 12.52 11.20L12.52 11.20Q12.52 10.28 12.82 9.61Q13.12 8.95 13.61 8.52Q14.10 8.09 14.73 7.89Q15.36 7.69 16.02 7.69L16.02 7.69Q16.76 7.69 17.37 7.91Q17.98 8.13 18.42 8.53Q18.86 8.92 19.10 9.46Q19.35 10.01 19.35 10.65L19.35 10.65Q19.35 11.13 19.08 11.38Q18.82 11.63 18.34 11.70L18.34 11.70L14.88 12.22Q15.04 12.68 15.51 12.92Q15.99 13.15 16.60 13.15L16.60 13.15Q17.18 13.15 17.69 13.00Q18.20 12.85 18.52 12.66L18.52 12.66Q18.75 12.80 18.90 13.05Q19.05 13.30 19.05 13.58L19.05 13.58Q19.05 14.21 18.47 14.52L18.47 14.52Q18.02 14.76 17.46 14.84Q16.90 14.92 16.41 14.92ZM16.02 9.42L16.02 9.42Q15.68 9.42 15.44 9.53Q15.19 9.65 15.04 9.82Q14.88 10.00 14.80 10.21Q14.73 10.43 14.71 10.65L14.71 10.65L17.11 10.26Q17.07 9.98 16.80 9.70Q16.53 9.42 16.02 9.42ZM20.52 13.05L20.52 5.59Q20.66 5.54 20.97 5.50Q21.28 5.45 21.60 5.45L21.60 5.45Q21.91 5.45 22.16 5.49Q22.40 5.53 22.57 5.66Q22.74 5.78 22.82 6.00Q22.90 6.22 22.90 6.57L22.90 6.57L22.90 7.94Q23.25 7.80 23.57 7.74Q23.88 7.69 24.26 7.69L24.26 7.69Q24.93 7.69 25.54 7.92Q26.14 8.16 26.60 8.62Q27.06 9.07 27.33 9.74Q27.59 10.42 27.59 11.30L27.59 11.30Q27.59 12.21 27.32 12.89Q27.05 13.57 26.56 14.01Q26.07 14.46 25.36 14.69Q24.65 14.92 23.80 14.92L23.80 14.92Q22.88 14.92 22.23 14.71Q21.59 14.49 21.14 14.17L21.14 14.17Q20.52 13.73 20.52 13.05L20.52 13.05ZM23.80 13.05L23.80 13.05Q24.44 13.05 24.80 12.61Q25.16 12.17 25.16 11.30L25.16 11.30Q25.16 10.42 24.79 9.98Q24.43 9.55 23.81 9.55L23.81 9.55Q23.53 9.55 23.33 9.60Q23.13 9.66 22.89 9.77L22.89 9.77L22.89 12.82Q23.04 12.92 23.26 12.99Q23.48 13.05 23.80 13.05ZM34.50 12.67L34.50 12.67Q34.50 13.73 33.70 14.34Q32.90 14.94 31.35 14.94L31.35 14.94Q30.76 14.94 30.25 14.85Q29.75 14.77 29.39 14.60Q29.04 14.42 28.83 14.15Q28.63 13.89 28.63 13.52L28.63 13.52Q28.63 13.19 28.77 12.96Q28.91 12.73 29.11 12.57L29.11 12.57Q29.51 12.80 30.04 12.97Q30.56 13.15 31.25 13.15L31.25 13.15Q31.68 13.15 31.91 13.02Q32.14 12.89 32.14 12.68L32.14 12.68Q32.14 12.49 31.98 12.38Q31.81 12.26 31.42 12.19L31.42 12.19L31.00 12.11Q29.78 11.87 29.18 11.36Q28.59 10.85 28.59 9.90L28.59 9.90Q28.59 9.38 28.81 8.96Q29.04 8.54 29.44 8.26Q29.85 7.98 30.41 7.83Q30.98 7.67 31.67 7.67L31.67 7.67Q32.19 7.67 32.64 7.75Q33.10 7.83 33.43 7.98Q33.77 8.13 33.96 8.38Q34.16 8.62 34.16 8.96L34.16 8.96Q34.16 9.28 34.04 9.51Q33.92 9.74 33.74 9.90L33.74 9.90Q33.63 9.83 33.40 9.75Q33.18 9.67 32.91 9.61Q32.65 9.55 32.38 9.51Q32.10 9.46 31.88 9.46L31.88 9.46Q31.42 9.46 31.16 9.57Q30.91 9.67 30.91 9.90L30.91 9.90Q30.91 10.05 31.05 10.15Q31.19 10.25 31.58 10.33L31.58 10.33L32.02 10.43Q33.36 10.74 33.93 11.28Q34.50 11.82 34.50 12.67ZM35.59 6.01L35.59 6.01Q35.59 5.47 35.94 5.11Q36.30 4.75 36.89 4.75L36.89 4.75Q37.48 4.75 37.83 5.11Q38.19 5.47 38.19 6.01L38.19 6.01Q38.19 6.54 37.83 6.90Q37.48 7.27 36.89 7.27L36.89 7.27Q36.30 7.27 35.94 6.90Q35.59 6.54 35.59 6.01ZM38.08 8.99L38.08 14.69Q37.93 14.71 37.63 14.76Q37.32 14.81 37.02 14.81L37.02 14.81Q36.71 14.81 36.46 14.77Q36.22 14.73 36.05 14.60Q35.88 14.48 35.79 14.26Q35.70 14.04 35.70 13.69L35.70 13.69L35.70 7.99Q35.85 7.97 36.15 7.92Q36.46 7.87 36.76 7.87L36.76 7.87Q37.07 7.87 37.32 7.91Q37.56 7.95 37.73 8.08Q37.90 8.20 37.99 8.42Q38.08 8.64 38.08 8.99L38.08 8.99ZM41.97 10.25L41.97 12.36Q41.97 12.73 42.20 12.88Q42.43 13.03 42.85 13.03L42.85 13.03Q43.06 13.03 43.29 13.00Q43.51 12.96 43.68 12.91L43.68 12.91Q43.81 13.06 43.90 13.25Q43.99 13.44 43.99 13.71L43.99 13.71Q43.99 14.24 43.59 14.57Q43.19 14.91 42.18 14.91L42.18 14.91Q40.95 14.91 40.28 14.35Q39.62 13.79 39.62 12.53L39.62 12.53L39.62 6.54Q39.77 6.50 40.06 6.45Q40.35 6.40 40.67 6.40L40.67 6.40Q41.29 6.40 41.63 6.62Q41.97 6.83 41.97 7.53L41.97 7.53L41.97 8.43L43.81 8.43Q43.89 8.58 43.97 8.81Q44.04 9.04 44.04 9.32L44.04 9.32Q44.04 9.81 43.83 10.03Q43.61 10.25 43.25 10.25L43.25 10.25L41.97 10.25ZM48.79 14.92L48.79 14.92Q47.96 14.92 47.26 14.69Q46.55 14.46 46.02 14.00Q45.50 13.54 45.20 12.84Q44.90 12.14 44.90 11.20L44.90 11.20Q44.90 10.28 45.20 9.61Q45.50 8.95 45.99 8.52Q46.48 8.09 47.11 7.89Q47.74 7.69 48.40 7.69L48.40 7.69Q49.14 7.69 49.75 7.91Q50.36 8.13 50.80 8.53Q51.24 8.92 51.48 9.46Q51.73 10.01 51.73 10.65L51.73 10.65Q51.73 11.13 51.46 11.38Q51.20 11.63 50.72 11.70L50.72 11.70L47.26 12.22Q47.42 12.68 47.89 12.92Q48.37 13.15 48.99 13.15L48.99 13.15Q49.56 13.15 50.07 13.00Q50.58 12.85 50.90 12.66L50.90 12.66Q51.13 12.80 51.28 13.05Q51.44 13.30 51.44 13.58L51.44 13.58Q51.44 14.21 50.85 14.52L50.85 14.52Q50.40 14.76 49.84 14.84Q49.28 14.92 48.79 14.92ZM48.40 9.42L48.40 9.42Q48.06 9.42 47.82 9.53Q47.57 9.65 47.42 9.82Q47.26 10.00 47.19 10.21Q47.11 10.43 47.10 10.65L47.10 10.65L49.49 10.26Q49.45 9.98 49.18 9.70Q48.92 9.42 48.40 9.42ZM58.86 11.52L58.27 11.33Q57.65 11.12 57.15 10.90Q56.64 10.68 56.28 10.37Q55.92 10.07 55.71 9.64Q55.51 9.21 55.51 8.60L55.51 8.60Q55.51 7.41 56.43 6.69Q57.34 5.98 59.00 5.98L59.00 5.98Q59.60 5.98 60.12 6.06Q60.63 6.15 61.00 6.32Q61.38 6.50 61.59 6.77Q61.80 7.04 61.80 7.41L61.80 7.41Q61.80 7.77 61.63 8.03Q61.46 8.29 61.22 8.47L61.22 8.47Q60.91 8.27 60.40 8.13Q59.88 7.98 59.26 7.98L59.26 7.98Q58.63 7.98 58.34 8.16Q58.04 8.33 58.04 8.60L58.04 8.60Q58.04 8.81 58.23 8.94Q58.41 9.07 58.77 9.18L58.77 9.18L59.51 9.42Q60.83 9.84 61.54 10.49Q62.24 11.14 62.24 12.26L62.24 12.26Q62.24 13.45 61.31 14.19Q60.37 14.92 58.55 14.92L58.55 14.92Q57.90 14.92 57.35 14.82Q56.80 14.71 56.38 14.51Q55.97 14.31 55.74 14.01Q55.51 13.71 55.51 13.31L55.51 13.31Q55.51 12.91 55.75 12.62Q55.99 12.33 56.27 12.18L56.27 12.18Q56.66 12.49 57.22 12.71Q57.79 12.94 58.46 12.94L58.46 12.94Q59.15 12.94 59.43 12.73Q59.71 12.52 59.71 12.24L59.71 12.24Q59.71 11.96 59.49 11.81Q59.26 11.66 58.86 11.52L58.86 11.52ZM63.15 10.44L63.15 10.44Q63.15 9.35 63.50 8.51Q63.84 7.67 64.43 7.11Q65.03 6.54 65.83 6.24Q66.64 5.95 67.58 5.95L67.58 5.95Q68.52 5.95 69.32 6.24Q70.13 6.54 70.73 7.11Q71.33 7.67 71.67 8.51Q72.02 9.35 72.02 10.44L72.02 10.44Q72.02 11.82 71.51 12.78Q71.01 13.73 70.14 14.28L70.14 14.28Q70.35 14.38 70.64 14.49Q70.92 14.60 71.24 14.71Q71.55 14.83 71.89 14.93Q72.23 15.04 72.52 15.12L72.52 15.12Q72.53 15.20 72.53 15.27Q72.53 15.33 72.53 15.39L72.53 15.39Q72.53 16.18 72.12 16.55Q71.71 16.91 71.04 16.91L71.04 16.91Q70.39 16.91 69.71 16.55Q69.02 16.20 68.31 15.53L68.31 15.53L67.69 14.95L67.58 14.95Q66.63 14.95 65.81 14.65Q65.00 14.35 64.41 13.78Q63.83 13.20 63.49 12.36Q63.15 11.52 63.15 10.44ZM65.67 10.44L65.67 10.44Q65.67 11.72 66.19 12.35Q66.71 12.98 67.58 12.98L67.58 12.98Q68.46 12.98 68.98 12.35Q69.50 11.72 69.50 10.44L69.50 10.44Q69.50 9.18 68.98 8.55Q68.47 7.92 67.59 7.92L67.59 7.92Q66.72 7.92 66.20 8.55Q65.67 9.17 65.67 10.44ZM78.50 14.74L74.75 14.74Q74.14 14.74 73.79 14.39Q73.44 14.04 73.44 13.44L73.44 13.44L73.44 6.20Q73.60 6.17 73.93 6.13Q74.27 6.08 74.58 6.08L74.58 6.08Q74.90 6.08 75.14 6.13Q75.39 6.17 75.56 6.30Q75.73 6.43 75.81 6.65Q75.89 6.87 75.89 7.24L75.89 7.24L75.89 12.77L79.10 12.77Q79.20 12.92 79.28 13.18Q79.37 13.44 79.37 13.72L79.37 13.72Q79.37 14.28 79.13 14.51Q78.89 14.74 78.50 14.74L78.50 14.74Z"></path>
                        </g>
                        <defs>
                            <linearGradient gradientTransform="rotate(25)" id="53a95de8-6b3f-4fc9-92b9-c22f7db9a367" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color: rgb(0, 118, 221); stop-opacity: 1;"></stop><stop offset="100%" style="stop-color: rgb(230, 0, 233); stop-opacity: 1;"></stop>
                            </linearGradient>
                        </defs>
                        <g id="31e86ee5-920b-45d8-97dc-f22b4e6566c3" transform="matrix(1.0068354288736978,0,0,1.0068354288736978,-6.878253390009192,-4.649368519998575)" stroke="none" fill="url(#53a95de8-6b3f-4fc9-92b9-c22f7db9a367)">
                            <path d="M42.958 31.593a20.585 20.585 0 019.424-2.274s4.346-.054 7.468 1.86c3.12 1.914 6.514 4.885 6.514 4.885 4.967 5.014 13.082 4.99 18.073 0 4.99-4.99 4.99-13.082 0-18.073l-.018-.028c-8.2-8.2-19.526-13.27-32.037-13.27A45.176 45.176 0 007.075 49.708c-.158 25.157 20.187 45.6 45.307 45.6 2.5 0 4.953-.207 7.344-.596-9.283.644-18.746-1.912-26.595-7.67 13.11 6.763 29.612 4.652 40.601-6.337 7.767-7.767 11.098-18.29 9.996-28.421.004 7.349-2.798 14.7-8.405 20.306-11.206 11.207-29.376 11.207-40.582 0a28.692 28.692 0 01-5.03-6.775c10.19 8.98 25.466 9.031 35.742.204a20.815 20.815 0 01-3.648 2.388 20.585 20.585 0 01-9.423 2.274c-3.396 0-6.597-.824-9.424-2.274C36.276 64.98 31.701 58.027 31.701 50s4.575-14.98 11.257-18.407z"></path>
                            <path d="M65.453 66.019c.419-.341.826-.695 1.216-1.068-.392.379-.807.717-1.216 1.068z"></path>
                        </g>
                    </svg>
                </div>
            </div>
        </div>         

        <!-- Content -->
        <div class="relative py-5">
            <div class="relative w-full max-w-5xl mx-auto px-8">
                <div class="mb-5 font-baloo">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Setup</h1>
                    <p class="text-gray-600 dark:text-white">Welcome to the setup wizard for Website SQL v<?= $this->e($this->getStrings()->getVersion()); ?>. Please follow the instructions below to complete the setup.</p>
                </div>
                <form action="<?= $this->getRoute('app.setup'); ?>" method="post" class="mt-5 font-baloo">
                    <?php if ($error) { ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-5" role="alert">
                            <div class="flex gap-3">
                                <strong class="font-bold">Error:</strong>
                                <span class="block sm:inline"><?= $error; ?></span>
                                <?php if ($error_details) { ?>
                                    <button type="button" class="underline" onclick="toggleViewDetails();">
                                        View Details
                                    </button>
                                <?php } ?>
                            </div>
                            <?php if ($error_details) { ?>
                                <div class="mt-3" style="display: none;" id="error_details">
                                    <p class="text-sm text-red-700 dark:text-red-200"><?= $error_details; ?></p>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 p-10 mb-5">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Step 1: Database Configuration</h2>
                        <p class="text-gray-600 dark:text-white">Please enter your database connection details below.</p>
                        <div class="grid grid-cols-1 gap-6 mt-5">
                            <div>
                                <label for="host" class="block text-sm font-medium text-gray-700 dark:text-white">Host</label>
                                <input type="text" name="host" value="<?= $this->e($_POST['host'] ?? ''); ?>" id="host" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="database" class="block text-sm font-medium text-gray-700 dark:text-white">Database</label>
                                <input type="text" name="database" value="<?= $this->e($_POST['database'] ?? ''); ?>" id="database" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-white">Username</label>
                                <input type="text" name="username" value="<?= $this->e($_POST['username'] ?? ''); ?>" id="username" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-white">Password</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 p-10 mb-5">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Step 2: Admin User Configuration</h2>
                        <p class="text-gray-600 dark:text-white">Please enter your admin user details below.</p>
                        <div class="grid grid-cols-1 gap-6 mt-5">
                            <div>
                                <label for="admin_email" class="block text-sm font-medium text-gray-700 dark:text-white">Email</label>
                                <input type="email" name="admin_email" id="admin_email" value="<?= $this->e($_POST['admin_email'] ?? ''); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="admin_password" class="block text-sm font-medium text-gray-700 dark:text-white">Password</label>
                                <input type="password" name="admin_password" id="admin_password" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="admin_repeat_password" class="block text-sm font-medium text-gray-700 dark:text-white">Repeat Password</label>
                                <input type="password" name="admin_repeat_password" id="admin_repeat_password" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-200 p-10 mb-5">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Step 3: Install</h2>
                        <p class="text-gray-600 dark:text-white">Please click the button below to install Website SQL. This will create the .env file, install the database, and create the first admin user.</p>
                        <div class="mt-5">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                            <button type="submit" name="doSetup" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Install</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="relative py-5">
            <div class="relative w-full max-w-5xl mx-auto px-8">
                <div class="flex justify-between font-baloo text-gray-600 dark:text-white">
                    <p>&copy; Copyright Alan Tiller 2018-<?php echo date('Y'); ?>. All rights reserved.</p>
                    <p>Website SQL v<?= $this->e($this->getStrings()->getVersion()); ?></p>
                </div>
            </div>
        </div>
    </body>
</html>