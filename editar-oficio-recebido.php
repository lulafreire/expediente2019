<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include("conn.php");
include("functions.php");

// Identifica o ofício a ser alterado
$id = $_GET['id'];

// Pesquisa a sigla da Unidade
$sqlSigla = mysqli_query($conn, "SELECT sigla FROM unidades WHERE cod = '$codUnidade'");
while($s=mysqli_fetch_array($sqlSigla)) {

    $sigla = $s['sigla'];

}

//Pesquisa os dados do ofício a ser alterado
$sqlOficio = mysqli_query($conn, "SELECT * FROM documentos WHERE id = '$id'");
while($o=mysqli_fetch_array($sqlOficio)) {

    $numero       = $o['numero'];
    $emissor      = $o['emissor'];
    $assunto      = utf8_encode($o['assunto']);
    $destinatario = $o['destinatario'];
    $interessado  = utf8_encode($o['interessado']);
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
    $cargo           = utf8_encode($e['cargo']);
    $orgao           = utf8_encode($e['orgao']);
    $endereco        = utf8_encode($e['endereco']);
    $cep             = $e['cep'];
    $cidade          = utf8_encode($e['cidade']);

}

// Verifica o anexo inicial
$sqlAnexos = mysqli_query($conn, "SELECT * FROM anexos WHERE referencia = '$id' ORDER BY id ASC LIMIT 1");
$resAnexos = mysqli_num_rows($sqlAnexos);
if($resAnexos=='') {

    $anexo = "
    <div class='row'>
        <div class='col-12'>
            <p class='text-muted'><strong>ANEXO:</strong> Ofício não foi anexado.</p>
        </div>
    </div>
    <div class='row'>
        <div class='col-12'>
            <label for='Anexo' class='mb-0'>Anexar Ofício Recebido</label>
            <input class='form-control-sm mb-2' type='file' name='arquivo'>
        </div>
    </div>";

} else {

    while($a = mysqli_fetch_array($sqlAnexos)) {

        $idAnexo   = $a['id'];
        $arquivo   = $a['arquivo'];
        $descricao = $a['descricao'];
        $tam       = $a['tam'];
        $anexo     = "
        <div class='row'>
            <div class='col-9'>
                <p><strong>ANEXO:</strong> <a title='$descricao'>$arquivo</a> ($tam Mb) <br><span class='text-muted mt-0'><i><small>* Para substituir o anexo, exclua este primeiro.</i></small></span></br></p>
            </div>
            <div class='col-3'>
                <button type='button' class='btn btn-light btn-sm'><a title='Donwload' href='download.php?url=$arquivo'><i class='fas fa-search text-dark'></i></a></button>
                <button type='button' class='btn btn-light btn-sm'><a title='Excluir' onclick=\"return confirm('Deseja realmente excluir este registro?')\" href='delete-anexo.php?idAnexo=$idAnexo&idOficio=$id&arquivo=$arquivo'><i class='far fa-trash-alt text-dark'></i></a></button>
            </div>            
        </div>";

    }

}

// Verifica se foi gravado como resposta a algum ofício
$sqlResposta = mysqli_query($conn, "SELECT * FROM documentos WHERE resposta = '$id'");
$resResposta = mysqli_num_rows($sqlResposta);

if($resResposta=='') {

    $oficioResposta = "";

} else {

    while($r=mysqli_fetch_array($sqlResposta)) {

        $idOfcioResposta = $r['id'];
        $numOficioResposta = $r['numero'];
        $dtOficioResposta  = $r['data'];
        $d = explode("-", $dtOficioResposta);
        $anoOficioResposta = $d[0];

        $oficioResposta = "$idOfcioResposta - Ofício nº $numOficioResposta/$anoOficioResposta/$sigla/INSS, de ". converteData($dtOficioResposta);
    }

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


  <!-- Preenchimento automático do formulário -->
  <script type='text/javascript'>
        $(document).ready(function(){
            $("input[name='destinatario']").blur(function(){
                var $nome_aluno = $("input[name='nome_aluno']");
                var $tratamento = $("input[name='tratamento']");
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
                    $tratamento.val( json.tratamento );
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
                    <form method="post" action="salvar-editar-oficio-recebido.php" enctype="multipart/form-data">
                    <input value="<?php echo "$numero"; ?>" name="numero" class="form-control form-control-sm" type="text" placeholder="Número" required>
                </div>                        
            </div>            
            <div class="row">
                <div class="col-8">
                    <label for="contato" class="mb-0">Emissor</label>
                    <input value="<?php echo "$nome"; ?>" id="contato" name="contato" class="form-control form-control-sm" type="text" placeholder="Nome do Emissor" readonly>
                    <input value="<?php echo "$id_emissor"; ?>" id="id_contato" name="id_contato" class="form-control form-control-sm" type="hidden">
                    <input value="<?php echo "$id"; ?>" id="id_oficio" name="id_oficio" class="form-control form-control-sm" type="hidden">
                </div>
                <div class="col-4">                    
                    <label for="cargo" class="mb-0">Cargo</label>
                    <input value="<?php echo "$cargo"; ?>" name="cargo" class="form-control form-control-sm" type="text" placeholder="Cargo" readonly>
                </div>
            </div>            
            <div class="row">
                <div class="col-12">
                    
                    <label for="orgao" class="mb-0">Órgão</label>
                    <input value="<?php echo "$orgao"; ?>" name="orgao" class="form-control form-control-sm" type="text" placeholder="Órgão" readonly>
                    <label for="endereco" class="mb-0">Endereço</label>
                    <input value="<?php echo "$endereco"; ?>" name="endereco" class="form-control form-control-sm" type="text" placeholder="Endereço" readonly>
                </div>
            </div>                                               
            <div class="row">
                <div class="col-4">
                    <label for="cep" class="mb-0">CEP</label>
                    <input value="<?php echo "$cep"; ?>" name="cep" class="form-control form-control-sm" type="text" placeholder="CEP" readonly>
                </div>
                <div class="col-8">
                    <label for="cidade" class="mb-0">Cidade</label>
                    <input value="<?php echo "$cidade"; ?>" name="cidade" class="form-control form-control-sm" type="text" placeholder="Cidade" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="emissao" class="mb-0">Emitido em</label>
                    <input value="<?php echo "$dtEmissao"; ?>" name="emissao" class="form-control form-control-sm" type="text" id="calendario1" autocomplete="off" placeholder="Emitido em">
                </div>
                <div class="col-4">
                    <label for="recebido" class="mb-0">Recebido em</label>
                    <input value="<?php echo "$dtRecebido"; ?>" name="recebido" class="form-control form-control-sm" type="text" id="calendario2" autocomplete="off" placeholder="Recebido em">
                </div>
                <div class="col-4">
                    <label for="prazo" class="mb-0">Prazo</label>
                    <input value="<?php echo "$dtPrazo"; ?>" name="prazo" class="form-control form-control-sm" type="text" id="calendario3" autocomplete="off" placeholder="Prazo"/>                                      
                </div>
            </div>            
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i><small> Não é possível alterar o emissor. Caso necessário, exclua o ofício e envie outro.</small>
            </div>
        </div>            
        <div class="col-7">
            <div class="row">
                <div class="col-6">
                    <label for="resposta" class="mb-0">Resposta ao Ofício</label>
                    <input value="<?php echo "$oficioResposta"; ?>" id="resposta" name="resposta" class="form-control form-control-sm mb-2" type="text" placeholder="Informe se é resposta a algum ofício">
                </div>
                <div class="col-6">
                    <label for="assunto" class="mb-0">Assunto</label>
                    <input value="<?php echo "$assunto"; ?>" id="assunto" name="assunto" class="form-control form-control-sm" type="text" placeholder="Informe o assunto">
                </div>                                          
            </div>
            <div class="row">
                <div class="col-12">
                    <input value="<?php echo "$interessado"; ?>" type="text" name="interessado" class="form-control form-control-sm" placeholder="Nome do interessado">
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