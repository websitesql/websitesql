<?php $this->layout('layout::application', ['title' => $title]); ?>

<div class="relative py-5">
    <div class="relative w-full max-w-5xl mx-auto px-8 text-gray-800 dark:text-white">
        <!-- Title -->
        <div class="font-baloo pt-6 mb-8">
            <h2 class="font-semibold text-lg"><?= $this->e($title); ?></h2>
            <h1 class="font-extrabold text-4xl"><?= $this->e($subtitle); ?></h1>
            <p class="font-light leading-4"><?= $this->e($description); ?></p>
        </div>

        <!-- Content -->
        <div class="font-baloo mb-6">
            <div class="mb-10">
                <h2 class="font-semibold text-2xl">Users Details</h2>
                <p class="font-light leading-4">Update the details of the role.</p>
                <div class="mt-2 mb-5 mx-auto block h-0 w-full no-underline border-b border-gray-300 overflow-hidden"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                    <div class="flex gap-3">
                        <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 80 80" class="fill-black dark:fill-white animate-spin-fast">
                            <g transform="translate(0.000000,85.000000) scale(0.100000,-0.100000)" stroke="none">
                                <path d="M385 823 c125 -14 222 -79 272 -181 24 -49 28 -69 28 -143 0 -82 -2 -91 -44 -172 -48 -96 -49 -123 -5 -151 37 -25 77 -13 108 33 104 149 83 369 -47 503 -73 75 -179 119 -281 117 -34 -1 -47 -4 -31 -6z"/>
                                <path d="M96 670 c-53 -63 -79 -148 -78 -250 2 -109 45 -203 130 -282 60 -56 123 -86 208 -100 69 -10 121 -6 69 6 -103 24 -136 39 -184 84 -73 69 -96 125 -96 239 0 88 2 94 43 173 23 45 42 88 42 96 0 8 -11 26 -25 39 -34 35 -76 33 -109 -5z"/>
                            </g>
                        </svg>
                        <h2 class="text-lg font-baloo text-neutral-800 dark:text-white leading-6">Loading data - please wait...</h2>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>