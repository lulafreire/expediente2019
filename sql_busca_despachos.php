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
    $sqlOpt07 = "tipo = '2' AND unidade = '$codUnidade' AND assunto LIKE '%$numero%' OR";
    $sqlOpt08 = " OR tipo = '2' AND unidade = '$codUnidade' AND texto LIKE '%$numero%'";
    $sqlOpt09 = " OR tipo = '2' AND unidade = '$codUnidade' AND numero = '$numero'";
    $sqlOpt10 = "tipo = '3' AND unidade = '$codUnidade' AND assunto LIKE '%$numero%' OR";
    $sqlOpt11 = " OR tipo = '3' AND unidade = '$codUnidade' AND texto LIKE '%$numero%'";
    $sqlOpt12 = " OR tipo = '3' AND unidade = '$codUnidade' AND numero = '$numero'";
  
  } else {
  
    $sqlOpt01 = "";
    $sqlOpt02 = "";
    $sqlOpt03 = "";
    $sqlOpt04 = "";
    $sqlOpt05 = "";
    $sqlOpt06 = "";
    $sqlOpt07 = "";
    $sqlOpt08 = "";
    $sqlOpt09 = "";
    $sqlOpt10 = "";
    $sqlOpt11 = "";
    $sqlOpt12 = "";
  
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
                <th scope="col">Interessado</th>
                <th scope="col">Referência</th>
                <th scope="col">Assunto</th>
                <th scope="col">Emitido em</th>                   
                <th scope="col"><i class='far fa-file-alt' title="Detalhes"></i></th>
                <th scope="col"><i class="far fa-edit" title="Editar"></i></i></th>
                <th scope="col"><i class="fas fa-file-download" title="Download"></i></th>
                <th scope="col"><i class="fas fa-paperclip" title="Anexar Documento"></i></th>
                <th scope="col"><i class="far fa-trash-alt"></i></th>                           
            </tr>
            </thead>
            <tbody>
                
            <?php
            // Pesquisa os Ofícios Emitidos
            $despachos = mysqli_query($conn, "SELECT * FROM documentos WHERE 
            tipo = '2' AND unidade = '$codUnidade' AND interessado LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '2' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
            tipo = '2' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '2' AND unidade = '$codUnidade' AND assunto LIKE '%$termo%' OR
            $sqlOpt07
            tipo = '2' AND unidade = '$codUnidade' AND texto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '2' AND unidade = '$codUnidade' AND texto LIKE '%$termo%'
            $sqlOpt08
            $sqlOpt09 ORDER BY id DESC");
            $qtdespachos = mysqli_num_rows($despachos);

            // Paginação
            $pagina = (isset($_GET['pagina']))? $_GET['pagina']: 1;            
                    
            // Ofícios por página
            $oficiosPorPagina = 4;

            // Total de páginas
            $totalPaginas = ceil($qtdespachos / $oficiosPorPagina);

            // Início
            $inicio = ($oficiosPorPagina * $pagina) - $oficiosPorPagina;

            // Selecionar para paginação
            $res_despachos = mysqli_query($conn, "SELECT * FROM documentos WHERE 
            tipo = '2' AND unidade = '$codUnidade' AND interessado LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '2' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
            tipo = '2' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '2' AND unidade = '$codUnidade' AND assunto LIKE '%$termo%' OR
            $sqlOpt07
            tipo = '2' AND unidade = '$codUnidade' AND texto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
            tipo = '2' AND unidade = '$codUnidade' AND texto LIKE '%$termo%'
            $sqlOpt08
            $sqlOpt09 ORDER by id DESC LIMIT $inicio, $oficiosPorPagina");
                        
            while($r=mysqli_fetch_array($res_despachos)) {

                $id           = $r['id'];
                $numero       = $r['numero'];
                $emissor      = $r['emissor'];
                $assunto      = utf8_encode($r['assunto']);                
                $interessado  = $r['interessado'];
                $data         = $r['data'];
                $dtEmissao    = converteData($data);                
                $resumo       = $r['texto'];
                $anoEmissao   = anoEmissao($dtEmissao);
                
                $r = explode("|", $resumo);
                $referencia =$r[0];
                $texto = $r['1'];                

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
                    <td><small>$interessado</small></td>
                    <td><small>$referencia</small></td>
                    <td><small>$assunto</small></td>
                    <td><small>$dtEmissao</small></td>                                       
                    <td><a href='detalha-despacho-emitido.php?id=$id' target='_parent'><i class='far fa-file-alt text-dark' title='Detalhes'></i></a></td>
                    <td><a href='editar-despacho-emitido.php?id=$id' target='_parent'><i class='far fa-edit text-dark' title='Editar'></i></a></td>
                    <td><a href='dompdf.php?arquivo=$arquivoHtml&nome_arquivo=DESPACHO-$numero-$anoEmissao-$siglaUnidade' target='_blanc' class='text-dark'><i class='fas fa-file-download' title='Abrir Despacho'></i></a></td>                
                    <td><a href='anexar-despacho-emitido.php?id=$id' target='_parent' class='text-dark'><i class='fas fa-paperclip' title='Anexar documento'></i></a></td>                
                    <td><a href='excluir-despacho.php?id=$id&location=sql_despachos&pagina=$pagina' target='_self' class='text-dark'><i class='far fa-trash-alt' title='Excluir'></i></a></td>                    
                </tr>";                

            }
            
            ?>
            
                </tbody>
            </table>
        </div>
    </div>

<?php
if($qtdespachos>0) {
?>
    <div class='row'>
        <div class="col-12">
            <nav aria-label="Navegação de página exemplo">
                <ul class="pagination pagination-sm justify-content-end">
                    <li class="page-item"><a class="page-link" href="sql_busca_despachos.php?pagina=1&termo=<?php echo "$termo"; ?>">Primeira</a></li>
                    <?php 

                        for($i = 1; $i < $totalPaginas + 1; $i++) {

                            if($i == $pagina) {

                                echo "<li class='page-item active'><a class='page-link' href='sql_busca_despachos.php?pagina=$i&termo=$termo'>$i</a></li>";

                            } else {

                                echo "<li class='page-item'><a class='page-link' href='sql_busca_despachos.php?pagina=$i&termo=$termo'>$i</a></li>";

                            }
                            

                        }
                    
                    ?>                
                    <li class="page-item"><a class="page-link" href="sql_busca_despachos.php?pagina=<?php echo "$totalPaginas"; ?>&termo=<?php echo "$termo"; ?>">Última</a></li>
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