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
    
        <h6 style="border-bottom: 1px solid #C0C0C0; padding: 6px"><i class="fas fa-magic"></i> Criar Modelo</h6>
        
        <div class="row">
        
            <div class="col-6">

                <div class="row mb-2">
                    <div class="col-4">
                        <form id="form1" name="form1" method="post" action="grava-modelo.php">                
                        <label for="assunto" class="mb-0">Tipo</label>
                        <select name="tipo" class="form-control form-control-sm">
                            <option value="OFICIO">Ofício</option>
                            <option value="CARTA">Carta</option>
                            <option value="DESPACHO">Despacho</option>
                        </select>
                    </div>
                    <div class="col-8">
                        <label for="titulo" class="mb-0">Título</label>
                        <input name="titulo" class="form-control form-control-sm" type="text" placeholder="Dê um título ao modelo" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div id="dvCentro">
                            <textarea id="txtArtigo" name="txtArtigo"></textarea>
                        </div>
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

                    if(isset($_GET['modelo'])) {

                        $modelo = $_GET['modelo'];
                        echo "
                        <iframe name='modelo' src='modelo_pdf.php?idModelo=$modelo' frameborder='1' scrolling='no' height='90%' width='100%'></iframe>";
                       
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