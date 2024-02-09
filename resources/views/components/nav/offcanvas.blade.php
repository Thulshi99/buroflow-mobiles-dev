<div class="fixed inset-0 z-40 flex md:hidden" role="dialog" aria-modal="true">
    <!-- Off-canvas menu overlay, show/hide based on off-canvas menu state. -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true" x-show="open"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <!-- Off-canvas menu, show/hide based on off-canvas menu state. -->
    <div class="relative flex flex-col flex-1 w-full max-w-xs pt-5 pb-4 bg-indigo-700" x-show="open"
        x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
        <!-- Close button, show/hide based on off-canvas menu state. -->
        <div class="absolute top-0 right-0 pt-2 -mr-12" x-transition:enter="ease-in-out duration-300"
            @click="open = false" x-show="open" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <button type="button"
                class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <!-- Heroicon name: outline/x -->
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex items-center flex-shrink px-4 mx-auto">
            <x-jet-application-logo />
        </div>
        <div class="flex-1 h-0 mt-5 overflow-y-auto">
            <x-nav.sidebar />
        </div>
    </div>

    <div class="flex-shrink-0 w-14" aria-hidden="true" @click="open = ! open">
        <!-- Dummy element to force sidebar to shrink to fit close icon -->
    </div>
</div>
