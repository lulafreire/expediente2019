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

if(isset($_GET['result'])) {

    $result = $_GET['result'];

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
    
        <h6 style="border-bottom: 1px solid #C0C0C0; padding: 6px"><i class="far fa-building"></i> Atualizar dados da UNIDADE</h6>
        
        <?php

            if(isset($result)) {
                echo "
                <div class='col-6 alert alert-success' role='alert'>
                    <i class='fas fa-check'></i> Dados atualizados com sucesso!
                </div>";
            }
        
        ?>               
        
            <div class="col-6">

                <div class="row mb-2">
                    <div class="col-10">
                        <form id="form1" name="form1" method="post" action="grava-editar-unidade.php">                
                        <label for="nome" class="mb-0">Nome</label>
                        <input type="text" name="nome" value="<?php echo "$nome"; ?>" class="form-control form-control-sm" placeholder="Nome do Contato" required>
                        
                    </div>
                    <div class="col-2">
                        <label for="sigla" class="mb-0">Sigla</label> <a class="text-primary" data-toggle="tooltip" data-placement="right" title="A sigla é utilizada na nomenclatura dos ofícios emitidos."><i class="fas fa-question"></i></a>
                        <input id="sigla" name="sigla" value="<?php echo "$sigla"; ?>" class="form-control form-control-sm" type="text" placeholder="M/F" required>                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">                        
                        <label for="endereco" class="mb-0">Endereço Completo</label>
                        <input name="endereco" value="<?php echo "$endereco"; ?>" class="form-control form-control-sm" type="text" placeholder="Endereço" required>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-3">
                        <label for="telefone" class="mb-0">DDD-TELEFONE</label>
                        <input name="telefone" value="<?php echo "$telefone"; ?>" class="form-control form-control-sm" type="text" placeholder="DDD-Telefone">
                    </div>
                    <div class="col-3">
                        <label for="voip" class="mb-0">VOIP</label>
                        <input name="voip" value="<?php echo "$voip"; ?>" class="form-control form-control-sm" type="text" placeholder="VoIP">
                    </div>
                    <div class="col-6">
                        <label for="email" class="mb-0">E-mail</label>
                        <input name="email" value="<?php echo "$email"; ?>" class="form-control form-control-sm" type="email" placeholder="E-mail">
                    </div>
                </div>                  
                <div class="row mt-2">
                    <div class="col-6">
                        <input type="submit" class="btn btn-success btn-sm mr-auto" value="Concluir">
                    </div></form>
                </div>
            
            </div>           

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