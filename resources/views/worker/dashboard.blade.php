<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Worker Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">My Tasks</h3>
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($penugasans ?? [] as $tugas)
                            <div class="border rounded p-4 bg-gray-50">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-bold text-blue-600">Order #{{ $tugas->pesanan->id }}</h4>
                                    <span class="text-xs bg-yellow-200 px-2 py-1 rounded">{{ $tugas->status_tugas }}</span>
                                </div>
                                <ul class="text-sm text-gray-700 mb-4 list-disc list-inside">
                                    @foreach($tugas->pesanan->detailPesanans as $detail)
                                        <li>{{ $detail->produk->nama_produk }} (x{{ $detail->jumlah }})</li>
                                    @endforeach
                                </ul>
                                
                                @if($tugas->status_tugas != 'Selesai')
                                <form method="POST" action="{{ route('worker.updateProgress', $tugas->id) }}" class="mt-4 border-t pt-4">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="block text-sm mb-1">Progress Update</label>
                                        <textarea name="keterangan_progres" class="w-full rounded border-gray-300 text-sm" required></textarea>
                                    </div>
                                    <div class="flex gap-4 items-center">
                                        <select name="status" class="text-sm rounded border-gray-300">
                                            <option value="Diproses">Diproses</option>
                                            <option value="Selesai">Selesai</option>
                                        </select>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm w-full">Update Progress</button>
                                    </div>
                                </form>
                                @endif
                                
                                @if($tugas->progres->count() > 0)
                                <div class="mt-4 pt-4 border-t">
                                    <h5 class="text-sm font-bold mb-2">History:</h5>
                                    @foreach($tugas->progres as $prog)
                                        <div class="text-xs text-gray-600 mb-1">- {{ $prog->keterangan_progres }} ({{ $prog->tanggal_update }})</div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
