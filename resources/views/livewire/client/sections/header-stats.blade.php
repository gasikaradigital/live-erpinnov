<!-- partials/header-stats.blade.php -->
<div>
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-8">
        <div>
            <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Mon espace de travail</h1>
            <p class="tw-mt-1 tw-text-gray-600">Gérez vos instances et abonnements</p>
        </div>
        <button 
            class="tw-inline-flex tw-items-center tw-px-4 tw-py-2.5 tw-bg-indigo-600 tw-text-white tw-rounded-lg hover:tw-bg-indigo-700 tw-transition-colors tw-shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#instanceModal">
            <svg class="tw-w-5 tw-h-5 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer une instance
        </button>
    </div>

    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-6">
        <!-- Stats Cards -->
        <div class="tw-bg-white tw-rounded-xl tw-shadow-sm tw-p-6">
            <div class="tw-flex tw-items-center tw-gap-4">
                <div class="tw-p-3 tw-bg-indigo-50 tw-rounded-lg">
                    <svg class="tw-w-6 tw-h-6 tw-text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="tw-text-sm tw-font-medium tw-text-gray-500">Instances actives</p>
                    <p class="tw-mt-1 tw-text-2xl tw-font-semibold tw-text-gray-900">{{ $instances->count() }}</p>
                </div>
            </div>
        </div>

        <div class="tw-bg-white tw-rounded-xl tw-shadow-sm tw-p-6">
            <div class="tw-flex tw-items-center tw-gap-4">
                <div class="tw-p-3 tw-bg-green-50 tw-rounded-lg">
                    <svg class="tw-w-6 tw-h-6 tw-text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="tw-text-sm tw-font-medium tw-text-gray-500">Plan actuel</p>
                    <p class="tw-mt-1 tw-text-2xl tw-font-semibold tw-text-gray-900">
                        {{ $currentPlan ? $currentPlan->name : 'Gratuit' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="tw-bg-white tw-rounded-xl tw-shadow-sm tw-p-6">
            <div class="tw-flex tw-items-center tw-gap-4">
                <div class="tw-p-3 tw-bg-purple-50 tw-rounded-lg">
                    <svg class="tw-w-6 tw-h-6 tw-text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="tw-text-sm tw-font-medium tw-text-gray-500">Expiration</p>
                    <p class="tw-mt-1 tw-text-2xl tw-font-semibold tw-text-gray-900">
                        {{ $this->getFormattedEndDate() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
