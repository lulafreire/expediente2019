<?php

$arquivo      = $_GET['arquivo'];
$nome_arquivo = $_GET['nome_arquivo'];

require_once 'vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$html = file_get_contents("oficios-emitidos/$arquivo");

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$dompdf->stream("$nome_arquivo", array("Attachment" => 0));

?>
