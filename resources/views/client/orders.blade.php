<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Order History</h3>
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
                    @endif
                    
                    <div class="space-y-6">
                        @foreach($pesanans ?? [] as $pesanan)
                            <div class="border rounded p-4 {{ $pesanan->status_pesanan == 'Completed' ? 'bg-green-50' : 'bg-gray-50' }}">
                                <div class="flex justify-between items-center mb-2 border-b pb-2">
                                    <div class="font-bold text-gray-800">Order #{{ $pesanan->id }}</div>
                                    <span class="px-2 py-1 text-xs font-bold rounded bg-blue-100 text-blue-800">{{ $pesanan->status_pesanan }}</span>
                                </div>
                                <div class="mb-2">
                                    @foreach($pesanan->detailPesanans as $detail)
                                        <div class="flex justify-between text-sm">
                                            <span>{{ $detail->produk->nama_produk }} (x{{ $detail->jumlah }})</span>
                                            <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex justify-between items-center font-bold text-lg mt-2 pt-2 border-t">
                                    <span>Total:</span>
                                    <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($pesanan->status_pesanan == 'Pending')
                                <div class="mt-4 pt-4 border-t">
                                    <p class="text-sm text-gray-600 mb-2">Please transfer Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }} to BCA 1234567890 and upload your receipt.</p>
                                    <form method="POST" action="#" enctype="multipart/form-data" class="flex gap-2">
                                        @csrf
                                        <input type="file" name="bukti_bayar" class="text-sm rounded border-gray-300 w-full" required>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm whitespace-nowrap">Upload Payment</button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        @endforeach
                        
                        @if(count($pesanans) == 0)
                            <p class="text-gray-500 text-center py-8">You haven't placed any orders yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
