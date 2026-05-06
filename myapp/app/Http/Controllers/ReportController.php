<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function openPdf(Request $request)
    {
        $jasperStarter = 'C:\\Program Files (x86)\\JasperStarter\\bin\\jasperstarter.exe';
        $reportFile = storage_path('app/reports/products_report.jrxml');
        $outputDir = storage_path('app/reports/output');
        $outputPdf = $outputDir . DIRECTORY_SEPARATOR . 'products_report.pdf';

        if (!file_exists($jasperStarter)) {
            abort(500, 'ไม่พบ jasperstarter.exe');
        }

        if (!file_exists($reportFile)) {
            abort(500, 'ไม่พบไฟล์ report');
        }

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        if (file_exists($outputPdf)) {
            unlink($outputPdf);
        }

        $cmd = '"C:\\Program Files (x86)\\JasperStarter\\bin\\jasperstarter.exe"'
            . ' pr "' . $reportFile . '"'
            . ' -f pdf'
            . ' --jdbc-dir "C:\\jdbc"'
            . ' -t mysql'
            . ' -H 127.0.0.1'
            . ' --db-port 3306'
            . ' -n webportal'
            . ' -u root'
            . ' -o "' . $outputDir . '"'
            . ' 2>&1';

        shell_exec($cmd);

        if (!file_exists($outputPdf)) {
            abort(500, 'ไม่สามารถสร้าง PDF ได้');
        }

        return response()->file($outputPdf, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="products_report.pdf"',
        ]);
    }
}
