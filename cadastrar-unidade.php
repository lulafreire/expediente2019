
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Expediente:Cadastro de Unidade</title>

    <!-- Principal CSS do Bootstrap -->
    <link href="node_modules/bootstrap/compiler/bootstrap.css" rel="stylesheet">

    <!-- Fonts Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- Estilos customizados para esse template -->
    <link href="node_modules/bootstrape/compiler/signin.css" rel="stylesheet">

    <!-- Autocomplete -->
    <style>
    * {
        box-sizing: border-box;
    }
    
   
    
    /*the container must be positioned relative:*/
    .autocomplete {
        position: relative;
        display: inline-block;
    }
    
    input {
        border: 1px solid transparent;
        background-color: #ffffff;
        padding: 10px;
        font-size: 16px;
    }
    
    input[type=text] {
        background-color: #ffffff;
        width: 100%;
    }
    
    input[type=submit] {
        background-color: DodgerBlue;
        color: #fff;
        cursor: pointer;
    }
    
    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        /*position the autocomplete items to be the same width as the container:*/
        top: 100%;
        left: 0;
        right: 0;
    }
    
    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff; 
        border-top: 1px solid #d4d4d4;
        border-bottom: 1px solid #d4d4d4; 
    }
    
    /*when hovering an item:*/
    .autocomplete-items div:hover {
        background-color: #e9e9e9; 
    }
    
    /*when navigating through the items using the arrow keys:*/
    .autocomplete-active {
        background-color: DodgerBlue !important; 
        color: #ffffff; 
    }
    </style>

    <?php include("mascara_data.php"); 
    
    // Mensagens de erro
    if(isset($_GET['erro'])) {

        $erro = $_GET['erro'];

        switch($erro) {

            case 1: $msgErro = "Não foi possível enviar o e-mail. Envie a chave para a Unidade manualmente."; 
            break;            

        }
    }

    // Mensagens de sucesso
    if(isset($_GET['msg'])) {

        $msg = $_GET['msg'];

        switch($msg) {

            case 1: $msgSucesso = "A chave de acesso foi enviada para o e-mail da Unidade cadastrada.";
            break;

        }
    }
    
    ?>


</script>

</head>

  <body class="text-center" style="background-color: #0f3d5e;">
<center>
    
    <form class="form-signin col-8 text-light" method="post" action="gravar-unidade.php">
      <img class="mb-4 my-3" src="img/brasao.png">
      <h1 class="h3 mb-3 font-weight-normal text-light">Cadastro <b>Expediente</b></h1>
      
        <div class="row">
            <div class="col-2">
                <input name="codigo" type="text" class="form-control mb-3" onkeypress="formatar_mascara(this, '##.###.###')" maxlength="10" placeholder="Código OL" required autofocus>
            </div>
            <div class="col-8">
                <input name="nome" type="text" class="form-control mb-3" placeholder="Nome da Unidade" required>
            </div>
            <div class="col-2">
                <input name="sigla" type="text" class="form-control mb-3" placeholder="Sigla" required>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <input name="end" type="text" class="form-control mb-3" placeholder="Endereço" required>
            </div>
            <div class="col-2">
                <input name="cep" type="text" class="form-control mb-3" onkeypress="formatar_mascara(this, '##.###-###')" maxlength="10" placeholder="CEP" required>
            </div>
            <div class="col-4">
                <input name="cidade" type="text" class="form-control mb-3" placeholder="Cidade-UF" required>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <input name="tel" type="text" class="form-control mb-3" onkeypress="formatar_mascara(this, '##-####-####')" placeholder="DDD-Telefone" required>
            </div>
            <div class="col-3">
                <input name="voip" type="text" class="form-control mb-3" onkeypress="formatar_mascara(this, '####-####')" placeholder="VoIP" required>
            </div>
            <div class="col-6">
                <input name="email" type="text" class="form-control mb-3" placeholder="e-mail" required>
            </div>
        </div>

        <div class="row">
            <div class="col-6 mt-2">
                <button name="cadastrar" id="cadastrar" class="col-6 btn btn-lg btn-success btn-block text-center" type="submit">Cadastrar</button>
            </div>
            </form> 
            <div class="col-6 mt-2">
                <a href="index.php" id="login" class="col-6 btn btn-lg btn-primary btn-block text-center">Login</a>
            </div>        
        </div>
          
      <p class="mt-3 mb-3 text-muted"><i class="far fa-copyright fa-flip-horizontal"></i> 2019, Lula Freire (luiz.aoliveira)</p>


<?php

    if(isset($_GET['erro'])){

        echo "
        <div class='alert alert-warning col-8' role='alert'>
            <i class='fas fa-exclamation-triangle text-danger'></i> <strong>Erro!</strong> $msgErro
        </div>";

    }
    
    if(isset($_GET['msg'])) {

        echo "
        <div class='alert alert-success col-8' role='alert'>
        <i class='fas fa-check'></i> <strong>Sucesso!</strong> $msgSucesso
        </div>";
    }

?>

</center>


    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="node_modules/popper.js/dist/umd/popper.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="js/jquery.sticky.js"></script>
    
    <!-- jQuery easing -->
    <script src="js/jquery.easing.1.3.min.js"></script>
    
    <!-- Main Script -->
    <script src="js/main.js"></script>

    <script src="js/app.js"></script>
    <script src="js/app.min.js"></script>
    <script src="js/demo.js"></script>
</body>
</html>