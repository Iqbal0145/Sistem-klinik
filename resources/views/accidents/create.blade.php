<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-rose-700">Laporan insiden</p>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Input Kecelakaan Kerja</h2>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-9rem)] bg-slate-50 py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('accidents.store') }}" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-input-label for="company_id" value="Perusahaan" />
                        <select id="company_id" name="company_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="" disabled @selected(! old('company_id'))>Pilih perusahaan</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @selected((string) old('company_id') === (string) $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="name" value="Nama karyawan" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="age" value="Umur" />
                        <x-text-input id="age" name="age" type="number" min="1" max="120" class="mt-1 block w-full" :value="old('age')" required />
                        <x-input-error :messages="$errors->get('age')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="department" value="Bagian" />
                        <x-text-input id="department" name="department" type="text" class="mt-1 block w-full" :value="old('department')" required />
                        <x-input-error :messages="$errors->get('department')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="occurred_on" value="Tanggal kejadian" />
                        <x-text-input id="occurred_on" name="occurred_on" type="date" max="{{ now()->toDateString() }}" class="mt-1 block w-full" :value="old('occurred_on')" required />
                        <x-input-error :messages="$errors->get('occurred_on')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="chronology" value="Kronologi kejadian" />
                        <textarea id="chronology" name="chronology" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('chronology') }}</textarea>
                        <x-input-error :messages="$errors->get('chronology')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="injured_body_part" value="Bagian yang terluka" />
                        <x-text-input id="injured_body_part" name="injured_body_part" type="text" class="mt-1 block w-full" :value="old('injured_body_part')" required />
                        <x-input-error :messages="$errors->get('injured_body_part')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="action_taken" value="Tindakan" />
                        <textarea id="action_taken" name="action_taken" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('action_taken') }}</textarea>
                        <x-input-error :messages="$errors->get('action_taken')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="therapy" value="Therapy" />
                        <textarea id="therapy" name="therapy" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('therapy') }}</textarea>
                        <x-input-error :messages="$errors->get('therapy')" class="mt-2" />
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('dashboard') }}" class="rounded-md px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</a>
                    <x-primary-button>Simpan laporan</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>