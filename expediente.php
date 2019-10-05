<?php
session_start();

$codUnidade = $_SESSION['codUnidade'];

// Não permite acesso à página se não fez o login
if($codUnidade=='') {

    Header("location:http://localhost/expediente2019/login.php");
    exit;

}

include("conn.php");

// Pesquisar nome da Unidade
$sqlUnidade = mysqli_query($conn, "SELECT * FROM unidades WHERE cod = '$codUnidade'");
while($u=mysqli_fetch_array($sqlUnidade)) {

    $nomeUnidade = utf8_encode($u['nome']);

}

include_once("functions.php");
include_once("conn.php");

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

        <nav class="navbar fixed navbar-expand-lg navbar-dark" style="background-color: #0f3d5e;">
            <a class="navbar-brand h1 mb-0" href="expediente.php"><img src="img/logo.png" valign="middle" alt=""> Expediente</a>
            <ul class="navbar-nav">
                <li class="nav-item">                    
                    <a class="nav-link" href="expediente.php"><i class="fas fa-home"></i> Início</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-file-alt"></i> Ofícios
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="receber-oficio.php" target="conteudo">Receber Ofício</a>
                        <a class="dropdown-item" href="novo-oficio.php" target="conteudo">Emitir Ofício</a>                                            
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-edit"></i> Modelos
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">                                                
                        <a class="dropdown-item" href="modelos_cartas.php" target="conteudo">Cartas</a>
                        <a class="dropdown-item" href="modelos_despachos.php" target="conteudo">Despachos</a>
                        <a class="dropdown-item" href="modelos_oficios.php" target="conteudo">Ofícios</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="criar_modelo.php" target="conteudo"><i class="fas fa-magic"></i> Criar Modelo</a>                        
                    </div>
                </li>                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-tie"></i> Contatos
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">                                                
                        <a class="dropdown-item" href="cadastrar_contato.php" target="conteudo">Cadastrar</a>
                        <a class="dropdown-item" href="sql_contatos.php" target='conteudo'>Exibir Todos</a>                        
                    </div>
                </li>
                <li class="nav-item">                    
                    <a class="nav-link" href="ajuda.php" target="conteudo" title="Ajuda"><i class="fas fa-question"></i></a>
                </li>                
            </ul>
            <div class="ui-widget"></div>
                <form class="form-inline ml-auto" action="busca.php" method="POST" target="conteudo">
                    <input id="q" size="40" name="q" type="search" class="form-control ml-2 mr-2" placeholder="Pesquisar Documentos">
                    <button class="btn btn-warning" type="submit">Ok</button>        
                </form>
            </div>
        </nav>
        <div class="container-fluid my-2">
            <div class="row">
                <div class="mx-2" style="height:80vh; width:100vw;">
                    <iframe name="conteudo" src="abas.php" frameborder="0" scrolling="auto" height="100%" width="100%"></iframe>
                </div>
            </div>
        </div>
        <!-- Navbar bottom -->
        <nav class="card-header border-top border-muted navbar fixed-bottom navbar-expand-lg navbar-light p-0">
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ml-2">
                    <li class="nav-item dropup">
                    <a class="ml-auto nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <small><i class="fas fa-map-marker-alt"></i> <?php echo "Sua Unidade: <strong>$codUnidade - $nomeUnidade</strong>"; ?></small>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">                    
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalMeusDados">Atualizar Dados da Unidade</a>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalTrocaSenha">Trocar Chave de Acesso</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="sair.php"><i class="far fa-times-circle text-danger"></i> Sair</a>
                    </div>
                </li>
                </ul>
            </div>
        </nav>

        
        
    

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