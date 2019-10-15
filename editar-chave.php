<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include("conn.php");
                        
// Pesquisa os dados do contato
$sqlUnidade = mysqli_query($conn, "SELECT * FROM unidades WHERE cod='$codUnidade'");
while($c=mysqli_fetch_array($sqlUnidade)){

    $nome = utf8_encode($c['nome']);
    $sigla = $c['sigla'];
    $endereco = utf8_encode($c['end']);   
    $cidade = utf8_encode($c['cidade']);
    $telefone = $c['tel'];
    $email = $c['email'];
    $voip  = $c['voip'];
}

if(isset($_GET['chave'])) {

    $chave = $_GET['chave'];

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

  <style type="text/css">
        label {
            font-weight: bold;
            font-size: 12px;
        }
    </style>

    <div class="container-fluid mt-2">
    
        <h6 style="border-bottom: 1px solid #C0C0C0; padding: 6px"><i class="fas fa-key"></i> Alterar CHAVE DE ACESSO</h6>
        
        <?php

            if(isset($chave)) {
            
                echo "
                <div class='col-6 alert alert-success' role='alert'>
                    <i class='fas fa-check'></i> Chave atualizada com sucesso! Nova chave: <b>$chave</b>
                </div>";
            
            } else {            
        
        ?>               
        
            <div class="col-6">

                <div class="row mb-2">
                    <div class="col-6">
                        <form id="form1" name="form1" method="post" action="grava-chave.php">                
                        <label for="chave" class="mb-0">Chave de Acesso</label>
                        <input type="text" name="chave" class="form-control form-control-sm" placeholder="Escolha uma nova chave">
                        <small class="text-muted"><i>(*) Deixe vazia caso deseje deixar o acesso livre</i></small>
                    </div>                    
                </div>                                
                <div class="row mt-2">
                    <div class="col-6">
                        <input type="submit" class="btn btn-success btn-sm mr-auto" value="Concluir">
                    </div></form>
                </div>
            
            </div>

            <?php
            
                }

            ?>

    </div>    
                           

    <!-- JavaScript (Opcional) -->
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
            CKEDITOR.replace( 'txtArtigo', {tabSpaces: 25});
    </script>
  </body>
</html>