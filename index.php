<?php

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
            <a class="navbar-brand h1 mb-0" href="dashboard"><img src="img/logo.png" valign="middle" alt=""> Expediente</a>
            <ul class="navbar-nav">
                <li class="nav-item">                    
                    <a class="nav-link" href="dashboard"><i class="fas fa-home"></i> Início</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-file-download"></i> Receber
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="conteudo">Ofício</a>
                        <a class="dropdown-item" href="unidades">Memorando</a>                        
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-edit"></i> Escrever
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="novo-oficio.php" target="conteudo">Ofício</a>
                        <a class="dropdown-item" href="unidades">Memorando</a>                        
                        <a class="dropdown-item" href="unidades">Carta</a>
                        <a class="dropdown-item" href="unidades">Despacho</a>                        
                    </div>
                </li>
                <li class="nav-item">                    
                    <a class="nav-link" href="equipe"><i class="fas fa-paperclip"></i> Anexos</a>
                </li>
                <li class="nav-item">                    
                    <a class="nav-link" href="equipe"><i class="fas fa-user-tie"></i> Contatos</a>
                </li>
            </ul>
            <div class="ui-widget"></div>
                <form class="form-inline ml-auto" action="arquivos" method="POST">
                    <input id="q" size="60" name="q" type="search" class="form-control ml-2 mr-2" placeholder="Pesquisar Documentos">
                    <button class="btn btn-warning" type="submit">Ok</button>        
                </form>
            </div>
        </nav>
        <div class="container-fluid my-2">
            <div class="row">
                <div class="mx-2" style="height:80vh; width:100vw;">
                    <iframe name="conteudo" src="inicio.php" frameborder="0" scrolling="no" height="100%" width="100%"></iframe>
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