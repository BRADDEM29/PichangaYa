<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-950 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')
                <x-section-border class="dark:border-gray-800" />
            @endif

            @if (strtolower(Auth::user()->role) === 'owner')
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.manage-user-phones')
                </div>
                <x-section-border class="dark:border-gray-800" />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>
                <x-section-border class="dark:border-gray-800" />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>
                <x-section-border class="dark:border-gray-800" />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border class="dark:border-gray-800" />
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
<style>
    .dark .bg-white {
        background-color: #1f2937 !important; /* gray-800 */
        color: #f3f4f6 !important; /* gray-100 */
    }
    .dark .border-gray-200 {
        border-color: #374151 !important; /* gray-700 */
    }
    .dark shadow-sm, .dark .shadow {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5) !important;
    }
</style>