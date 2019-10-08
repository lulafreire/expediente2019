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

    $id_contato      = $e['id'];
    $nome            = utf8_encode($e['nome']);
    $genero          = $e['genero'];
    $cargo           = utf8_encode($e['cargo']);
    $orgao           = utf8_encode($e['orgao']);
    $endereco        = utf8_encode($e['endereco']);
    $cep             = $e['cep'];
    $cidade          = utf8_encode($e['cidade']);

}

$txtResposta = "$id - Ofício nº $numero, de $nome";
$txtAssunto  = "Resposta ao Ofício nº $numero - Ass.: $assunto";

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
                                    <input id="contato" name="contato" value="<?php echo "$nome"; ?>" class="form-control form-control-sm" type="text" placeholder="Nome do Destinatário" readonly>
                                    <input id="id_contato" name="id_contato" value="<?php echo "$id_contato"; ?>" class="form-control form-control-sm" type="hidden">
                                </div>
                                <div class="col-2">
                                    <label for="genero" class="mb-0">Gênero</label> <a class="text-primary" data-toggle="tooltip" data-placement="right" title="O gênero é necessário para flexionar o Pronome de Tratamento nos ofícios emitidos para este contato."><i class="fas fa-question"></i></a>
                                    <input id="genero" name="genero" value="<?php echo "$genero"; ?>" class="form-control form-control-sm" type="text" placeholder="M/F" maxlength = "1" required onchange="verificarGenero()" readonly>                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="cargo" class="mb-0">Cargo</label>
                                    <input name="cargo" value="<?php echo "$cargo"; ?>" class="form-control form-control-sm" type="text" placeholder="Cargo" readonly>
                                    <label for="orgao" class="mb-0">Órgão</label>
                                    <input name="orgao" value="<?php echo "$orgao"; ?>" class="form-control form-control-sm" type="text" placeholder="Órgão" readonly>
                                    <label for="endereco" class="mb-0">Endereço</label>
                                    <input name="endereco" value="<?php echo "$endereco"; ?>" class="form-control form-control-sm" type="text" placeholder="Endereço" readonly>
                                </div>
                            </div>                    
                        </div>                
                    </div>                                                     
                    <div class="row">
                        <div class="col-4">
                            <label for="cep" class="mb-0">CEP</label>
                            <input name="cep" value="<?php echo "$cep"; ?>" class="form-control form-control-sm" type="text" placeholder="CEP" readonly>
                        </div>
                        <div class="col-8">
                            <label for="cidade" class="mb-0">Cidade</label>
                            <input name="cidade" value="<?php echo "$cidade"; ?>" class="form-control form-control-sm" type="text" placeholder="Cidade" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="interessado" class="mb-0">Interessado</label>
                            <input id="interessado" name="interessado" value="<?php echo "$interessado"; ?>" class="form-control form-control-sm" type="text" placeholder="Interessado" readonly>                                
                        </div>
                    </div>                                                              
                </div>
                <div class="col-7">
                    <div class="row">
                        <div class="col-6">
                            <label for="resposta" class="mb-0">Resposta ao Ofício</label>
                            <input id="resposta" name="resposta" value="<?php echo "$txtResposta"; ?>" class="form-control form-control-sm mb-2" type="text" placeholder="Informe se é resposta a algum ofício" readonly>
                        </div>
                        <div class="col-6">
                            <label for="assunto" class="mb-0">Assunto</label>
                            <input id="assunto" name="assunto" value="<?php echo "$txtAssunto"; ?>" class="form-control form-control-sm" type="text" placeholder="Informe o assunto" readonly>
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