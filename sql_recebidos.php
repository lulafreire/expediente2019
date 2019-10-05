<?php
session_start();

$codUnidade = $_SESSION['codUnidade'];

?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">

    <title>.:: Expediente ::.</title>
  </head>
  <body>

<script>
  $(function () {
  $('[data-toggle="tooltip"]').tooltip()
});
</script>

<div class='row mt-3' style="min-height: 375px;">
    <div class="col-12">
        <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">Nº</th>
            <th scope="col">Emissor</th>
            <th scope="col">Cargo - Órgão</th>
            <th scope="col">Emitido em</th>
            <th scope="col">Recebido em</th>
            <th scope="col">Prazo</th>
            <th scope="col"><i class="fas fa-check" title="Status"></i></th>   
            <th scope="col"><i class='far fa-file-alt' title="Detalhes"></i></th>
            <th scope="col"><i class="fas fa-reply" title="Responder"></i></th>            
            <th scope="col"><i class="far fa-edit" title="Editar"></i></th>
            <th scope="col"><i class="fas fa-paperclip" title="Anexar Documento"></i></th>
            <th scope="col"><i class="far fa-trash-alt" title="Excluir"></i></th>
        </tr>
        </thead>
        <tbody>
            
        <?php

        include_once("conn.php");
        include_once("functions.php");

        // Pesquisa os Ofícios Recebidos
        $recebidos = mysqli_query($conn, "SELECT * from documentos where tipo = '1' AND unidade = '$codUnidade' ORDER by id DESC");
        $qtRecebidos = mysqli_num_rows($recebidos);

        // Paginação
        $pagina = (isset($_GET['pagina']))? $_GET['pagina']: 1;
        
        // Ofícios por página
        $oficiosPorPagina = 5;

        // Total de páginas
        $totalPaginas = ceil($qtRecebidos / $oficiosPorPagina);

        // Início
        $inicio = ($oficiosPorPagina * $pagina) - $oficiosPorPagina;

        // Selecionar para paginação
        $res_recebidos = mysqli_query($conn, "SELECT * from documentos where tipo = '1' AND unidade = '$codUnidade' ORDER by id DESC LIMIT $inicio, $oficiosPorPagina");
        while($r=mysqli_fetch_array($res_recebidos)) {

            $id        = $r['id'];
            $numero    = $r['numero'];
            $emissor   = $r['emissor'];
            $interessado = utf8_encode($r['interessado']);
            $data      = $r['data'];
            $dtEmissao = converteData($data);
            $recebido  = $r['recebido'];
            $dtRecebido = converteData($recebido);
            $prazo     = $r['prazo'];
            $dtPrazo   = converteData($prazo);
            $resposta  = $r['resposta'];
            $resumo    = $r['texto'];
            $anoEmissao = anoEmissao($dtEmissao);

            // Pesquisa os dados da unidade logada
            $sqlUnidade = mysqli_query($conn, "SELECT * FROM unidades WHERE cod = '$codUnidade'");
            while($u=mysqli_fetch_array($sqlUnidade)) {

                $nomeUnidade  = utf8_encode($u['nome']);
                $siglaUnidade = $u['sigla'];
                $endUnidade   = utf8_encode($u['end']);
                $telUnidade   = $u['tel'];
                $emailUnidade = $u['email'];
                $cidadeUnidade= utf8_encode($u['cidade']);

            }

            // Flag prazo e resposta
            $hoje = date('Y-m-d');

            if($resposta !='0') {

                $textColor = "text-success";
                $textTitle = "Ofício respondido";        
                
                // Pesquisa os dados do ofício de resposta
                $sqlResposta = mysqli_query($conn, "SELECT * FROM documentos where id = '$resposta'");
                while($resp=mysqli_fetch_array($sqlResposta)) {

                    $numeroResposta = $resp['numero'];
                    $dataResposta   = converteData($resp['data']);
                    $oficioResposta = "Ofício nº $numeroResposta/APSIRECE/INSS, de $dataResposta";
                    $idResposta     = $resp['id'];

                    // Pesquisa o arquivo HTML gerado para o ofício
                    $sqlHtml = mysqli_query($conn, "SELECT * FROM oficios_html WHERE referencia = '$idResposta'");
                    while($h = mysqli_fetch_array($sqlHtml)) {

                        $arquivoHtml = $h['arquivo'];

                    }

                    $textoResposta = "<strong>Resposta:</strong> <a href='dompdf.php?arquivo=$arquivoHtml&nome_arquivo=OFICIO-$numero-$anoEmissao-$siglaUnidade' target='_blanc'>$oficioResposta</a>";
                }

                $tdResponde = "<td><a title='Ofício já respondido'><i class='fas fa-reply text-muted'></i></a></td>";
            
            } else {

                $tdResponde = "<td><a href='responder-oficio-recebido.php?id=$id' target='_parent'><i class='fas fa-reply text-dark' title='Responder'></i></a></td>";

                if($prazo >= $hoje) {

                    if($prazo > $hoje) {
                    
                        $textColor = "text-primary";
                        $textTitle = "Ofício não respondido. Prazo $dtPrazo";

                    } else {
                        $textColor = "text-warning";
                        $textTitle = "Ofício não respondido. Prazo vence hoje: $dtPrazo";
                    }
                    

                } else {

                    if($prazo !="0000-00-00") {
                        
                        $textColor = "text-danger";
                        $textTitle = "Ofício não respondido. Prazo ultrapassado: $dtPrazo";
                    
                    } else {

                        $textColor = "text-primary";
                        $textTitle = "Ofício não respondido. Prazo indefinido.";

                    }
                    

                }

                $textoResposta = "<strong>Resposta:</strong> Ofício não respondido.";
            }

            // Pesquisa o nome do emissor
            $sqlEmissor = mysqli_query($conn, "SELECT * FROM contatos WHERE id = '$emissor'");
            while($n = mysqli_fetch_array($sqlEmissor)) {

                $nomeEmissor = utf8_encode($n['nome']);
                $orgao       = utf8_encode($n['orgao']);
                $cargo       = utf8_encode($n['cargo']);
            }
            
            echo "
            <tr>
                <th scope='row'><small>$numero/$anoEmissao</small></th>
                <td><small><a title='$cargo - $orgao'>$nomeEmissor</a></small></td>
                <td><small>$cargo - $orgao</small></td>
                <td><small>$dtEmissao</small></td>
                <td><small>$dtRecebido</small></td>
                <td><small>$dtPrazo</small></td>
                <td><a title='$textTitle'><i class='fas fa-check $textColor'></i></a></td>
                <td><a href='detalha-oficio-recebido.php?id=$id' target='_parent'><i class='far fa-file-alt' title='Detalhes'></i></a></td>
                $tdResponde
                <td><a href='editar-oficio-recebido.php?id=$id' target='_parent'><i class='far fa-edit text-dark' title='Editar'></i></a></td>                
                <td><a href='anexar-oficio-recebido.php?id=$id' target='_parent'><i class='fas fa-paperclip text-dark' title='Anexar Documento'></i></a></td>
                <td><a href='excluir-oficio.php?id=$id&location=sql_recebidos&pagina=$pagina' onclick=\"return confirm('Deseja realmente excluir o Ofício $numero/$anoEmissao de $nomeEmissor?')\" target='_parent'><i class='far fa-trash-alt text-dark' title='Excluir'></i></a></td>
            </tr>";

            // Modal Detalha Ofício
            echo "
            <div class='modal fade' id='detalhaOficio$id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>
                    <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLabel'>Ofício <strong>$numero/$anoEmissao</strong></h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
                        <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <strong>Emissor:</strong> $nomeEmissor<br>
                        <strong>Cargo:</strong> $cargo<br>
                        <strong>Órgão:</strong> $orgao<br>
                        <strong>Interessado:</strong> $interessado<br>
                        <strong>Emitido em:</strong> $dtEmissao<br> 
                        <strong>Prazo:</strong> $dtPrazo<br>
                        $textoResposta
                        <hr>
                        <strong>Resumo:</strong> $resumo
                        <hr>
                        <strong>ANEXOS:</strong><br>";

                        // Pesquisa os anexos relacionados a este ofício
                        $sqlAnexos = mysqli_query($conn, "SELECT * FROM anexos WHERE referencia ='$id' ORDER BY id ASC");
                        while($a=mysqli_fetch_array($sqlAnexos)) {
                            $idAnexo = $a['id'];
                            $arquivo = $a['arquivo'];
                            $tam     = $a['tam'];
                            $dtAnexo = converteData($a['data']);
                            $descricao = utf8_encode($a['descricao']);

                            echo "• <a href='download.php?url=$arquivo'>$descricao</a> <small class='text-muted'><i>($tam Mb) Anexado em $dtAnexo</i></small><br>";
                        }
                        echo "<hr>
                        <strong>Histórico de Alterações:</strong><br>";

                        // Pesquisa os eventos relacionados ao ofício
                        $sqlEventos = mysqli_query($conn, "SELECT * FROM eventos WHERE referencia = '$id' ORDER BY id ASC");
                        while($e=mysqli_fetch_array($sqlEventos)) {
                            $data = converteDataHora($e['data']);
                            $evento = $e['descricao'];

                            echo "$data - $evento<br>";

                        }
                        
                    echo "
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
                    </div>
                    </div>
                </div>
            </div>";

        }
        
        ?>
        
            </tbody>
        </table>
    </div>
</div>

<div class='row'>
    <div class="col-12">
        <nav aria-label="Navegação de página exemplo">
            <ul class="pagination pagination-sm justify-content-end">
                <li class="page-item"><a class="page-link" href="sql_recebidos.php?pagina=1">Primeira</a></li>
                <?php 

                    for($i = 1; $i < $totalPaginas + 1; $i++) {

                        if($i == $pagina) {

                            echo "<li class='page-item active'><a class='page-link' href='sql_recebidos.php?pagina=$i'>$i</a></li>";

                        } else {

                            echo "<li class='page-item'><a class='page-link' href='sql_recebidos.php?pagina=$i'>$i</a></li>";

                        }
                        

                    }
                
                ?>                
                <li class="page-item"><a class="page-link" href="sql_recebidos.php?pagina=<?php echo "$totalPaginas"; ?>">Última</a></li>
            </ul>
        </nav>
    </div>
</div>




<!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="node_modules/popper.js/dist/popper.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script>
            CKEDITOR.replace( 'txtArtigo' );
    </script>

  </body>
</html>