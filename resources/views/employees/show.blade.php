<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-blue-700">Profil karyawan</p>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ $employee->name }}</h2>
            </div>
            <a href="{{ route('employees.index') }}" class="text-sm font-semibold text-blue-700 hover:text-blue-900">Kembali ke daftar</a>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-9rem)] bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl space-y-7 px-4 sm:px-6 lg:px-8">
            <section>
                <div class="mb-3 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase text-gray-500">Informasi karyawan</p>
                        <h3 class="mt-1 text-lg font-semibold text-gray-900">Data perusahaan</h3>
                    </div>
                </div>
                <div class="grid gap-px overflow-hidden rounded-lg border border-gray-200 bg-gray-200 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white p-5"><p class="text-xs font-semibold uppercase text-gray-500">Perusahaan</p><p class="mt-2 text-sm font-medium text-gray-900">{{ $employee->company_name ?? '-' }}</p></div>
                    <div class="bg-white p-5"><p class="text-xs font-semibold uppercase text-gray-500">Nomor karyawan</p><p class="mt-2 text-sm font-medium text-gray-900">{{ $employee->employee_number ?? '-' }}</p></div>
                    <div class="bg-white p-5"><p class="text-xs font-semibold uppercase text-gray-500">Bagian</p><p class="mt-2 text-sm font-medium text-gray-900">{{ $employee->department ?? '-' }}</p></div>
                    <div class="bg-white p-5"><p class="text-xs font-semibold uppercase text-gray-500">Umur</p><p class="mt-2 text-sm font-medium text-gray-900">{{ $employee->age ? $employee->age.' tahun' : '-' }}</p></div>
                </div>
            </section>

            <section>
                <div class="mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Ringkasan klinis</p>
                    <h3 class="mt-1 text-lg font-semibold text-gray-900">Rekap medis pasien</h3>
                </div>
                @php($latestCheckup = $employee->healthCheckups->first())
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-lg border border-gray-200 border-l-4 border-l-emerald-500 bg-white p-5">
                        <p class="text-xs font-semibold uppercase text-gray-500">Pemeriksaan terakhir</p>
                        <p class="mt-2 text-sm font-semibold text-gray-900">{{ $latestCheckup?->checked_on->format('d M Y') ?? 'Belum ada pemeriksaan' }}</p>
                        @if ($latestCheckup)
                            <p class="mt-1 text-sm text-gray-600">{{ $latestCheckup->diagnosis }}</p>
                        @endif
                    </div>
                    <div class="rounded-lg border border-gray-200 border-l-4 border-l-rose-500 bg-white p-5">
                        <p class="text-xs font-semibold uppercase text-gray-500">Laporan kecelakaan</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">{{ $employee->workAccidents->count() }}</p>
                        <p class="mt-1 text-sm text-gray-600">Total laporan tercatat</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 border-l-4 border-l-blue-500 bg-white p-5">
                        <p class="text-xs font-semibold uppercase text-gray-500">Catatan pengobatan</p>
                        <p class="mt-2 text-sm leading-6 text-gray-700">{{ $latestCheckup?->treatment ?? 'Belum ada catatan pengobatan.' }}</p>
                    </div>
                </div>
            </section>

            <section>
                <div class="mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Riwayat pasien</p>
                    <h3 class="mt-1 text-lg font-semibold text-gray-900">Riwayat pemeriksaan</h3>
                </div>
                <div class="divide-y divide-gray-100 rounded-lg border border-gray-200 bg-white">
                    @forelse ($employee->healthCheckups as $checkup)
                        <a href="{{ route('checkups.show', $checkup) }}" class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 hover:bg-gray-50">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $checkup->checked_on->format('d M Y') }} · {{ $checkup->clinic ?? 'Pemeriksaan' }}</p>
                                <p class="mt-1 text-sm text-gray-600">{{ $checkup->diagnosis }}</p>
                            </div>
                            <span class="text-sm font-semibold text-blue-700">View</span>
                        </a>
                    @empty
                        <p class="px-5 py-8 text-center text-sm text-gray-500">Belum ada riwayat pemeriksaan.</p>
                    @endforelse
                </div>
            </section>

            <section>
                <div class="mb-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Keselamatan kerja</p>
                    <h3 class="mt-1 text-lg font-semibold text-gray-900">Riwayat kecelakaan</h3>
                </div>
                <div class="divide-y divide-gray-100 rounded-lg border border-gray-200 bg-white">
                    @forelse ($employee->workAccidents as $accident)
                        <article class="px-5 py-4">
                            <div class="flex flex-wrap justify-between gap-2">
                                <p class="text-sm font-semibold text-gray-900">{{ $accident->occurred_on->format('d M Y') }} · {{ $accident->injured_body_part }}</p>
                                <span class="text-sm text-gray-500">{{ $accident->company?->name ?? '-' }} · {{ $accident->department }}</span>
                            </div>
                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600">{{ $accident->chronology }}</p>
                            <p class="mt-2 text-sm text-gray-700"><span class="font-medium">Tindakan:</span> {{ $accident->action_taken }}</p>
                            <p class="mt-1 text-sm text-gray-700"><span class="font-medium">Therapy:</span> {{ $accident->therapy }}</p>
                        </article>
                    @empty
                        <p class="px-5 py-8 text-center text-sm text-gray-500">Belum ada laporan kecelakaan yang terhubung dengan karyawan ini.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>