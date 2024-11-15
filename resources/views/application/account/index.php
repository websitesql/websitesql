<?php $this->layout('layout::application', ['title' => $title]); ?>

<div class="relative py-5">
    <div class="relative w-full max-w-5xl mx-auto px-8 text-gray-800 dark:text-white">
        <!-- Title -->
        <div class="font-baloo py-6">
            <h1 class="font-extrabold text-4xl">Account</h1>
            <p class="font-light leading-4">Here you can manage your account settings, view your account information, configure MFA and reset your password.</p>
        </div>

        <!-- Action buttons -->
        <div class="flex justify-between items-center h-full mb-10">
            <div class="flex items-center gap-3">
                <a href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=dashboard&layout=old" class="flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer">
                    <i class="fas fa-shield"></i>
                    <span>Multi-factor authentication</span>
                </a>
                <a href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=dashboard&layout=old" class="flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer">
                    <i class="fas fa-key"></i>
                    <span>Reset password</span>
                </a>
            </div>
            <div class="flex items-center gap-3">
                
            </div>
        </div>

        <!-- Grid-based dashboard -->
        <div class="grid grid-cols-1 sm:grid-cols-4 md:grid-cols-8 lg:grid-cols-12 gap-5">
            <!-- Account information -->
            <div class="col-span-1 sm:col-span-4 md:col-span-4 lg:col-span-4">
                <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-xl p-5">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-circle text-3xl text-gray-700 dark:text-white"></i>
                        <div>
                            <h3 class="font-baloo text-lg font-bold">Account information</h3>
                            <p class="font-light text-sm">View and edit your account information.</p>
                        </div>
                    </div>
                    <div class="mt-5">
                        <a href="/<?php echo $this->getStrings()->getAdminFilePath(); ?>?page=account&action=edit" class="flex items-center gap-2 h-10 py-2 px-5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-white border border-gray-300 dark:border-white text-base font-baloo font-medium leading-4 rounded-xl shadow-sm transition-all duration-100 cursor-pointer">
                            <i class="fas fa-edit"></i>
                            <span>Edit account information</span> 
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="overflow-hidden shadow ring-1 ring-gray-600 ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-base font-semibold text-gray-900 sm:pl-6 font-baloo font-regular">Asset Tag ID</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-base font-semibold text-gray-900 font-baloo font-regular">Description</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-base font-semibold text-gray-900 font-baloo font-regular">Brand</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-base font-semibold text-gray-900 font-baloo font-regular">Purchase Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-base font-semibold text-gray-900 font-baloo font-regular">Cost</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-base font-semibold text-gray-900 font-baloo font-regular">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="asset in assets" :key="asset.id">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-base font-medium text-gray-900 sm:pl-6 font-baloo font-regular">{{ asset.asset_tag_id }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-base text-gray-700 font-baloo font-regular">{{ asset.description }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-base text-gray-700 font-baloo font-regular">{{ asset.brand }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-base text-gray-700 font-baloo font-regular">{{ formattedDate(asset.purchase_date) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-base text-gray-700 font-baloo font-regular">{{ formattedCurrency(asset.cost) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-base text-gray-700 font-baloo font-regular" v-html="formattedAssetStatus(asset.status)"></td>
                                <td class="relative whitespace-nowrap px-3 py-4 text-right text-base font-baloo sm:pr-6">
                                    <button type="button" @click="importFilesPopup = true" class="flex items-center gap-2 h-10 py-2 px-5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 text-base font-baloo font-medium leading-4 rounded-2xl shadow-sm transition-all duration-100">
                                        <Eye class="h-4 fill-gray-700"/>
                                        <span class="inline-block mt-0.5">
                                            View
                                        </span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
	</div>			
</div>