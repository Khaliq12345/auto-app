<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as RXlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelController extends Controller
{

    public function showImportForm()
    {
        return view('home'); // Créez un fichier resources/views/import_form.blade.php
    }

    public function importExcel1(Request $request)
    {
        $request->validate([
            'fileInput' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('fileInput');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('uploads', $filename); // Stocker le fichier dans storage/app/uploads

        $spreadsheet = null;
        $extension = $file->getClientOriginalExtension();


        if ($extension == 'xlsx') {
            $reader = new RXlsx();
        } elseif ($extension == 'xls') {
            $reader = new Xls();
        } else {
            return back()->withErrors(['message' => 'Format de fichier non supporté.']);
        }

        try {
            $spreadsheet = $reader->load(storage_path('app/uploads/' . $filename));
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Erreur lors de la lecture du fichier Excel: ' . $e->getMessage()]);
        }

        $worksheet = $spreadsheet->getActiveSheet();

        
        $rows = $worksheet->toArray();

        // Supprimer la première ligne si elle contient les headers
        if (!empty($rows)) {
            unset($rows[0]);
        }

        dd($rows);

        foreach ($rows as $row) {
            // Accéder aux données de chaque colonne
            $colonne1 = $row[0] ?? null; // Assurez-vous de connaître l'index des colonnes
            $colonne2 = $row[1] ?? null;
            // ... et ainsi de suite

            // Faire quelque chose avec les données (par exemple, les enregistrer dans la base de données)
            dump("Colonne 1: " . $colonne1 . ", Colonne 2: " . $colonne2);
        }

        return redirect()->route('dashboard')->with('success', 'Fichier Excel importé et traité avec succès.');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'fileInput' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('fileInput');
        $filePath = $file->getPathname(); // Chemin temporaire du fichier
        $chunkSize = 1; // Ajustez la taille du lot selon vos besoins

        try {
            $this->readExcelLineByLine($filePath, function (int $rowNumber, array $rowData) {
                // Ici, vous traitez chaque ligne individuellement
                Log::info('Ligne ' . $rowNumber . ' Excel :', $rowData);
                // Votre logique pour traiter la ligne (par exemple, enregistrer en base de données)
            }, 1, null);

            // $this->readExcelInChunks($filePath, $chunkSize, function (array $chunkData) {

            //     foreach ($chunkData as $row) {
            //         Log::info('Ligne du lot :', $row);

            //     }

            //     Log::info('Lot traité avec succès.');
            // });

            return redirect()->back()->with('success', 'Fichier Excel importé avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'importation du fichier Excel : ' . $e->getMessage());
        }
    }

    public function readExcelLineByLine(string $filePath, callable $processLine, int $startRow = 1, ?int $maxRows = null): void
    {
        try {
            $reader = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            Log::info("Lecture du fichier Excel : $filePath, Total lignes : $highestRow");

            $endRow = $maxRows !== null ? min($highestRow, $startRow + $maxRows - 1) : $highestRow;

            for ($rowNumber = $startRow; $rowNumber <= $endRow; $rowNumber++) {
                $rowData = [];
                $row = $worksheet->getRowIterator($rowNumber, $rowNumber)->current();
                if ($row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    foreach ($cellIterator as $cell) {
                        $rowData[] = $cell->getValue();
                    }
                    $processLine($rowNumber, $rowData);
                }
            }

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            Log::info("Traitement du fichier Excel terminé : $filePath");

        } catch (\Exception $e) {
            Log::error("Erreur lors de la lecture du fichier Excel : $filePath", ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function readExcelInChunks(string $filePath, int $chunkSize, callable $processChunk): void
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $totalRows = $worksheet->getHighestRow();
            $startRow = 1;
            Log::info("Lecture du fichier Excel : $filePath, Total lignes : $totalRows, Taille des lots : $chunkSize");

            $reader = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);

            Log::info("Start");

            // Vérifier le type de lecteur et appliquer setRowRange si supporté
            if ($reader instanceof Xlsx || $reader instanceof Xls) {
                Log::info("true");
                for ($currentRow = $startRow; $currentRow <= $totalRows; $currentRow += $chunkSize) {
                    $endRow = min($currentRow + $chunkSize - 1, $totalRows);

                    Log::info("Traitement du lot : Lignes $currentRow à $endRow");

                    $reader->setRowRange($currentRow, $endRow - $currentRow + 1);
                    $chunkedSpreadsheet = $reader->load($filePath);
                    $chunkedWorksheet = $chunkedSpreadsheet->getActiveSheet();

                    $chunkData = [];
                    foreach ($chunkedWorksheet->getRowIterator() as $row) {
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);
                        $rowData = [];
                        foreach ($cellIterator as $cell) {
                            $rowData[] = $cell->getValue();
                        }
                        $chunkData[] = $rowData;
                    }

                    // Appeler la fonction de traitement avec les données du lot
                    $processChunk($chunkData);

                    // Libérer la mémoire
                    $chunkedSpreadsheet->disconnectWorksheets();
                    unset($chunkedSpreadsheet);
                }
            } else {
                Log::info("false");
                // Si le lecteur ne supporte pas setRowRange, charger tout le fichier et le traiter par lots manuellement
                $spreadsheet = $reader->load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray(); // Récupérer toutes les données sous forme de tableau

                for ($i = 0; $i < count($rows); $i += $chunkSize) {
                    $chunkData = array_slice($rows, $i, $chunkSize);
                    Log::info("Traitement manuel du lot : Lignes " . ($i + 1) . " à " . min($i + $chunkSize, count($rows)));
                    $processChunk($chunkData);
                }

                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
            }

            Log::info("Traitement du fichier Excel terminé : $filePath");

        } catch (\Exception $e) {
            Log::error("Erreur lors de la lecture du fichier Excel : $filePath", ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function readExcelInChunks1(string $filePath, int $chunkSize, callable $processChunk): void
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $totalRows = $worksheet->getHighestRow();
            $startRow = 1;

            Log::info("Lecture du fichier Excel : $filePath, Total lignes : $totalRows, Taille des lots : $chunkSize");

            $reader = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);

            for ($currentRow = $startRow; $currentRow <= $totalRows; $currentRow += $chunkSize) {
                $endRow = min($currentRow + $chunkSize - 1, $totalRows);

                Log::info("Traitement du lot : Lignes $currentRow à $endRow");

                $reader->setRowRange($currentRow, $endRow - $currentRow + 1);
                $chunkedSpreadsheet = $reader->load($filePath);
                $chunkedWorksheet = $chunkedSpreadsheet->getActiveSheet();

                $chunkData = [];
                foreach ($chunkedWorksheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    $rowData = [];
                    foreach ($cellIterator as $cell) {
                        $rowData[] = $cell->getValue();
                    }
                    $chunkData[] = $rowData;
                }

                // Appeler la fonction de traitement avec les données du lot
                $processChunk($chunkData);

                // Libérer la mémoire
                $chunkedSpreadsheet->disconnectWorksheets();
                unset($chunkedSpreadsheet);
            }

            Log::info("Traitement du fichier Excel terminé : $filePath");

        } catch (\Exception $e) {
            Log::error("Erreur lors de la lecture du fichier Excel : $filePath", ['message' => $e->getMessage()]);
            throw $e;
        }
    }



    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Téléphone');

        // Exemple de données à exporter
        $data = [
            ['John Doe', 'john.doe@example.com', '123-456-7890'],
            ['Jane Smith', 'jane.smith@example.com', '987-654-3210'],
            // ... vos données
        ];

        $row = 2;
        foreach ($data as $rowData) {
            $sheet->setCellValue('A' . $row, $rowData[0]);
            $sheet->setCellValue('B' . $row, $rowData[1]);
            $sheet->setCellValue('C' . $row, $rowData[2]);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        // Option 1: Sauvegarder le fichier sur le serveur
        // $writer->save('exported-data.xlsx');
        // return response()->download('exported-data.xlsx')->deleteFileAfterSend(true);

        // Option 2: Envoyer le fichier en tant que téléchargement
        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="exported-data.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }


}
