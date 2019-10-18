<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include("conn.php");
include("functions.php");

// Identifica o despacho a ser editado
$id = $_GET['id'];

//Pesquisa os dados do ofício a ser alterado
$sqlOficio = mysqli_query($conn, "SELECT * FROM documentos WHERE id = '$id'");
while($o=mysqli_fetch_array($sqlOficio)) {

    $numero       = $o['numero'];
    $emissor      = $o['emissor'];
    $assunto      = utf8_encode($o['assunto']);    
    $interessado  = utf8_encode($o['interessado']);
    $data         = $o['data'];
    $dtEmissao    = converteData($data);    
    $resumo       = utf8_encode($o['texto']);

    $r = explode("|", $resumo);
    $referencia =$r[0];
    $texto = $r['1'];

}

// Pesquisa o arquivo HTML relativo ao ofício selecionado
$sqlHtml = mysqli_query($conn, "SELECT * FROM oficios_html WHERE referencia = '$id'");
while($h=mysqli_fetch_array($sqlHtml)) {

    $arquivoHtml = $h['arquivo'];
}

// Pesquisa os dados do emissor
$sqlEmissor = mysqli_query($conn, "SELECT * FROM usuarios WHERE id = '$emissor'");
while($n = mysqli_fetch_array($sqlEmissor)) {

    $idEmissor     = $n['id'];
    $nomeEmissor   = utf8_encode($n['nome']);   
    $matEmissor    = utf8_encode($n['matricula']);
    $funcaoEmissor = utf8_encode($n['funcao']);
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

    
  <!-- Utilizando Modelos -->
  <?php
    include_once("conn.php");
    include_once("functions.php");

    if(isset($_GET['idModelo'])) {

        $idModelo = $_GET['idModelo'];
        $sqlModelo = mysqli_query($conn, "SELECT texto FROM modelos WHERE id = '$idModelo'");
        while($m = mysqli_fetch_array($sqlModelo)) {

            $textoModelo = utf8_encode($m['texto']);

        }

    } else {

        $textoModelo = "";

    }
    
  ?>

    <!-- Preenchimento automático do emissor -->
    <script type='text/javascript'>
        $(document).ready(function(){
            $("input[name='emissor']").blur(function(){
                var $emissor = $("input[name='emissor']");
                var $id_emissor = $("input[name='id_emissor']");
                var $matricula = $("input[name='matricula']"); 
                var $funcao_emissor = $("input[name='funcao_emissor']"); 
                var $cargo_emissor = $("input[name='cargo_emissor']");                 
                $.getJSON('function_emissor.php',{ 
                    emissor: $( this ).val() 
                },function( json ){
                    $emissor.val( json.emissor );
                    $matricula.val( json.matricula );
                    $funcao_emissor.val( json.funcao_emissor );
                    $cargo_emissor.val( json.cargo_emissor );
                    $id_emissor.val( json.id_emissor );                    
                });
            });
        });
    </script>          

    <!-- Autocomplete Emissor -->
    <script type="text/javascript">
        $(document).ready(function(){
            $("#emissor").autocomplete({
                source: 'search_emissor.php',
                minLength: 0,
            });
        });
    </script>

    <!-- Habilitar/Desabilitar numeração manual -->
    <script>
        function Habilitar(chk,campo) {
            var cmp = document.getElementById(campo);
            if(chk.checked)
            cmp.disabled = false;
            else
            cmp.disabled = true;
        }
    </script>   
        
        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-5">
                    <div class="row">
                        <div class="col-12">
                            <h6 style="border-bottom: 1px solid #C0C0C0; padding: 6px"><i class="fas fa-file-signature"></i> Editar <b>DESPACHO</b></h6>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                <form id="form1" name="form1" method="post" action="editar-despacho-html.php">
                                    <label for="referencia" class="mb-0">Referência</label>
                                    <input id="referencia" value="<?php echo "$referencia"; ?>" name="referencia" class="form-control form-control-sm" type="text" placeholder="NB/SIPPS/PROTOCOLO" required>                                    
                                    <input id="id_despacho" value="<?php echo "$id"; ?>" name="id_despacho" type="hidden">
                                    <input id="arquivoHtml" value="<?php echo "$arquivoHtml"; ?>" name="arquivoHtml" type="hidden">
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="interessado" class="mb-0">Interessado</label>
                                    <input id="interessado" value="<?php echo "$interessado"; ?>" name="interessado" class="form-control form-control-sm" type="text" placeholder="Nome do interessado" required>                                    
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="assunto" class="mb-0">Assunto</label>
                                    <input id="assunto" value="<?php echo "$assunto"; ?>" name="assunto" class="form-control form-control-sm" type="text" placeholder="Descrever o assunto" required>                                    
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-12">
                                <label for="emissor" class="mb-0">Emissor</label>
                                    <input id="emissor" value="<?php echo "$nomeEmissor"; ?>" name="emissor" class="form-control form-control-sm" type="text" placeholder="Nome do emissor">
                                    <input id="id_emissor" value="<?php echo "$emissor"; ?>" name="id_emissor" class="form-control form-control-sm" type="hidden">                                    
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="matricula" class="mb-0">Matrícula</label>
                                    <input id="matricula" value="<?php echo "$matEmissor"; ?>" name="matricula" class="form-control form-control-sm" type="text" placeholder="Matrícula">                                                        
                                </div>
                                <div class="col-8">
                                    <label for="funcao_emissor" class="mb-0">Função</label>
                                    <input id="funcao_emissor" value="<?php echo "$funcaoEmissor"; ?>" name="funcao_emissor" class="form-control form-control-sm" type="text" placeholder="Função">                                                        
                                </div>                                
                            </div>                             
                        </div>                
                    </div>                                                                                 
                </div>
                <div class="col-7">                    
                    <div class="row">
                        <div class="col">
                            <div id="dvCentro">
                                <textarea id="txtArtigo" name="txtArtigo"><?php echo "$texto"; ?></textarea>
                            </div>
                        </div>                        
                    </div>                                        
                    <div class="row mt-2">
                        <div class="col-6">
                            <input type="submit" class="btn btn-success btn-sm mr-auto" value="Concluir">
                        </div></form>
                    </div>
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