<?php

// app/Traits/Exportable.php

namespace App\Traits;

use App\Services\ExportService;

trait Exportable
{
    public function exportToTxtFinal($jsonFilePath)
    {
        $jsonContent = json_decode(file_get_contents($jsonFilePath), true);

        $txtContent = ExportService::exportToJsonTxt($jsonContent);

        // Guardar el archivo TXT
        $txtFilePath = storage_path('app/exportTxt.txt');
        file_put_contents($txtFilePath, $txtContent);

        // Descargar el archivo
        return response()->download($txtFilePath)->deleteFileAfterSend(true);
    }
}