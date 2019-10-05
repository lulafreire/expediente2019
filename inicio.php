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

<?php
include_once("conn.php");

// Pesquisa a quantidade de ofícios Recebidos e Emitidos
$sqlQtRec = mysqli_query($conn, "SELECT * FROM documentos WHERE tipo = '1' AND unidade = '$codUnidade'");
$qtRec    = mysqli_num_rows($sqlQtRec);

$sqlQtEmit = mysqli_query($conn, "SELECT * FROM documentos WHERE tipo = '0' AND unidade = '$codUnidade'");
$qtEmit    = mysqli_num_rows($sqlQtEmit);


?>
<div class='row'>
<div class='col-6'>
    <div class='card'>
        <div class='card-header'>
            <div class="row">
                <div class="col-11">
                    <i class='fas fa-file-alt'></i> Ofícios <b>Recebidos</b> (<?php echo "$qtRec"; ?>)
                </div>
                <div class="col-1">
                    <a href='sql-recebidos-nrpu.php' target='recebidos'><button class="btn btn-sm btn-light" type="submit" data-toggle="tooltip" data-placement="right" title="Clique aqui para pesquisar os ofícios não respondidos que estão com prazo ultrapassado."><i class='fas fa-check text-danger'></i></button></a>
                </div>
            </div>            
        </div>
        <div class='card-body' style="height:80vh; width:100vw;">
            <iframe name="recebidos" src="sql_recebidos.php" frameborder="0" scrolling="no" height="100%" width="47%"></iframe>  
        </div>
    </div>
</div>
<div class='col-6'>
    <div class='card'>
        <div class='card-header'>
        <i class='fas fa-file-alt'></i> Ofícios <b>Emitidos</b> (<?php echo "$qtEmit"; ?>)
        </div>
        <div class='card-body' style="height:80vh; width:100vw;">
            <iframe name="emitidos" src="sql_emitidos.php" frameborder="0" scrolling="no" height="100%" width="47%"></iframe>  
        </div>
    </div>
</div>
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
  </body>
</html>