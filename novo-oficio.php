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

    <!-- Verifica se o gênero foi corretamente informado M ou F-->
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

 <!-- Preenchimento automático do contato -->
 <script type='text/javascript'>
        $(document).ready(function(){
            $("input[name='contato']").blur(function(){
                var $nome = $("input[name='nome']");
                var $genero = $("input[name='genero']");
                //var $tratamento = $("input[name='tratamento']");
                var $cargo = $("input[name='cargo']");
                var $contato = $("input[name='contato']");
                var $id_contato = $("input[name='id_contato']");
                var $orgao = $("input[name='orgao']");
                var $endereco = $("input[name='endereco']");
                var $cep = $("input[name='cep']");
                var $cidade = $("input[name='cidade']");
                $.getJSON('function-contato.php',{ 
                    contato: $( this ).val() 
                },function( json ){
                    $nome.val( json.nome );
                    $genero.val( json.genero );
                    //$tratamento.val( json.tratamento );
                    $cargo.val( json.cargo );
                    $contato.val( json.nome );
                    $orgao.val( json.orgao );
                    $endereco.val( json.endereco );
                    $cep.val( json.cep );
                    $cidade.val( json.cidade );
                    $id_contato.val ( json.id_contato );
                });
            });
        });
    </script>

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

    <!-- Preenchimento automático do assunto -->
    <script type='text/javascript'>
        $(document).ready(function(){
            $("input[name='resposta']").blur(function(){
                var $assunto = $("input[name='assunto']");                
                $.getJSON('function_resposta.php',{ 
                    resposta: $( this ).val() 
                },function( json ){
                    $assunto.val( json.assunto );                    
                });
            });
        });
    </script>    

    <!-- Autocomplete Contato -->
    <script type="text/javascript">
        $(document).ready(function(){
            $("#contato").autocomplete({
                source: 'search-contato.php',
                minLength: 0,
            });
        });
    </script>

    <!-- Autocomplete Resposta -->
    <script type="text/javascript">
        $(document).ready(function(){
            $("#resposta").autocomplete({
                source: 'search_resposta.php',
                minLength: 0,
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
                        <div class="col-8">
                            <h6 style="border-bottom: 1px solid #C0C0C0; padding: 6px"><i class="far fa-file"></i> Emitir <b>OFÍCIO</b></h6>
                        </div>
                        <div class="col-4">
                            <form id="form1" name="form1" method="post" action="novo-html.php">
                            <input type="checkbox" name="numeracao" onClick="Habilitar(this,'campo1');"> <label for="numero">Nº Manual</label> <a class="text-primary" data-toggle="tooltip" data-placement="right" title="Caso existam ofícios físicos emitidos pela Unidade, habilite a numeração manual no primeiro ofício digital. Para os demais, a numeração será automática."><i class="fas fa-question"></i></a>
                            <input type="text" class="form-control form-control-sm" name="numero" id="campo1" disabled>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-10">
                                    <label for="contato" class="mb-0">Destinatário</label>
                                    <input id="contato" name="contato" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário" required>
                                    <input id="id_contato" name="id_contato" class="form-control form-control-sm" type="hidden">
                                </div>
                                <div class="col-2">
                                    <label for="genero" class="mb-0">Gênero</label> <a class="text-primary" data-toggle="tooltip" data-placement="right" title="O gênero é necessário para flexionar o Pronome de Tratamento nos ofícios emitidos para este contato."><i class="fas fa-question"></i></a>
                                    <input id="genero" name="genero" class="form-control form-control-sm" type="text" placeholder="M/F" maxlength = "1" required onchange="verificarGenero()">                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="cargo" class="mb-0">Cargo</label>
                                    <input name="cargo" class="form-control form-control-sm" type="text" placeholder="Cargo" required>
                                    <label for="orgao" class="mb-0">Órgão</label>
                                    <input name="orgao" class="form-control form-control-sm" type="text" placeholder="Órgão" required>
                                    <label for="endereco" class="mb-0">Endereço</label>
                                    <input name="endereco" class="form-control form-control-sm" type="text" placeholder="Endereço" required>
                                </div>
                            </div>                    
                        </div>                
                    </div>                                                     
                    <div class="row">
                        <div class="col-4">
                            <label for="cep" class="mb-0">CEP</label>
                            <input name="cep" class="form-control form-control-sm" type="text" placeholder="CEP" required>
                        </div>
                        <div class="col-8">
                            <label for="cidade" class="mb-0">Cidade</label>
                            <input name="cidade" class="form-control form-control-sm" type="text" placeholder="Cidade" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="interessado" class="mb-0">Interessado</label>
                            <input id="interessado" name="interessado" class="form-control form-control-sm" type="text" placeholder="Interessado">                                
                        </div>
                    </div>                                                              
                </div>
                <div class="col-7">
                    <div class="row">
                        <div class="col-6">
                            <label for="resposta" class="mb-0">Resposta ao Ofício</label>
                            <input id="resposta" name="resposta" class="form-control form-control-sm mb-2" type="text" placeholder="Informe se é resposta a algum ofício">
                        </div>
                        <div class="col-6">
                            <label for="assunto" class="mb-0">Assunto</label>
                            <input id="assunto" name="assunto" class="form-control form-control-sm" type="text" placeholder="Informe o assunto">
                        </div>                                                                  
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="dvCentro">
                                <textarea id="txtArtigo" name="txtArtigo"><?php echo "$textoModelo"; ?></textarea>
                            </div>
                        </div>                        
                    </div>                    
                    <div class="row">
                        <div class="col-6">
                            <label for="emissor" class="mb-0">Emissor</label>
                            <input id="emissor" name="emissor" class="form-control form-control-sm" type="text" placeholder="Nome do emissor">
                            <input id="id_emissor" name="id_emissor" class="form-control form-control-sm" type="hidden">
                        </div>
                        <div class="col-2">
                            <label for="matricula" class="mb-0">Matrícula</label>
                            <input id="matricula" name="matricula" class="form-control form-control-sm" type="text" placeholder="Matrícula">                                                        
                        </div>
                        <div class="col-4">
                            <label for="funcao_emissor" class="mb-0">Função</label>
                            <input id="funcao_emissor" name="funcao_emissor" class="form-control form-control-sm" type="text" placeholder="Função">                                                        
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