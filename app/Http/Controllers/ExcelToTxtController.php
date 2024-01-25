<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelToTxtController extends Controller
{
    public function convert(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('excel_file');
        $collection = Excel::toArray([], $file);

        $txtContent = $this->convertToTabDelimitedTxt($collection[0]);

        // Guardar el archivo TXT
        $txtFilePath = storage_path('app/exportTxt.txt');
        file_put_contents($txtFilePath, $txtContent);

        // Descargar el archivo
        return response()->download($txtFilePath)->deleteFileAfterSend(true);
    }

    private function convertToTabDelimitedTxt($collection)
    {
        $txtContent = '';

        foreach ($collection as $row) {
            $txtContent .= implode("\t", $row);
            $txtContent .= "\n";
        }

        return $txtContent;
    }
}