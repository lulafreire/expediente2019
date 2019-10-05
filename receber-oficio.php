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
                source: 'search_resposta_recebidos.php',
                minLength: 0,
            });
        });
    </script>

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

<div class="container-fluid">

    <div class="row">
        <div class="col-5">
            <div class="row">
                <div class="col-8">
                    <h6 style="border-bottom: 1px solid #C0C0C0; padding: 6px"><i class="far fa-file"></i> Receber <b>OFÍCIO</b></h6>
                </div>
                <div class="col-4">
                    <form method="post" action="salvar-oficio-recebido.php" enctype="multipart/form-data">
                    <label for = "numero">Nº</label><input name="numero" class="form-control form-control-sm" type="text" placeholder="Número" required>
                </div>                        
            </div>            
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-10">
                            <label for="contato" class="mb-0">Emissor</label>
                            <input id="contato" name="contato" class="form-control form-control-sm" type="text" placeholder="Nome do Emissor" required>
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
                            <input name="cargo" class="form-control form-control-sm" type="text" placeholder="Cargo do Emissor" required>
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
                <div class="col-4">
                    <label for="emissao" class="mb-0">Emitido em</label>
                    <input name="emissao" class="form-control form-control-sm" type="text" id="calendario1" autocomplete="off" placeholder="Emitido em" required>
                </div>
                <div class="col-4">
                    <label for="recebido" class="mb-0">Recebido em</label>
                    <input name="recebido" class="form-control form-control-sm" type="text" id="calendario2" autocomplete="off" placeholder="Recebido em" required>
                </div>
                <div class="col-4">
                    <label for="prazo" class="mb-0">Prazo</label>
                    <input name="prazo" class="form-control form-control-sm" type="text" id="calendario3" autocomplete="off" placeholder="Prazo"/>                                      
                </div>
            </div>
            <div class="row">
                <div class="col-12 container-fluid">
                    <div class="form-group mt-2">
                        <label for="Anexo" class="mb-0">Anexar Ofício Recebido</label>
                        <input class="form-control-sm mb-2" type="file" name="arquivo">
                        <p class="text-muted"><small><i>* É possível anexar documentos posteriormente através do menu "Anexos"</i></small></p>
                    </div>
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
                    <input id="assunto" name="assunto" class="form-control form-control-sm" type="text" placeholder="Informe o assunto" required>
                </div>                                          
            </div>
            <div class="row">
                <div class="col-12">
                    <input type="text" name="interessado" class="form-control form-control-sm" placeholder="Nome do interessado">
                </div>                        
            </div>
            <div class="row">
                <div class="col">
                    <label for="assunto" class="mb-0">Resumo</label>
                    <div id="dvCentro">
                        <textarea id="txtArtigo" name="txtArtigo" required></textarea>
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
            CKEDITOR.replace( 'txtArtigo' );
    </script>

    <!-- Datepicker -->
    <script>
        $(function() {
            $("#calendario1").datepicker({
                dateFormat: 'dd/mm/yy',
                showOtherMonths: true,
                selectOtherMonths: true,
                dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
                dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
                dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
                monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
            });
            $("#calendario2").datepicker({
                dateFormat: 'dd/mm/yy',
                showOtherMonths: true,
                selectOtherMonths: true,
                dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
                dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
                dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
                monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
            });
            $("#calendario3").datepicker({
                dateFormat: 'dd/mm/yy',
                showOtherMonths: true,
                selectOtherMonths: true,
                dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
                dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
                dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
                monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
            });
        });
    </script>

  </body>
</html>