<?php
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 60);

require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Mpdf\MpdfException;

function genererAttestationPDF(array $data): ?string {
  try {
    $mpdf = new Mpdf();

    ob_start();
    include __DIR__ . '/../views/attestation_template.php';
    $html = ob_get_clean();

    $timestamp = time();
    $filename = 'attestation_' . strtolower($data['nom'] ?? 'inconnu') . '_' . strtolower($data['prenoms'] ?? 'inconnu') . '_' . $timestamp . '.pdf';
    $dir = __DIR__ . '/public/pdf/';
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $filepath = $dir . $filename;

    $mpdf->WriteHTML($html);
    $mpdf->Output($filepath, Destination::FILE);

    return $filepath;

  } catch (MpdfException $e) {
    error_log("Erreur PDF : " . $e->getMessage());
    // Plus de echo ici !
    return null;
  }
}

