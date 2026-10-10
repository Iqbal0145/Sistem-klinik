<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-blue-700">Data perusahaan</p>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Karyawan</h2>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-9rem)] bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                            <tr>
                                <th class="px-6 py-3">Nama karyawan</th>
                                <th class="px-6 py-3">Perusahaan</th>
                                <th class="px-6 py-3">Nomor karyawan</th>
                                <th class="px-6 py-3">Bagian</th>
                                <th class="px-6 py-3">Riwayat pemeriksaan</th>
                                <th class="px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($employees as $employee)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $employee->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $employee->company_name ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $employee->employee_number ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $employee->department ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $employee->health_checkups_count }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('employees.show', $employee) }}" class="text-sm font-semibold text-blue-700 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada data karyawan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $employees->links() }}
        </div>
    </div>
</x-app-layout>