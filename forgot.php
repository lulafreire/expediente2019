
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>PortalAPS:Recupera Senha</title>

    <!-- Principal CSS do Bootstrap -->
    <link href="node_modules/bootstrap/compiler/bootstrap.css" rel="stylesheet">

    <!-- Fonts Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- Estilos customizados para esse template -->
    <link href="node_modules/bootstrape/compiler/signin.css" rel="stylesheet">

    
</head>

<?php include("mascara_data.php"); ?>

  <body class="text-center" style="background-color: #0f3d5e;">
<center>
    
    <form class="form-signin col-4 text-light" method="post" action="recuperar-chave.php">
      <img class="mb-4 my-3" src="img/brasao.png">
      <h1 class="h3 mb-3 font-weight-normal text-light">Recuperar Chave de Acesso <b>Expediente</b></h1>
      <label for="codigo" class="sr-only">Código</label>
      <input name="codigo" type="text" class="form-control mb-3" onkeypress="formatar_mascara(this, '##.###.###')" placeholder="Código da Unidade" required autofocus>
      <button name="login" id="login" class="btn btn-lg btn-primary btn-block" type="submit">Enviar</button>
    </form>        
      <p class="mt-3 mb-3 text-muted">&copy; 2019, Lula Freire (luiz.aoliveira)</p>
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