<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include("conn.php");
include("functions.php");

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

$termo = $_POST['q'];

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
      

// Pesquisa a quantidade de ofícios Recebidos e Emitidos
$sqlQtRec = mysqli_query($conn, "SELECT * FROM documentos WHERE 
tipo = '1' AND unidade = '$codUnidade' AND interessado LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '1' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
tipo = '1' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '1' AND unidade = '$codUnidade' AND assunto LIKE '%$termo%' OR
$sqlOpt01
tipo = '1' AND unidade = '$codUnidade' AND texto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '1' AND unidade = '$codUnidade' AND texto LIKE '%$termo%'
$sqlOpt02
$sqlOpt05");
$qtRec    = mysqli_num_rows($sqlQtRec);

$sqlQtEmit = mysqli_query($conn, "SELECT * FROM documentos WHERE 
tipo = '0' AND unidade = '$codUnidade' AND interessado LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '0' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
tipo = '0' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '0' AND unidade = '$codUnidade' AND assunto LIKE '%$termo%' OR
$sqlOpt03 
tipo = '0' AND unidade = '$codUnidade' AND texto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '0' AND unidade = '$codUnidade' AND texto LIKE '%$termo%'
$sqlOpt04
$sqlOpt06");
$qtEmit    = mysqli_num_rows($sqlQtEmit);

// Pesquisa a quantidade de Despachos Emitidos
$sqlQtDesp = mysqli_query($conn, "SELECT * FROM documentos WHERE 
tipo = '2' AND unidade = '$codUnidade' AND interessado LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '2' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
tipo = '2' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '2' AND unidade = '$codUnidade' AND assunto LIKE '%$termo%' OR
$sqlOpt07
tipo = '2' AND unidade = '$codUnidade' AND texto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '2' AND unidade = '$codUnidade' AND texto LIKE '%$termo%'
$sqlOpt08
$sqlOpt09");
$qtDesp    = mysqli_num_rows($sqlQtDesp);

// Pesquisa a quantidade de Cartas Emitidas
$sqlQtCartas = mysqli_query($conn, "SELECT * FROM documentos WHERE 
tipo = '3' AND unidade = '$codUnidade' AND interessado LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '3' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
tipo = '3' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '3' AND unidade = '$codUnidade' AND assunto LIKE '%$termo%' OR
$sqlOpt10
tipo = '3' AND unidade = '$codUnidade' AND texto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR 
tipo = '3' AND unidade = '$codUnidade' AND texto LIKE '%$termo%'
$sqlOpt11
$sqlOpt12");
$qtCartas    = mysqli_num_rows($sqlQtCartas);


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

<div class='container-fluid'>

  <div class='col-12 border-bottom mb-2'>
    <h6><i class='fas fa-search text-primary'></i> Resultado da busca por <b><font class='text-primary'>"<?php echo "$termo"; ?>"</font></b>
  </div>

  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="rececebidos-tab" data-toggle="tab" href="#recebidos" role="tab" aria-controls="rec" aria-selected="true"><i class='fas fa-file-alt'></i> Ofícios <b>Recebidos</b> (<?php echo "$qtRec"; ?>)</a>
    </li>    
    <li class="nav-item">
      <a class="nav-link" id="emitidos-tab" data-toggle="tab" href="#emitidos" role="tab" aria-controls="profile" aria-selected="false"><i class='fas fa-file-alt'></i> Ofícios <b>Emitidos</b> (<?php echo "$qtEmit"; ?>)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" title="Despachos Emitidos" id="despachos-tab" data-toggle="tab" href="#despachos" role="tab" aria-controls="despachos" aria-selected="false"><i class="fas fa-file-signature"></i> Despachos <b>Emitidos</b> (<?php echo "$qtDesp"; ?>)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" title="Cartas Emitidas" id="cartas-tab" data-toggle="tab" href="#cartas" role="tab" aria-controls="cartas" aria-selected="false"><i class="far fa-envelope"></i> Cartas <b>Emitidas</b> (<?php echo "$qtCartas"; ?>)</a>
    </li>   
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="recebidos" role="tabpanel" aria-labelledby="recebidos-tab">
        <iframe name='recebidos' width="100%" height="400" frameborder="0" scrolling="no" src="sql_busca_recebidos.php?termo=<?php echo"$termo"; ?>"></iframe>
    </div>
    <div class="tab-pane fade" id="emitidos" role="tabpanel" aria-labelledby="emitidos-tab">
        <iframe name='emitidos' width="100%" height="400" frameborder="0" scrolling="no" src="sql_busca_emitidos.php?termo=<?php echo"$termo"; ?>"></iframe>    
    </div>
    <div class="tab-pane fade" id="despachos" role="tabpanel" aria-labelledby="despachos-tab">
        <iframe name='despachos' width="100%" height="400" frameborder="0" scrolling="no" src="sql_busca_despachos.php?termo=<?php echo"$termo"; ?>"></iframe>    
    </div>
    <div class="tab-pane fade" id="cartas" role="tabpanel" aria-labelledby="cartas-tab">
        <iframe name='cartas' width="100%" height="400" frameborder="0" scrolling="no" src="sql_busca_cartas.php?termo=<?php echo"$termo"; ?>"></iframe>
    </div>    
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