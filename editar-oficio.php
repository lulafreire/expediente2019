<?php
include("conn.php");
include("functions.php");

// Identifica o ofício a ser alterado
$id = $_GET['id'];

//Pesquisa os dados do ofício a ser alterado
$sqlOficio = mysqli_query($conn, "SELECT * FROM documentos WHERE id = '$id'");
while($o=mysqli_fetch_array($sqlOficio)) {

    $numero       = $o['numero'];
    $emissor      = $o['emissor'];
    $assunto      = utf8_encode($o['assunto']);
    $destinatario = $o['destinatario'];
    $interessado  = $o['interessado'];
    $data         = $o['data'];
    $dtEmissao    = converteData($data);
    $recebido     = $o['recebido'];
    $dtRecebido   = converteData($recebido);
    $prazo        = $o['prazo'];
    $dtPrazo      = converteData($prazo);
    $resposta     = $o['resposta'];
    $resumo       = utf8_encode($o['texto']);

}

// Pesquisa os dados do emissor
$sqlEmissor = mysqli_query($conn, "SELECT * FROM contatos WHERE id='$emissor'");
while($e=mysqli_fetch_array($sqlEmissor)) {

    $nome            = utf8_encode($e['nome']);
    $tratamento      = utf8_encode($e['tratamento']);
    $cargo           = utf8_encode($e['cargo']);
    $orgao           = utf8_encode($e['orgao']);
    $endereco        = utf8_encode($e['endereco']);
    $cep             = $e['cep'];
    $cidade          = utf8_encode($e['cidade']);

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
                source: 'search_resposta_recebidos.php',
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
<div class="container-fluid">

    <div class="row">
        <div class="col-5">
            <div class="row">
                <div class="col-8">
                    <h6 style="border-bottom: 1px solid #C0C0C0; padding: 6px"><i class="far fa-edit"></i> Editar <b>OFÍCIO</b></h6>
                </div>
                <div class="col-4">
                    <form method="post" action="oficio_recebido.php" enctype="multipart/form-data">
                    <input value="<?php echo "$numero"; ?>" name="numero" class="form-control form-control-sm" type="text" placeholder="Número" required>
                </div>                        
            </div>            
            <div class="row">
                <div class="col-8">
                    <label for="destinatario" class="mb-0">Emissor</label>
                    <input value="<?php echo "$nome"; ?>" id="destinatario" name="destinatario" class="form-control form-control-sm" type="text" placeholder="Nome do Emissor">
                    <input value="<?php echo "$emissor"; ?>" id="id_destinatario" name="id_destinatario" class="form-control form-control-sm" type="hidden">
                </div>
                <div class="col-4">                    
                    <label for="cargo" class="mb-0">Cargo</label>
                    <input name="cargo" class="form-control form-control-sm" type="text" placeholder="Cargo" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label for="tratamento" class="mb-0">Forma de Tratamento</label>
                    <input value="<?php echo "$tratamento"; ?>" name="tratamento" class="form-control form-control-sm" type="text" placeholder="Será utilizada na resposta ao Ofício" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    
                    <label for="orgao" class="mb-0">Órgão</label>
                    <input name="orgao" class="form-control form-control-sm" type="text" placeholder="Órgão" readonly>
                    <label for="endereco" class="mb-0">Endereço</label>
                    <input name="endereco" class="form-control form-control-sm" type="text" placeholder="Endereço" readonly>
                </div>
            </div>                                               
            <div class="row">
                <div class="col-4">
                    <label for="cep" class="mb-0">CEP</label>
                    <input name="cep" class="form-control form-control-sm" type="text" placeholder="CEP" readonly>
                </div>
                <div class="col-8">
                    <label for="cidade" class="mb-0">Cidade</label>
                    <input name="cidade" class="form-control form-control-sm" type="text" placeholder="Cidade" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label for="emissao" class="mb-0">Emitido em</label>
                    <input name="emissao" class="form-control form-control-sm" type="text" id="calendario1" autocomplete="off" placeholder="Emitido em">
                </div>
                <div class="col-4">
                    <label for="recebido" class="mb-0">Recebido em</label>
                    <input name="recebido" class="form-control form-control-sm" type="text" id="calendario2" autocomplete="off" placeholder="Recebido em">
                </div>
                <div class="col-4">
                    <label for="prazo" class="mb-0">Prazo</label>
                    <input name="prazo" class="form-control form-control-sm" type="text" id="calendario3" autocomplete="off" placeholder="Prazo"/>                                      
                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    <label for="Anexo" class="mb-0">Anexar Ofício Recebido</label>
                    <input class="form-control-sm mb-2" type="file" name="arquivo">
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
                <div class="col-12">
                    <input type="text" name="interessado" class="form-control form-control-sm" placeholder="Nome do interessado">
                </div>                        
            </div>
            <div class="row">
                <div class="col">
                    <label for="assunto" class="mb-0">Resumo</label>
                    <div id="dvCentro">
                        <textarea id="txtArtigo" name="txtArtigo"><?php echo "$resumo"; ?></textarea>
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