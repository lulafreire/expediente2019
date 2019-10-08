<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include("conn.php");
                        
if(isset($_GET['id'])) {
    
    $id = $_GET['id'];

}

// Pesquisa os dados do contato
$sqlContato = mysqli_query($conn, "SELECT * FROM contatos WHERE id='$id'");
while($c=mysqli_fetch_array($sqlContato)){

    $nome = utf8_encode($c['nome']);
    $genero = $c['genero'];
    $cargo = utf8_encode($c['cargo']);
    $orgao = utf8_encode($c['orgao']);
    $endereco = utf8_encode($c['endereco']);
    $cep = $c['cep'];
    $cidade = utf8_encode($c['cidade']);
    $telefone = $c['telefone'];
    $email = $c['email'];
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
        function verificarGenero(){

            var texto=document.getElementById("genero").value;

            for (letra of texto){

                if (!isNaN(texto)){

                    alert("Não digite números");
                    document.getElementById("genero").value="";
                    return;

                }


                letraspermitidas="MmFf"

                var ok=false;
                for (letra2 of letraspermitidas ){

                    if (letra==letra2){

                        ok=true;

                    }


                 }


                 if (!ok){
                    alert("Digite M = Masculino ou F = Feminino");
                    document.getElementById("genero").value="";
                    return; 

                 }

            }

        }


    </script>

  <style type="text/css">
        label {
            font-weight: bold;
            font-size: 12px;
        }
    </style>

    <div class="container-fluid mt-2">
    
        <h6 style="border-bottom: 1px solid #C0C0C0; padding: 6px"><i class="fas fa-edit"></i> Editar CONTATO</h6>
        
        <div class="row">
        
            <div class="col-6">

                <div class="row mb-2">
                    <div class="col-10">
                        <form id="form1" name="form1" method="post" action="grava-editar-contato.php">                
                        <label for="nome" class="mb-0">Nome</label>
                        <input type="text" name="nome" value="<?php echo "$nome"; ?>" class="form-control form-control-sm" placeholder="Nome do Contato" required>
                        <input type="hidden" name="id" value="<?php echo "$id"; ?>">
                    </div>
                    <div class="col-2">
                        <label for="genero" class="mb-0">Gênero</label> <a class="text-primary" data-toggle="tooltip" data-placement="right" title="O gênero é necessário para flexionar o Pronome de Tratamento nos ofícios emitidos para este contato."><i class="fas fa-question"></i></a>
                        <input id="genero" name="genero" value="<?php echo "$genero"; ?>" class="form-control form-control-sm" type="text" placeholder="M/F" maxlength = "1" required onchange="verificarGenero()">                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="cargo" class="mb-0">Cargo</label>
                        <input name="cargo" value="<?php echo "$cargo"; ?>" class="form-control form-control-sm" type="text" placeholder="Cargo" required>
                        <label for="orgao" class="mb-0">Órgão</label>
                        <input name="orgao" value="<?php echo "$orgao"; ?>" class="form-control form-control-sm" type="text" placeholder="Órgão" required>
                        <label for="endereco" class="mb-0">Endereço</label>
                        <input name="endereco" value="<?php echo "$endereco"; ?>" class="form-control form-control-sm" type="text" placeholder="Endereço" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <label for="cep" class="mb-0">CEP</label>
                        <input name="cep" value="<?php echo "$cep"; ?>" class="form-control form-control-sm" type="text" placeholder="CEP" required>
                    </div>
                    <div class="col-8">
                        <label for="cidade" class="mb-0">Cidade</label>
                        <input name="cidade" value="<?php echo "$cidade"; ?>" class="form-control form-control-sm" type="text" placeholder="Cidade" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <label for="telefone" class="mb-0">DDD-TELEFONE</label>
                        <input name="telefone" value="<?php echo "$telefone"; ?>" class="form-control form-control-sm" type="text" placeholder="DDD-Telefone">
                    </div>
                    <div class="col-9">
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
            <div class="col-6">
                <?php

                    if(isset($_GET['contato'])) {

                        echo "
                        <div class='alert alert-success' role='alert'>
                            <i class='fas fa-check'></i> Dados atualizados com sucesso!
                        </div>";

                        include("conn.php");
                        
                        $contato = $_GET['contato'];

                        // Pesquisa os dados do contato
                        $sqlContato = mysqli_query($conn, "SELECT * FROM contatos WHERE id='$contato'");
                        while($c=mysqli_fetch_array($sqlContato)){

                            $nome = utf8_encode($c['nome']);
                            $cargo = utf8_encode($c['cargo']);
                            $orgao = utf8_encode($c['orgao']);
                            $endereco = utf8_encode($c['endereco']);
                            $cep = $c['cep'];
                            $cidade = utf8_encode($c['cidade']);
                            $telefone = $c['telefone'];
                            $email = $c['email'];
                        }

                        echo "
                        <b>Nome:</b> $nome<br>
                        <b>Cargo:</b> $cargo<br>
                        <b>Órgão:</b> $orgao<br>
                        <hr size='1' width='100%'>
                        <b>Endereço:</b> $endereco<br>
                        <b>CEP:</b> $cep <b>Cidade:</b> $cidade<br>
                        <hr size='1' width='100%'>
                        <b>Telefone:</b> $telefone</b><br>
                        <b>E-mail:</b> $email<br>";

                    }                   

                ?>
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