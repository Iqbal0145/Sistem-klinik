<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-emerald-700">Rekam klinik</p>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Input pemeriksaan kesehatan</h2>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-9rem)] bg-slate-50 py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('checkups.store') }}" class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2" x-data="{
                        employees: @js($employees->map(fn ($employee) => [
                            'id' => (string) $employee->id,
                            'name' => $employee->name,
                            'company' => $employee->company_name ?: 'Perusahaan belum diisi',
                            'hasHistory' => $employee->health_checkups_count > 0,
                        ])->values()),
                        query: '',
                        selectedEmployeeId: @js((string) old('employee_id', '')),
                        isOpen: false,
                        get filteredEmployees() {
                            const search = this.query.trim().toLocaleLowerCase();

                            if (!search) {
                                return [];
                            }

                            return this.employees.filter((employee) =>
                                `${employee.name} ${employee.company}`.toLocaleLowerCase().includes(search)
                            ).slice(0, 8);
                        },
                        selectEmployee(employee) {
                            this.query = employee.name;
                            this.selectedEmployeeId = employee.id;
                            this.isOpen = false;
                        }
                    }" x-init="const selected = employees.find((employee) => employee.id === selectedEmployeeId); if (selected) query = selected.name" @click.outside="isOpen = false">
                        <x-input-label for="employee-search" value="Nama karyawan" />
                        <input id="employee-search" type="text" x-model="query" @input="selectedEmployeeId = ''; isOpen = true" @focus="isOpen = true" autocomplete="off" required placeholder="Ketik nama karyawan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <input type="hidden" name="employee_id" :value="selectedEmployeeId">
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                        <p class="mt-2 text-xs text-gray-500">Karyawan tanpa riwayat tetap dapat dipilih untuk pemeriksaan pertama.</p>
                        <div x-cloak x-show="isOpen && query.trim().length > 0" class="relative">
                            <ul class="absolute z-10 mt-1 max-h-64 w-full overflow-y-auto rounded-md border border-gray-200 bg-white py-1 shadow-lg">
                                <template x-for="employee in filteredEmployees" :key="employee.id">
                                    <li>
                                        <button type="button" @click="selectEmployee(employee)" class="flex w-full items-start justify-between gap-4 px-4 py-3 text-left hover:bg-blue-50">
                                            <span>
                                                <span class="block text-sm font-medium text-gray-900" x-text="employee.name"></span>
                                                <span class="mt-1 block text-xs text-gray-500" x-text="employee.company"></span>
                                            </span>
                                            <span class="shrink-0 text-xs font-medium text-amber-700" x-show="!employee.hasHistory">Belum ada riwayat</span>
                                        </button>
                                    </li>
                                </template>
                                <li x-show="filteredEmployees.length === 0" class="px-4 py-3 text-sm text-gray-500">Tidak ada karyawan yang cocok.</li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <x-input-label for="checked_on" value="Tanggal pemeriksaan" />
                        <x-text-input id="checked_on" name="checked_on" type="date" max="{{ now()->toDateString() }}" class="mt-1 block w-full" :value="old('checked_on', now()->toDateString())" required />
                        <x-input-error :messages="$errors->get('checked_on')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="clinic" value="Poli" />
                        <x-text-input id="clinic" name="clinic" type="text" class="mt-1 block w-full" :value="old('clinic')" />
                        <x-input-error :messages="$errors->get('clinic')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="diagnosis" value="Diagnosis" />
                        <textarea id="diagnosis" name="diagnosis" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('diagnosis') }}</textarea>
                        <x-input-error :messages="$errors->get('diagnosis')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="treatment" value="Tindakan/pengobatan" />
                        <textarea id="treatment" name="treatment" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('treatment') }}</textarea>
                        <x-input-error :messages="$errors->get('treatment')" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="notes" value="Catatan" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('checkups.index') }}" class="rounded-md px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</a>
                    <x-primary-button>Simpan pemeriksaan</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
<div>
    <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
</div>
