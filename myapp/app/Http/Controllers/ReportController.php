<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ReportController extends Controller
{
 public function openPdf(Request $request)
{
    $jasperStarter = 'C:\\jasperstarter\\bin\\jasperstarter.exe';
    $reportFile = storage_path('app/reports/Blank_A4.jrxml');
    $outputDir = storage_path('app/reports/output');
    $outputPdf = $outputDir . DIRECTORY_SEPARATOR . 'Blank_A4.pdf';

    if (!file_exists($jasperStarter)) {
        dd('ไม่พบ jasperstarter.exe', $jasperStarter);
    }

    if (!file_exists($reportFile)) {
        dd('ไม่พบไฟล์ report', $reportFile);
    }

    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    if (file_exists($outputPdf)) {
        unlink($outputPdf);
    }

    $process = new Process([
        $jasperStarter,
        'pr',
        $reportFile,
        '-f', 'pdf',
        '-t', 'mysql',
        '-H', '127.0.0.1',
        '-n', 'webportal',
        '-u', 'root',
        '--db-port', '3306',
        '-o', $outputDir,
    ]);

    $process->setTimeout(120);
    $process->run();

    dd([
        'successful' => $process->isSuccessful(),
        'output' => $process->getOutput(),
        'errorOutput' => $process->getErrorOutput(),
        'outputPdfExists' => file_exists($outputPdf),
        'outputPdf' => $outputPdf,
    ]);
}
}