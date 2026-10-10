<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-emerald-700">Detail pemeriksaan</p>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ $checkup->employee?->name ?? 'Karyawan' }}</h2>
            </div>
            <a href="{{ route('checkups.index') }}" class="text-sm font-semibold text-blue-700 hover:text-blue-900">Kembali ke riwayat</a>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-9rem)] bg-slate-50 py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <section class="divide-y divide-gray-100 rounded-xl border border-gray-200 bg-white px-6 shadow-sm sm:px-8">
                <div class="grid gap-5 py-6 sm:grid-cols-2">
                    <div><p class="text-xs font-semibold uppercase text-gray-500">Tanggal pemeriksaan</p><p class="mt-1 text-sm text-gray-900">{{ $checkup->checked_on->format('d M Y') }}</p></div>
                    <div><p class="text-xs font-semibold uppercase text-gray-500">Poli</p><p class="mt-1 text-sm text-gray-900">{{ $checkup->clinic ?? '-' }}</p></div>
                    <div class="sm:col-span-2"><p class="text-xs font-semibold uppercase text-gray-500">Diagnosis</p><p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $checkup->diagnosis }}</p></div>
                    <div class="sm:col-span-2"><p class="text-xs font-semibold uppercase text-gray-500">Tindakan / pengobatan</p><p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $checkup->treatment }}</p></div>
                    <div class="sm:col-span-2"><p class="text-xs font-semibold uppercase text-gray-500">Catatan medis</p><p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $checkup->notes ?: '-' }}</p></div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>