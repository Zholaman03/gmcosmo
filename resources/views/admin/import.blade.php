 @extends('admin.dashboard')

 @section('content')
 
 @if(session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 text-sm rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-100 border border-red-400 text-red-700 text-sm rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Import Card -->
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm">
                <h2 class="text-lg font-bold text-gray-800 mb-3">Import Products</h2>
                <form action="/import-products" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                        Import
                    </button>
                </form>
            </div>
@endsection