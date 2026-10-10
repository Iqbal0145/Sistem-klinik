<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600">Administrasi</p>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Perusahaan</h2>
            </div>
            <p class="text-sm text-gray-500">Kelola daftar perusahaan yang terdaftar</p>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-9rem)] bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Tambah perusahaan</h3>
                <form method="POST" action="{{ route('companies.store') }}" class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-end">
                    @csrf
                    <div class="w-full sm:max-w-xl">
                        <x-input-label for="name" value="Nama perusahaan" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required maxlength="255" autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <x-primary-button>Tambah perusahaan</x-primary-button>
                </form>
                @if (session('status'))
                    <p class="mt-4 text-sm font-medium text-emerald-700">{{ session('status') }}</p>
                @endif
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar perusahaan</h3>
                </div>
                @if ($companies->isEmpty())
                    <p class="px-6 py-8 text-sm text-gray-500">Belum ada perusahaan yang terdaftar.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Nama perusahaan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($companies as $company)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $company->id }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $company->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
