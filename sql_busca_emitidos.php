<?php
session_start();

$codUnidade = $_SESSION['codUnidade'];

$termo= $_GET['termo'];

?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">

    <!-- JQUERY Autocomplete CSS -->
    <link rel="stylesheet" type="text/css" href="ui/jquery-ui.css"/>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">

    <!-- JQUERY completar formulário -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>

    <title>.:: Expediente ::.</title>
  </head>
  <body>

<script>
  $(function () {
  $('[data-toggle="tooltip"]').tooltip()
});
</script>

<div class="container-fluid">
    <div class='row mt-3' style="min-height: 340px;">
        <div class="col-12">

            <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Número</th>
                <th scope="col">Destinatário</th>
                <th scope="col">Cargo - Órgão</th>
                <th scope="col">Emitido em</th>
                <th scope="col">Recebido em</th>
                <th scope="col"><i class="fas fa-check" title="Status"></i></th>   
                <th scope="col"><i class='far fa-file-alt' title="Detalhes"></i></th>
                <th scope="col"><i class="far fa-edit" title="Editar"></i></i></th>
                <th scope="col"><i class="fas fa-file-download" title="Download"></i></th>
                <th scope="col"><i class="fas fa-paperclip" title="Anexar Documento"></i></th>
                <th scope="col"><i class="far fa-trash-alt"></i></th>
                <th scope="col"><i class="fas fa-envelope-open-text"></i></th>            
            </tr>
            </thead>
            <tbody>
                
            <?php

            include_once("conn.php");
            include_once("functions.php");

            // Pesquisa os Ofícios Emitidos
            $emitidos = mysqli_query($conn, "SELECT * FROM documentos WHERE 
            tipo = '0' AND unidade = '$codUnidade' AND interessado LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '0' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
            tipo = '0' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '0' AND unidade = '$codUnidade' AND assunto LIKE '%$termo%' OR
            tipo = '0' AND unidade = '$codUnidade' AND texto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '0' AND unidade = '$codUnidade' AND texto LIKE '%$termo%' ORDER BY id DESC");
            $qtEmitidos = mysqli_num_rows($emitidos);

            // Paginação
            $pagina = (isset($_GET['pagina']))? $_GET['pagina']: 1;            
                    
            // Ofícios por página
            $oficiosPorPagina = 5;

            // Total de páginas
            $totalPaginas = ceil($qtEmitidos / $oficiosPorPagina);

            // Início
            $inicio = ($oficiosPorPagina * $pagina) - $oficiosPorPagina;

            // Selecionar para paginação
            $res_emitidos = mysqli_query($conn, "SELECT * FROM documentos WHERE 
            tipo = '0' AND unidade = '$codUnidade' AND interessado LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '0' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
            tipo = '0' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '0' AND unidade = '$codUnidade' AND assunto LIKE '%$termo%' OR
            tipo = '0' AND unidade = '$codUnidade' AND texto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '0' AND unidade = '$codUnidade' AND texto LIKE '%$termo%' ORDER by id DESC LIMIT $inicio, $oficiosPorPagina");
                        
            while($r=mysqli_fetch_array($res_emitidos)) {

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

                // Pesquisa se o ofício consta como resposta de algum outro
                $sqlResposta = mysqli_query($conn, "SELECT * FROM documentos WHERE resposta = '$id'");
                $resResp = mysqli_num_rows($sqlResposta);

                if($resResp !='') {

                    while($resp=mysqli_fetch_array($sqlResposta)) {
                        $idOficioOriginal           = $resp['id'];
                        $numOficioOriginal          = $resp['numero'];
                        $emissorOficioOriginal      = $resp['emissor'];
                        $dataOficioOriginal         = $resp['data'];
                        $dtEmissaoOficioOriginal    = converteData($dataOficioOriginal);
                        $recebidoOficioOriginal     = $resp['recebido'];
                        $dtRecebidoOficioOriginal   = converteData($recebidoOficioOriginal);
                        $prazoOficioOriginal        = $resp['prazo'];
                        $dtPrazoOficioOriginal      = converteData($prazoOficioOriginal);
                        $respostaOficioOriginal     = $resp['resposta'];
                        $resumoOficioOriginal       = $resp['texto'];
                        $anoEmissaoOficioOriginal   = anoEmissao($dtEmissaoOficioOriginal);


                        $textColor = "text-success";
                        $textTitle = "Responde ao Ofício nº $numOficioOriginal";

                        // Pesquisa os dados do emissor da Resposta
                        $sqlEmissor = mysqli_query($conn, "SELECT * FROM contatos WHERE id = '$emissorOficioOriginal'");
                        while($n = mysqli_fetch_array($sqlEmissor)) {

                            $nomeEmissorOficioOriginal   = utf8_encode($n['nome']);
                            $orgaoEmissorOficioOriginal  = utf8_encode($n['orgao']);
                            $cargoEmissorOficioOriginal  = utf8_encode($n['cargo']);
                        }
                        $textResposta = "Responde ao Ofício nº $numOficioOriginal, de $nomeEmissorOficioOriginal ($cargoEmissorOficioOriginal - $orgaoEmissorOficioOriginal) <i class='far fa-file-alt' title='Detalhes' data-toggle='modal' data-target='#detalhaReferencia$idOficioOriginal' data-dismiss='modal'></i>";

                    }

                } else {

                    if($resposta !='0') {

                        $textColor = "text-success";
                        $textTitle = "Ofício respondido";

                        // Pesquisa os dados do ofício de resposta
                        $sqlOfResp = mysqli_query($conn, "SELECT * FROM documentos WHERE id = '$resposta'");
                        while($of = mysqli_fetch_array($sqlOfResp)) {

                            $numOficioResposta = $of['numero'];
                            $idOficioResposta  = $of['id'];

                        }

                        $textResposta = "Respondido pelo Ofício $numOficioResposta";
                    
                    } else {

                        $textColor = "text-danger";
                        $textTitle = "Ofício não respondido.";
                        $textResposta = "Ofício não respondido.";

                    }

                }

                // Pesquisa o nome do destinatario
                $sqlEmissor = mysqli_query($conn, "SELECT * FROM contatos WHERE id = '$destinatario'");
                while($n = mysqli_fetch_array($sqlEmissor)) {

                    $nomeDest    = utf8_encode($n['nome']);
                    $orgao       = utf8_encode($n['orgao']);
                    $cargo       = utf8_encode($n['cargo']);
                }

                // Pesquisa o nome do emissor
                $sqlEmissor = mysqli_query($conn, "SELECT * FROM usuarios WHERE id = '$emissor'");
                while($n = mysqli_fetch_array($sqlEmissor)) {

                    $nomeEmissor = utf8_encode($n['nome']);
                    $matricula   = utf8_encode($n['matricula']);
                }
                
                $codUnidade = $_SESSION['codUnidade'];
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

                // Pesquisa o arquivo HTML gerado para o ofício
                $sqlHtml = mysqli_query($conn, "SELECT * FROM oficios_html WHERE referencia = '$id'");
                while($h = mysqli_fetch_array($sqlHtml)) {

                    $arquivoHtml = $h['arquivo'];

                }

                echo "
                <tr>
                    <th scope='row'><small>$numero/$anoEmissao</small></th>
                    <td><small><a title='$cargo - $orgao'>$nomeDest</a></small></td>
                    <td><small>$cargo - $orgao</small></td>
                    <td><small>$dtEmissao</small></td>
                    <td><small>$dtRecebido</small></td>
                    <td><a title='$textTitle'><i class='fas fa-check $textColor'></i></a></td>
                    <td><a href='detalha-oficio-emitido.php?id=$id' target='_parent'><i class='far fa-file-alt text-dark' title='Detalhes'></i></a></td>
                    <td><a href='editar-oficio-emitido.php?id=$id' target='_parent'><i class='far fa-edit text-dark' title='Editar'></i></a></td>
                    <td><a href='dompdf.php?arquivo=$arquivoHtml&nome_arquivo=OFICIO-$numero-$anoEmissao-$siglaUnidade' target='_blanc' class='text-dark'><i class='fas fa-file-download' title='Abrir Ofício'></i></a></td>                
                    <td><a href='anexar-oficio-emitido.php?id=$id' target='_parent' class='text-dark'><i class='fas fa-paperclip' title='Anexar documento'></i></a></td>                
                    <td><a href='excluir-oficio.php?id=$id&location=sql_emitidos&pagina=$pagina' target='_self' class='text-dark'><i class='far fa-trash-alt' title='Excluir'></i></a></td>
                    <td><i class='fas fa-envelope-open-text' title='Registrar Recebimento do AR' data-toggle='modal' data-target='#registra-AR-$id'></i></td>
                </tr>";                

                // Modal Registra AR
                echo "
                <div class='modal fade' id='registra-AR-$id' tabindex='-1' role='dialog' aria-labelledby='ModalAR' aria-hidden='true'>
                    <div class='modal-dialog modal-md modal-dialog' role='document'>
                        <div class='modal-content'>
                        <div class='modal-header alert-primary'>
                            <h5 class='modal-title' id='exampleModalLabel'>Registrar AR - Ofício <strong>$numero/$anoEmissao/$siglaUnidade/INSS</strong></h5>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body'>
                            <form method='post' action='gravar-ar.php' enctype='multipart/form-data'>
                                <div class='row'>
                                    <div class='col-8'>
                                        <label for ='data'><strong>Data de Recebimento</strong></label>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-4 mb-2'>
                                        <input type='hidden' name='id' value='$id'>
                                        <input type='hidden' name='pagina' value='$pagina'>
                                        <input type='hidden' name='location' value='sql_emitidos'>    
                                        <input name='data' class='form-control form-control-sm' type='text' id='calendario' autocomplete='off' placeholder='Data'/>                    
                                    </div>                               
                                </div>
                                <div class='row'>
                                    <div class='col-3'>
                                        <label for ='arquivo'><strong>Anexo</strong></label>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-9'>
                                        <input class='form-control-sm mb-2' type='file' name='arquivo'>
                                    </div>                               
                                </div>                                                
                        </div>
                        <div class='modal-footer'>
                        <input type='submit' class='btn btn-success btn' value='Gravar'></form>&nbsp;&nbsp;<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
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

<?php
if($qtEmitidos>0) {
?>
    <div class='row'>
        <div class="col-12">
            <nav aria-label="Navegação de página exemplo">
                <ul class="pagination pagination-sm justify-content-end">
                    <li class="page-item"><a class="page-link" href="sql_busca_emitidos.php?pagina=1&termo=<?php echo "$termo"; ?>">Primeira</a></li>
                    <?php 

                        for($i = 1; $i < $totalPaginas + 1; $i++) {

                            if($i == $pagina) {

                                echo "<li class='page-item active'><a class='page-link' href='sql_busca_emitidos.php?pagina=$i&termo=$termo'>$i</a></li>";

                            } else {

                                echo "<li class='page-item'><a class='page-link' href='sql_busca_emitidos.php?pagina=$i&termo=$termo'>$i</a></li>";

                            }
                            

                        }
                    
                    ?>                
                    <li class="page-item"><a class="page-link" href="sql_busca_emitidos.php?pagina=<?php echo "$totalPaginas"; ?>&termo=<?php echo "$termo"; ?>">Última</a></li>
                </ul>
            </nav>
        </div>
    </div>
<?php
}
?>

</div>



<!-- JavaScript (Opcional) -->    
<script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="ui/jquery-ui.js"></script>
    <script src="node_modules/popper/dist/popper2.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="ckeditor/ckeditor.js"></script>

    <script type="text/javascript">
        $(function () {
            $('[data-toggle="popover"]').popover()
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    
    <!-- CKEditor -->
    <script>
            CKEDITOR.replace( 'txtArtigo' );
    </script>

    <!-- Datepicker -->
    <script>
        $(function() {
            $("#calendario").datepicker({
                dateFormat: 'dd/mm/yy',
                showOtherMonths: true,
                selectOtherMonths: true,
                dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
                dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
                dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
                monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
            });
        });
    </script>

  </body>
</html>