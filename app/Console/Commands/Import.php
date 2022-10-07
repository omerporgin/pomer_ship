<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        try {
            Artisan::call('db:wipe');

            $fileList = glob("./database/gtip_csv/*");
            foreach ($fileList as $path) {

                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                $spreadsheet = $reader->load($path);
                $reader->setDelimiter(';');
                $reader->setEnclosure('"');
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                foreach ($sheetData as $row) {
                    try {
                        $item = new \App\Models\Gtip;
                        $item->gtip = $row[1];
                        $item->description = $row[2];
                        if (isset($row[3])) {
                            $item->olcu = $row[3];
                        }
                        if (isset($row[4])) {
                            $item->vergi = $row[4];
                        }
                        $item->is_selectable = 0;
                        $item->search = '';
                    } catch (\Exception $e) {
                        dd($item);
                    }
                    $item->save();
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
