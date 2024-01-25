<x-app-layout>
    <div class="flex items-center justify-center py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl sm:rounded-lg p-6 bg-white">
                <form action="{{ route('excel.to.txt') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="excel_file" class="block text-sm font-medium text-gray-700"></label>
                        <div class="mt-1">
                            <input type="file" name="excel_file" id="excel_file" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Convertir a TXT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>