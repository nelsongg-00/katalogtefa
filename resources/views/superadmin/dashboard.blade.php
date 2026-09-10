<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Superadmin Global Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500 font-bold uppercase">Total Revenue</div>
                    <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 font-bold uppercase">Total Orders</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500">
                    <div class="text-sm text-gray-500 font-bold uppercase">Total Users</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500 font-bold uppercase">Total Majors</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalJurusans }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">System-Wide Recent Orders</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="p-3">Order ID</th>
                                    <th class="p-3">Client</th>
                                    <th class="p-3">Major (Jurusan)</th>
                                    <th class="p-3">Products</th>
                                    <th class="p-3">Total</th>
                                    <th class="p-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanans as $pesanan)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">#{{ $pesanan->id }}</td>
                                    <td class="p-3">{{ $pesanan->user->name }}</td>
                                    <td class="p-3">
                                        @foreach($pesanan->detailPesanans as $detail)
                                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">{{ $detail->produk->jurusan->nama_jurusan ?? 'N/A' }}</span><br>
                                        @endforeach
                                    </td>
                                    <td class="p-3">
                                        @foreach($pesanan->detailPesanans as $detail)
                                            {{ $detail->produk->nama_produk }}<br>
                                        @endforeach
                                    </td>
                                    <td class="p-3">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 text-sm rounded bg-blue-100 text-blue-800">{{ $pesanan->status_pesanan }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
