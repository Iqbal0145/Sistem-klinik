<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600">Sistem Klinik Perusahaan</p>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('Dashboard') }}</h2>
            </div>
            <p class="text-sm text-gray-500">Ringkasan aktivitas klinik hari ini</p>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-9rem)] bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl bg-gradient-to-r from-blue-700 to-cyan-600 p-6 text-white shadow-lg sm:p-8">
                <p class="text-sm font-medium text-blue-100">Selamat datang kembali</p>
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Halo, {{ Auth::user()->name }}!</h1>
                <p class="mt-2 max-w-2xl text-sm text-blue-50 sm:text-base">
                    Sistem Pemantauan layanan kesehatan karyawan dan aktivitas klinik.
                </p>
                @if (Auth::user()->isSuperAdmin())
                    <a href="{{ route('companies.index') }}" class="mt-5 inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-50">
                        Kelola perusahaan
                    </a>
                @endif
            </section>

            <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Kunjungan hari ini</p>
                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ $todayVisits }}</p>
                    <p class="mt-2 text-sm text-gray-500">Data masuk pukul 00:01–23:59</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Menunggu pemeriksaan</p>
                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ $waitingForCheckup }}</p>
                    <p class="mt-2 text-sm text-amber-600">Pengunjung hadir dan sedang diperiksa</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Total karyawan</p>
                    <p class="mt-3 text-3xl font-bold text-gray-900">{{ $totalEmployees }}</p>
                    <p class="mt-2 text-sm text-gray-500">Seluruh karyawan perusahaan</p>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Aktivitas terbaru</h3>
                        <p class="mt-1 text-sm text-gray-500">Pembaruan terakhir dari sistem klinik</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Online</span>
                </div>
                <div class="mt-6 divide-y divide-gray-100">
                    <div class="flex items-center justify-between py-4 first:pt-0">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pemeriksaan kesehatan selesai</p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $latestCheckup?->clinic ?? 'Belum ada data pemeriksaan' }}
                                @if ($latestCheckup)
                                    · {{ $latestCheckup->checked_on->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ $latestCheckup ? route('checkups.show', $latestCheckup) : route('checkups.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">View</a>
                    </div>
                    <div class="flex items-center justify-between py-4 last:pb-0">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Data karyawan diperbarui</p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $latestEmployee?->name ?? 'Belum ada data karyawan' }}
                                @if ($latestEmployee)
                                    · {{ $latestEmployee->updated_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('employees.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">View</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
