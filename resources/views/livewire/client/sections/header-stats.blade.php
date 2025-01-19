<!-- header-stats.blade.php -->
<div>
    <div class="tw-flex tw-flex-col sm:tw-flex-row tw-justify-between tw-items-start sm:tw-items-center tw-mb-4">
        <div>
            <h1 class="tw-text-2xl tw-font-normal tw-text-gray-900 dark:tw-text-white">Mon espace de travail</h1>
            <p class="tw-mt-2 tw-text-base tw-text-gray-500 dark:tw-text-gray-400">Gérez vos instances et abonnements</p>
        </div>
    </div>

    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-6">
        <!-- Instances actives -->
        <div class="tw-bg-white dark:tw-bg-gray-800 tw-rounded-2xl tw-border tw-border-gray-100 dark:tw-border-gray-700 tw-p-6 hover:tw-shadow-sm tw-transition-all tw-duration-200">
            <div class="tw-flex tw-items-center tw-gap-4">
                <div class="tw-p-4 tw-bg-blue-50 dark:tw-bg-blue-900/20 tw-rounded-xl">
                    <svg class="tw-w-7 tw-h-7 tw-text-blue-600 dark:tw-text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">Instances actives</p>
                    <p class="tw-mt-1 tw-text-2xl tw-font-normal tw-text-gray-900 dark:tw-text-white">
                        {{ $instances->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Plan actuel -->
        <div class="tw-bg-white dark:tw-bg-gray-800 tw-rounded-2xl tw-border tw-border-gray-100 dark:tw-border-gray-700 tw-p-6 hover:tw-shadow-sm tw-transition-all tw-duration-200">
            <div class="tw-flex tw-items-center tw-gap-4">
                <div class="tw-p-4 tw-bg-green-50 dark:tw-bg-green-900/20 tw-rounded-xl">
                    <svg class="tw-w-7 tw-h-7 tw-text-green-600 dark:tw-text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">Plan actuel</p>
                    <p class="tw-mt-1 tw-text-2xl tw-font-normal tw-text-gray-900 dark:tw-text-white">
                        {{ $currentPlan ? $currentPlan->name : 'Gratuit' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Expiration -->
        <div class="tw-bg-white dark:tw-bg-gray-800 tw-rounded-2xl tw-border tw-border-gray-100 dark:tw-border-gray-700 tw-p-6 hover:tw-shadow-sm tw-transition-all tw-duration-200">
            <div class="tw-flex tw-items-center tw-gap-4">
                <div class="tw-p-4 tw-bg-purple-50 dark:tw-bg-purple-900/20 tw-rounded-xl">
                    <svg class="tw-w-7 tw-h-7 tw-text-purple-600 dark:tw-text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="tw-text-sm tw-text-gray-500 dark:tw-text-gray-400">Expiration</p>
                    <p class="tw-mt-1 tw-text-2xl tw-font-normal tw-text-gray-900 dark:tw-text-white">
                        {{ $this->getFormattedEndDate() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
