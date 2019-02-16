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

  <!-- Preenchimento automático do formulário -->
  <script type='text/javascript'>
        $(document).ready(function(){
            $("input[name='destinatario']").blur(function(){
                var $nome_aluno = $("input[name='nome_aluno']");
                var $cargo = $("input[name='cargo']");
                var $destinatario = $("input[name='destinatario']");
                var $id_destinatario = $("input[name='id_destinatario']");
                var $orgao = $("input[name='orgao']");
                var $endereco = $("input[name='endereco']");
                var $cep = $("input[name='cep']");
                var $cidade = $("input[name='cidade']");
                $.getJSON('function.php',{ 
                    destinatario: $( this ).val() 
                },function( json ){
                    $nome_aluno.val( json.nome_aluno );
                    $cargo.val( json.cargo );
                    $destinatario.val( json.nome_aluno );
                    $orgao.val( json.orgao );
                    $endereco.val( json.endereco );
                    $cep.val( json.cep );
                    $cidade.val( json.cidade );
                    $id_destinatario.val ( json.id_destinatario );
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

    

    <!-- Autocomplete Destinatario -->
    <script type="text/javascript">
        $(document).ready(function(){
            $("#destinatario").autocomplete({
                source: 'search.php',
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
        
        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-4">
                    <div class="row">
                        <h6><i class="far fa-file"></i> Novo <b>OFÍCIO</b></h6>
                        <hr size="1" width="100%">
                    </div>
                    <div class="row">
                        <div class="form-group col mx-0">
                            <form method="post" action="oficio_pdf.php">
                                <label for="destinatario">Destinatário</label>
                                <input id="destinatario" name="destinatario" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                <input id="id_destinatario" name="id_destinatario" class="form-control form-control-sm" type="hidden">
                                <label for="cargo">Cargo</label>
                                <input name="cargo" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                <label for="orgao">Órgão</label>
                                <input name="orgao" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                <label for="endereco">Endereço</label>
                                <input name="endereco" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="cep">CEP</label>
                                        <input name="cep" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                    </div>
                                    <div class="col-6">
                                        <label for="cidade">Cidade</label>
                                        <input name="cidade" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário">
                                    </div>
                                </div>                                                                
                                <label for="tratamento">Forma de Tratamento</label>
                                <select class="form-control form-control-sm" name="tratamento" id="tratamento">
                                    <option value="Prezado(a) Sr(a)">Prezado(a) Sr(a)</option>
                                    <option value="Ilmo(a). Sr(a)">Ilmo(a) Sr(a)</option>
                                    <option value="Exmo(a). Sr(a)">Exmo(a) Sr(a)</option>
                                </select>                                                
                        </div>                        
                    </div>                    
                </div>
                <div class="col-8">
                    <div class="row">
                        <div class="col-6">
                            <label for="resposta">Resposta ao Ofício</label>
                            <input id="resposta" name="resposta" class="form-control form-control-sm mb-2" type="text" placeholder="Informe se é resposta a algum ofício">
                        </div>
                        <div class="col-6">
                            <label for="assunto">Assunto</label>
                            <input id="assunto" name="assunto" class="form-control form-control-sm" type="text" placeholder="Informe o assunto">
                        </div>                                          
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="dvCentro">
                                <textarea id="txtArtigo" name="txtArtigo"></textarea>
                            </div>
                        </div>                        
                    </div>                    
                    <div class="row">
                        <div class="col-6">
                            <label for="emissor">Emissor</label>
                            <input id="emissor" name="emissor" class="form-control form-control-sm" type="text" placeholder="Nome do emissor">
                            <input id="id_emissor" name="id_emissor" class="form-control form-control-sm" type="hidden">
                        </div>
                        <div class="col-2">
                            <label for="matricula">Matrícula</label>
                            <input id="matricula" name="matricula" class="form-control form-control-sm" type="text" placeholder="Matrícula">                                                        
                        </div>
                        <div class="col-4">
                            <label for="funcao_emissor">Função</label>
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
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="ui/jquery-ui.js"></script>
    <script src="node_modules/popper.js/dist/popper.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script>
            CKEDITOR.replace( 'txtArtigo' );
    </script>
  </body>
</html>