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
            <th scope="col"><i class="far fa-edit" title="Editar"></i></th>
            <th scope="col"><i class="fas fa-paperclip" title="Anexar Documento"></i></th>
            <th scope="col"><i class="far fa-trash-alt" title="Excluir"></i></th>
        </tr>
        </thead>
        <tbody>
            
        <?php

        include_once("conn.php");
        include_once("functions.php");

        $hoje = date('Y-m-d');

        // Pesquisa os Ofícios Recebidos
        $recebidos = mysqli_query($conn, "SELECT * from documentos where tipo = '0' AND resposta ='' AND unidade = '$codUnidade' ORDER by id DESC");
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
        $res_recebidos = mysqli_query($conn, "SELECT * from documentos where tipo = '0' AND resposta ='' AND unidade = '$codUnidade' ORDER by id DESC LIMIT $inicio, $oficiosPorPagina");
        while($r=mysqli_fetch_array($res_recebidos)) {

            $id           = $r['id'];
            $numero       = $r['numero'];
            $emissor      = $r['emissor'];
            $assunto      = utf8_encode($r['assunto']);
            $destinatario = $r['destinatario'];
            $interessado  = $r['interessado'];
            $data         = $r['data'];
            $dtEmissao    = converteData($data);
            $recebido     = $r['recebido'];
            $dtRecebido   = converteData($recebido);
            $prazo        = $r['prazo'];
            $dtPrazo      = converteData($prazo);
            $resposta     = $r['resposta'];
            $resumo       = $r['texto'];
            $anoEmissao   = anoEmissao($dtEmissao);

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

                    $textoResposta = "<strong>Resposta:</strong> <a href='pdf.php?idResp=$resposta' target='_parent'>$oficioResposta</a>";
                }
            
            } else {

                if($prazo > $hoje) {

                    $textColor = "text-primary";
                    $textTitle = "Ofício não respondido. Prazo $dtPrazo";

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

            // Verifica se possui anexo
            $sqlAnexos = mysqli_query($conn, "SELECT * FROM anexos WHERE referencia = '$id'");
            $qtAnexos = mysqli_num_rows($sqlAnexos);
            while($a = mysqli_fetch_array($sqlAnexos)) {

                $descricao = $a['descricao'];
                $arquivo   = $a['arquivo'];
                $tam       = $a['tam'];

            }
            
            if($qtAnexos =='') {

                $colorAnexo = "text-muted";
                $textAnexos = "Ofício não foi anexado.";
                $textDownload = "<i class='fas fa-file-download $colorAnexo' title='$textAnexos'></i>";

            } else {

                $colorAnexo = "text-dark";
                $textAnexos = "$descricao - $tam Mb";
                $textDownload = "<a href='download.php?url=$arquivo'><i class='fas fa-file-download $colorAnexo' title='$textAnexos' text-dark></i></a>";

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
                <td><i class='far fa-file-alt' title='Detalhes' data-toggle='modal' data-target='#detalhaOficio$id'></i></td>                
                <td><a href='editar-oficio-recebido.php?id=$id' target='_self'><i class='far fa-edit text-dark' title='Editar'></i></a></td>
                <td><a href='anexar-oficio-recebido.php?id=$id' target='_self'><i class='fas fa-paperclip text-dark' title='Anexar Documento'></i></a></td>
                <td><a href='excluir-oficio.php?id=$id' onclick=\"return confirm('Deseja realmente excluir o Ofício $numero de $nomeEmissor?')\" target='_parent'><i class='far fa-trash-alt text-dark' title='Excluir'></i></a></td>
            </tr>";
            // Modal Detalha Ofício
            echo "
            <div class='modal fade' id='detalhaOficio$id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered' role='document'>
                    <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLabel'>Ofício <strong>$numero</strong></h5>
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
                <li class="page-item"><a class="page-link" href="sql-recebidos-nrpu.php?pagina=1">Primeira</a></li>
                <?php 

                    for($i = 1; $i < $totalPaginas + 1; $i++) {

                        if($i == $pagina) {

                            echo "<li class='page-item active'><a class='page-link' href='sql-recebidos-nrpu.php?pagina=$i'>$i</a></li>";

                        } else {

                            echo "<li class='page-item'><a class='page-link' href='sql-recebidos-nrpu.php?pagina=$i'>$i</a></li>";

                        }
                        

                    }
                
                ?>                
                <li class="page-item"><a class="page-link" href="sql-recebidos-nrpu.php?pagina=<?php echo "$totalPaginas"; ?>">Última</a></li>
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