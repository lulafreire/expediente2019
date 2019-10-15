<?php
session_start();

include_once("conn.php");
include_once("functions.php");

$codUnidade = $_SESSION['codUnidade'];

$termo= $_GET['termo'];

$numero = formataNumero(preg_replace("/[^0-9]/", "", $termo));

if($numero!='') {

    $sqlOpt01 = "tipo = '1' AND unidade = '$codUnidade' AND assunto LIKE '%$numero%' OR";
    $sqlOpt02 = " OR tipo = '1' AND unidade = '$codUnidade' AND texto LIKE '%$numero%'";
    $sqlOpt03 = "tipo = '0' AND unidade = '$codUnidade' AND assunto LIKE '%$numero%' OR";
    $sqlOpt04 = " OR tipo = '0' AND unidade = '$codUnidade' AND texto LIKE '%$numero%'";
    $sqlOpt05 = " OR tipo = '1' AND unidade = '$codUnidade' AND numero = '$numero'";
    $sqlOpt06 = " OR tipo = '0' AND unidade = '$codUnidade' AND numero = '$numero'";
  
  } else {
  
    $sqlOpt01 = "";
    $sqlOpt02 = "";
    $sqlOpt03 = "";
    $sqlOpt04 = "";
    $sqlOpt05 = "";
    $sqlOpt06 = "";
  
  }

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

<div class="container-fluid">
    <div class='row mt-3' style="min-height: 340px;">
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

            // Pesquisa os Ofícios Recebidos
            $recebidos = mysqli_query($conn, "SELECT * FROM documentos WHERE 
            tipo = '1' AND unidade = '$codUnidade' AND interessado LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '1' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
            tipo = '1' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '1' AND unidade = '$codUnidade' AND assunto LIKE '%$termo%' OR
            $sqlOpt01
            tipo = '1' AND unidade = '$codUnidade' AND texto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '1' AND unidade = '$codUnidade' AND texto LIKE '%$termo%'
            $sqlOpt02 $sqlOpt05 ORDER BY id DESC");
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
            $res_recebidos = mysqli_query($conn, "SELECT * FROM documentos WHERE 
            tipo = '1' AND unidade = '$codUnidade' AND interessado LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '1' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
            tipo = '1' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '1' AND unidade = '$codUnidade' AND assunto LIKE '%$termo%' OR
            $sqlOpt01
            tipo = '1' AND unidade = '$codUnidade' AND texto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '1' AND unidade = '$codUnidade' AND texto LIKE '%$termo%'
            $sqlOpt02 $sqlOpt05 ORDER by id DESC LIMIT $inicio, $oficiosPorPagina");
            
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
                    <td><a href='detalha-oficio-recebido.php?id=$id' target='_parent'><i class='far fa-file-alt text-dark' title='Detalhes'></i></a></td>
                    $tdResponde
                    <td><a href='editar-oficio-recebido.php?id=$id' target='_parent'><i class='far fa-edit text-dark' title='Editar'></i></a></td>                
                    <td><a href='anexar-oficio-recebido.php?id=$id' target='_parent'><i class='fas fa-paperclip text-dark' title='Anexar Documento'></i></a></td>
                    <td><a href='excluir-oficio.php?id=$id&location=sql_recebidos&pagina=$pagina' onclick=\"return confirm('Deseja realmente excluir o Ofício $numero/$anoEmissao de $nomeEmissor?')\" target='_self'><i class='far fa-trash-alt text-dark' title='Excluir'></i></a></td>
                </tr>";

                

            }
            
            ?>
            
                </tbody>
            </table>
        </div>
    </div>
<?php
    if($qtRecebidos>0) {
?>
    <div class='row'>
        <div class="col-12">
            <nav aria-label="Navegação de página exemplo">
                <ul class="pagination pagination-sm justify-content-end">
                    <li class="page-item"><a class="page-link" href="sql_busca_recebidos.php?pagina=1&termo=<?php echo "$termo"; ?>">Primeira</a></li>
                    <?php 

                        for($i = 1; $i < $totalPaginas + 1; $i++) {

                            if($i == $pagina) {

                                echo "<li class='page-item active'><a class='page-link' href='sql_busca_recebidos.php?pagina=$i&termo=$termo'>$i</a></li>";

                            } else {

                                echo "<li class='page-item'><a class='page-link' href='sql_busca_recebidos.php?pagina=$i&termo=$termo'>$i</a></li>";

                            }
                            

                        }
                    
                    ?>                
                    <li class="page-item"><a class="page-link" href="sql_busca_recebidos.php?termo=<?php echo "$termo"; ?>&pagina=<?php echo "$totalPaginas"; ?>">Última</a></li>
                </ul>
            </nav>
        </div>
    </div>
<?php
    }
?>
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