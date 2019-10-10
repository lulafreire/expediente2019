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

// Pesquisa a quantidade de ofícios Recebidos e Emitidos
$sqlQtRec = mysqli_query($conn, "SELECT * FROM documentos WHERE 
tipo = '1' AND unidade = '$codUnidade' AND interessado LIKE '%$termo%' OR 
tipo = '1' AND unidade = '$codUnidade' AND assunto LIKE _utf8 '%$termo%' COLLATE utf8_unicode_ci OR
tipo = '1' AND unidade = '$codUnidade' AND texto like '%$termo%'");
$qtRec    = mysqli_num_rows($sqlQtRec);

$sqlQtEmit = mysqli_query($conn, "SELECT * FROM documentos WHERE tipo = '0' AND unidade = '$codUnidade'");
$qtEmit    = mysqli_num_rows($sqlQtEmit);


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
      <a class="nav-link" title="Recebidos Com Resposta Atrasada" id="recebidos-atrasados-tab" data-toggle="tab" href="#recebidos-nrpu" role="tab" aria-controls="recebidos-nrpu" aria-selected="false"><i class='fas fa-check text-danger'></i></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="emitidos-tab" data-toggle="tab" href="#emitidos" role="tab" aria-controls="profile" aria-selected="false"><i class='fas fa-file-alt'></i> Ofícios <b>Emitidos</b> (<?php echo "$qtEmit"; ?>)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" title="Emitidos Não Respondidos" id="emitidos-atrasados-tab" data-toggle="tab" href="#emitidos-nrpu" role="tab" aria-controls="emitidos-nrpu" aria-selected="false"><i class='fas fa-check text-danger'></i></a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="recebidos" role="tabpanel" aria-labelledby="recebidos-tab">
        <iframe name='recebidos' width="100%" height="430" frameborder="0" scrolling="no" src="sql_recebidos.php"></iframe>
    </div>
    <div class="tab-pane fade" id="emitidos" role="tabpanel" aria-labelledby="emitidos-tab">
        <iframe name='emitidos' width="100%" height="430" frameborder="0" scrolling="no" src="sql_emitidos.php"></iframe>    
    </div>
    <div class="tab-pane fade" id="recebidos-nrpu" role="tabpanel" aria-labelledby="recebidos-atrasados-tab">
        <iframe name='recebidos-nrpu' width="100%" height="430" frameborder="0" scrolling="no" src="sql-recebidos-nrpu.php"></iframe>
    </div>
    <div class="tab-pane fade" id="emitidos-nrpu" role="tabpanel" aria-labelledby="emitidos-atrasados-tab">
        <iframe name='emitidos-nrpu' width="100%" height="430" frameborder="0" scrolling="no" src="sql_emitidos-nr.php"></iframe>
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