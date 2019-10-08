<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include("conn.php");
include("functions.php");

// Pesquisa os dados da unidade logada
$sqlUnidade = mysqli_query($conn, "SELECT * FROM unidades WHERE cod = '$codUnidade'");
while($u=mysqli_fetch_array($sqlUnidade)) {

    $nomeUnidade  = utf8_encode($u['nome']);
    $siglaUnidade = $u['sigla'];
    $endUnidade   = utf8_encode($u['end']);
    $telUnidade   = $u['tel'];
    $emailUnidade = $u['email'];
    $cidadeUnidade= utf8_encode($u['cidade']);

}
                        
if(isset($_GET['id'])) {
    
    $id = $_GET['id'];
    $contato = $id;

}

// Pesquisa os dados do contato
$sqlContato = mysqli_query($conn, "SELECT * FROM contatos WHERE id='$contato'");
while($c=mysqli_fetch_array($sqlContato)){

    $nome = utf8_encode($c['nome']);
    $genero = $c['genero'];
    $cargo = utf8_encode($c['cargo']);
    $orgao = utf8_encode($c['orgao']);
    $endereco = utf8_encode($c['endereco']);
    $cep = $c['cep'];
    $cidade = utf8_encode($c['cidade']);
    $telefone = $c['telefone'];
    $email = $c['email'];
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

  <style type="text/css">
        label {
            font-weight: bold;
            font-size: 12px;
        }
    </style>

    <div class="container-fluid mt-2">
    
        <h6 style="border-bottom: 1px solid #C0C0C0; padding: 6px"><i class="fas fa-user"></i> detalhes do CONTATO</h6>
        
        <div class="row">
        
            <div class="col-6">
                <?php
                echo "
                <b>Nome:</b> $nome<br>
                <b>Cargo:</b> $cargo<br>
                <b>Órgão:</b> $orgao<br>
                <hr size='1' width='100%'>
                <b>Endereço:</b> $endereco<br>
                <b>CEP:</b> $cep <b>Cidade:</b> $cidade<br>
                <hr size='1' width='100%'>
                <b>Telefone:</b> $telefone</b><br>
                <b>E-mail:</b> $email<br>";
                ?>
            </div>
            <div class="col-6">
                <b>Últimos Ofícios Recebidos deste Contato</b><br>
                <?php
                // Pesquisa os 5 ofícios mais recentes recebidos do contato selecionado
                $sqlRecebidos = mysqli_query($conn, "SELECT * FROM documentos WHERE tipo ='1' AND emissor = '$contato'  AND unidade = '$codUnidade' ORDER BY data DESC LIMIT 5");
                $qtRecebidos = mysqli_num_rows($sqlRecebidos);
                if($qtRecebidos=='') {

                    echo "<font class='text-muted'><i>Não foram recebidos ofícios emitidos por este contato.</i></font>";
                
                } else {

                    while($o=mysqli_fetch_array($sqlRecebidos)) {

                        $id           = $o['id'];
                        $numero       = $o['numero'];
                        $data         = $o['data'];
                        $dtEmissao    = converteData($data);
                        $anoEmissao   = anoEmissao($dtEmissao);
                        $assunto      = utf8_encode($o['assunto']); 

                        echo "<a href='detalha-oficio-recebido.php?id=$id' target='_self' title='Ass.: $assunto'>• Ofício nº $numero/$anoEmissao, de $dtEmissao</a><br>";
                    }
                }


                ?>
                <hr size='1' width='100%'>
                <b>Últimos Ofícios Emitidos para este Contato</b><br>
                <?php
                // Pesquisa os 5 ofícios mais recentes emitidos para o contato selecionado
                $sqlEmitidos = mysqli_query($conn, "SELECT * FROM documentos WHERE tipo ='0' AND destinatario = '$contato' AND unidade ='$codUnidade' ORDER BY data DESC LIMIT 5");
                $qtEmitidos = mysqli_num_rows($sqlEmitidos);
                if($qtEmitidos=='') {

                    echo "<font class='text-muted'><i>Não foram emitidos ofícios para este contato.</i></font>";
                
                } else {

                    while($e=mysqli_fetch_array($sqlEmitidos)) {

                        $id           = $e['id'];
                        $numero       = $e['numero'];
                        $data         = $e['data'];
                        $dtEmissao    = converteData($data);
                        $anoEmissao   = anoEmissao($dtEmissao);
                        $assunto      = utf8_encode($e['assunto']); 

                        echo "<a href='detalha-oficio-emitido.php?id=$id' target='_self' title='Ass.: $assunto'>• Ofício nº $numero/$anoEmissao/$siglaUnidade/INSS, de $dtEmissao</a><br>";
                    }
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