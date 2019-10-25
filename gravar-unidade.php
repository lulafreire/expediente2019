
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

    <?php 
    
    include("conn.php");
    
    // Recupera dados do formulário
    $codigo = $_POST['codigo'];
    $nome   = utf8_decode($_POST['nome']);
    $sigla  = $_POST['sigla'];
    $end    = utf8_decode($_POST['end']);
    $cep    = $_POST['cep'];
    $cidade = utf8_decode($_POST['cidade']);
    $tel    = $_POST['tel'];
    $voip   = $_POST['voip'];
    $email  = $_POST['email'];

    // Endereço completo
    $end = "$end - $cep - $cidade";
    
    // Gera uma chave a partir do Código OL combinado com a data
    $data = date('Ymdhis');
    $comb = $codigo.$data;
    $chave = substr(preg_replace("/[^0-9]/", "", md5($comb)), 0, 5);

    //Grava
    $grava = mysqli_query($conn, "INSERT into unidades (cod, nome, sigla, end, cidade, tel, email, voip, chave) VALUES ('$codigo','$nome','$sigla','$end','$cidade','$tel','$email','$voip','$chave')");
           
    ?>

</head>

<body style="background-color: #0f3d5e;">

<div class="row">
    <div class="container-fluid col-8 text-center">
        <img class="mb-4 my-3" src="img/brasao.png">
        <h1 class="h3 mb-3 font-weight-normal text-light">Cadastro <b>Expediente</b></h1>
    </div>    
</div>
<div class="row">
    <div class="container-fluid col-8 text-center">
        <div class="alert alert-success" role="alert">
            <?php echo "A unidade <b>$codigo -" . utf8_encode($nome) . "</b> foi cadastrada com sucesso!<br>Chave de Acesso: <b>$chave</b></br>"; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="container-fluid col-8 text-center">
        <a class="btn btn-primary" href="http://localhost/expediente2019/cadastrar-unidade.php" role="button">Cadastrar Nova Unidade</a>
        <a class="btn btn-info" href="http://localhost/expediente2019/enviar-chave.php?codigo=<?php echo "$codigo"; ?>" role="button"><i class="far fa-envelope"></i> Enviar chave por e-mail</a>
    </div>
</div>





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