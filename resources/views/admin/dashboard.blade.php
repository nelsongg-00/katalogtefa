<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Jurusan Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Manage Orders</h3>
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="p-3">Order ID</th>
                                    <th class="p-3">Client</th>
                                    <th class="p-3">Products</th>
                                    <th class="p-3">Total</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanans ?? [] as $pesanan)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">#{{ $pesanan->id }}</td>
                                    <td class="p-3">{{ $pesanan->user->name }}</td>
                                    <td class="p-3">
                                        @foreach($pesanan->detailPesanans as $detail)
                                            {{ $detail->produk->nama_produk }} (x{{ $detail->jumlah }})<br>
                                        @endforeach
                                    </td>
                                    <td class="p-3">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 text-sm rounded bg-blue-100 text-blue-800">{{ $pesanan->status_pesanan }}</span>
                                    </td>
                                    <td class="p-3">
                                        @if($pesanan->status_pesanan == 'Pending')
                                            <form method="POST" action="{{ route('admin.validateOrder', $pesanan->id) }}">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">Validate</button>
                                            </form>
                                        @elseif($pesanan->status_pesanan == 'Validated')
                                            <form method="POST" action="{{ route('admin.assignTask', $pesanan->id) }}" class="flex gap-2">
                                                @csrf
                                                <select name="worker_id" class="text-sm rounded border-gray-300" required>
                                                    <option value="">Select Worker</option>
                                                    @foreach($workers as $worker)
                                                        <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Assign</button>
                                            </form>
                                        @endif
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
