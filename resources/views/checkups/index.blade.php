<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-medium text-emerald-700">Rekam klinik</p>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Riwayat pemeriksaan kesehatan</h2>
            </div>
            <a href="{{ route('checkups.create') }}" class="inline-flex items-center rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">Input pemeriksaan</a>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-9rem)] bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Nama karyawan</th>
                                <th class="px-6 py-3">Poli</th>
                                <th class="px-6 py-3">Diagnosis</th>
                                <th class="px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($checkups as $checkup)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $checkup->checked_on->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $checkup->employee?->name ?? 'Karyawan tidak tersedia' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $checkup->clinic ?? '-' }}</td>
                                    <td class="max-w-sm truncate px-6 py-4 text-sm text-gray-600">{{ $checkup->diagnosis }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('checkups.show', $checkup) }}" class="text-sm font-semibold text-blue-700 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada riwayat pemeriksaan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $checkups->links() }}
        </div>
    </div>
</x-app-layout>