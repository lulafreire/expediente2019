<?php
namespace Lula;
use Dompdf\Dompdf;

Class Capa {

    private $pdf;

    public function __construct($dados)
    {
        $numero        = formataNumero($dados[0]['numero'], $dados[0]['origem']);
        $origem        = $dados[0]['origem'];
        $titular       = $dados[0]['titular'];
        $instituidor   = $dados[0]['instituidor'];
        $representante = $dados[0]['representante'];
        $dt_cadastro   = converteData($dados[0]['dt_cadastro']);
        $caixa         = $dados[0]['caixa'];
        
        $html = "
        
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <!-- Meta tags Obrigatórias -->
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>

            <!-- Bootstrap CSS -->
            <link rel='stylesheet' href='node_modules/bootstrap/compiler/bootstrap.css'>
            <link rel='stylesheet' href='node_modules/bootstrap/compiler/style.css'>

            <table class='table table-bordered border-dark'>
            <thead>
                <tr>
                <th scope='col'>#</th>
                <th scope='col'>Primeiro</th>
                <th scope='col'>Último</th>
                <th scope='col'>Nickname</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <th scope='row'>1</th>
                <td>$titular</td>
                <td><b>$numero</b></td>
                <td>$origem - $caixa</td>
                </tr>
                <tr>
                <th scope='row'>2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td>@fat</td>
                </tr>
                <tr>
                <th scope='row'>3</th>
                <td colspan='2'>Larry the Bird</td>
                <td>@twitter</td>
                </tr>
            </tbody>
            </table>        
        ";

        $this->pdf = new Dompdf();    

        $this->pdf->loadHtml($html);

        $this->pdf->setPaper('A4', 'portrait');    

        $this->pdf->render();
    }

    public function stream()
	{
		return $this->pdf->stream();
	}

}


?>