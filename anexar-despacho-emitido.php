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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">

    <title>.:: Expediente ::.</title>
  </head>
  <body>

<div class="container-fluid">
    <div class="border-bottom">    
    <h5><i class="fas fa-paperclip"></i> Anexar documentos</h5>    
    </div>
    <div class="border-bottom py-2">
        <?php

            include_once("conn.php");
            include_once("functions.php");

            // Idenfica o Ofício
            $id = $_GET['id'];

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

            // Pesquisa os dados do despacho
            $sqlOficio = mysqli_query($conn, "SELECT * FROM documentos WHERE id = '$id'");
            while($o=mysqli_fetch_array($sqlOficio)) {

                $numero = $o['numero'];
                $ano    = anoEmissao($o['data']);
                $dtEmissao = converteData($o['data']);
                $idEmissor = $o['emissor'];
                $interessado  = $o['interessado'];                
            }
            
            echo "
            Despacho nº <b>$numero/$ano/$siglaUnidade/INSS</b> de $dtEmissao<br>
            Interessado: <b>$interessado</b> <br>";

        ?>        
    </div>
    <div class="border-bottom py-1">
        <strong>ANEXOS</strong>
    </div>
    <div class="border-bottom py-2">
            <?php
            
            // Pesquisa os anexos relacinados a este ofício
            $sqlAnexos = mysqli_query($conn, "SELECT * FROM anexos WHERE referencia ='$id' ORDER BY id ASC");
            while($a=mysqli_fetch_array($sqlAnexos)) {
                $idAnexo   = $a['id'];
                $arquivo   = $a['arquivo'];
                $descricao = utf8_encode($a['descricao']);
                $tam       = $a['tam'];
                $dtAnexo   = converteData($a['data']);                

                echo "
                <div class='row'>
                    <div class='col-9 border-bottom py-1'>
                        • $descricao <small class='text-muted'><i>($tam Mb) Anexado em $dtAnexo</i></small>
                    </div>
                    <div class='col-3 border-bottom py-1'>
                        <button type='button' class='btn btn-light btn-sm'><a title='Donwload' href='download.php?url=$arquivo'><i class='fas fa-search text-dark'></i></a></button>
                        <button type='button' class='btn btn-light btn-sm'><a title='Excluir' onclick=\"return confirm('Deseja realmente excluir este registro?')\" href='delete-anexo.php?idAnexo=$idAnexo&idOficio=$id&arquivo=$arquivo'><i class='far fa-trash-alt text-dark'></i></a></button>
                    </div>
                </div>";
            }
            
            ?>
    </div>
    <div class="border-bottom py-1">
        <i class="fas fa-plus text-success"></i> <strong>ANEXAR NOVO DOCUMENTO</strong>
    </div>
    <div class="border-bottom py-3">        
        <form method="post" action="salvar-anexo-despacho-emitido.php" enctype="multipart/form-data">
            <input type='hidden' name='id_despacho' value='<?php echo "$id"; ?>'><input type='text' size='60' maxlength='100' name='descricao' placeholder='Descrição'> <input class='form-control-sm mb-2' type='file' name='arquivo'> <input type="submit" class="btn btn-success btn-sm mr-auto" value="Enviar">
        </form>
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