<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
                <div class="grid gap-6 px-6 py-8 lg:grid-cols-[1.35fr,0.65fr] lg:px-8">
                    <div class="space-y-4">
                        <span class="inline-flex items-center rounded-full bg-teal-50 px-3 py-1 text-sm font-semibold text-teal-700">
                            Zeno Cars Workspace
                        </span>
                        <div class="space-y-2">
                            <h1 class="text-3xl font-semibold tracking-tight text-stone-900">
                                Signed in successfully.
                            </h1>
                            <p class="max-w-2xl text-sm leading-6 text-stone-600">
                                Your session is active. Use your available workspace links to continue with fleet operations or account management.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @if (auth()->user()?->isAdmin())
                                <a
                                    class="inline-flex items-center rounded-full bg-teal-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800"
                                    href="{{ route('admin.dashboard') }}"
                                >
                                    Open Admin Dashboard
                                </a>
                            @endif
                            <a
                                class="inline-flex items-center rounded-full border border-stone-300 px-5 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50"
                                href="{{ route('profile') }}"
                            >
                                View Profile
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                        <div class="rounded-2xl border border-stone-200 bg-stone-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Access</p>
                            <p class="mt-2 text-sm text-stone-700">
                                {{ auth()->user()?->isAdmin() ? 'Authenticated session is active and ready for admin operations.' : 'Authenticated session is active for staff-level access.' }}
                            </p>
                        </div>
                        <div class="rounded-2xl border border-stone-200 bg-amber-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">Next Step</p>
                            <p class="mt-2 text-sm text-amber-800">
                                {{ auth()->user()?->isAdmin() ? 'Go to the admin dashboard to continue managing fleet and booking workflows.' : 'Open your profile or contact an administrator if you need elevated access.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
