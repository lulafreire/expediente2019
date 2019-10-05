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


<div class='row' style="min-height: 425px;">
    <div class="col-12">

        <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">Nome</th>
            <th scope="col">Cargo</th>
            <th scope="col">Órgão</th>
            <th scope="col">Cidade</th>               
            <th scope="col"><i class='far fa-file-alt' title="Detalhes"></i></th>
            <th scope="col"><i class="fas fa-edit" title="Editar"></i></th>
        </tr>
        </thead>
        <tbody>
            
        <?php

        include_once("conn.php");
        include_once("functions.php");

        // Pesquisa os Ofícios Recebidos
        $contatos = mysqli_query($conn, "SELECT * from contatos");
        $qtContatos = mysqli_num_rows($contatos);

        // Paginação
        $pagina = (isset($_GET['pagina']))? $_GET['pagina']: 1;
                
        // Ofícios por página
        $contatosPorPagina = 7;

        // Total de páginas
        $totalPaginas = ceil($qtContatos / $contatosPorPagina);

        // Início
        $inicio = ($contatosPorPagina * $pagina) - $contatosPorPagina;

        // Selecionar para paginação
        $res_contatos = mysqli_query($conn, "SELECT * from contatos ORDER by nome ASC LIMIT $inicio, $contatosPorPagina");
        while($r=mysqli_fetch_array($res_contatos)) {

            $idContato = $r['id'];
            $nomeContato = utf8_encode($r['nome']);
            $cargoContato = utf8_encode($r['cargo']);
            $orgaoContato = utf8_encode($r['orgao']);
            $cidadeContato = utf8_encode($r['cidade']);
            
            echo "
            <tr>            
            <td><small>$nomeContato</small></td>
            <td><small>$cargoContato</small></td>
            <td><small>$orgaoContato</td>
            <td><small>$cidadeContato</td>
            <td><i class='far fa-file-alt' title='Detalhes' data-toggle='modal' data-target='#detalhaOficio$idContato'></i></td>
            <td><a href='editar_contato.php?id=$idContato' target='_parent' class='text-dark'><i class='fas fa-edit' title='Abrir Ofício'></i></a></td>
            </tr>";

            /*/* Modal Detalha Ofício
            echo "
            <div class='modal fade' id='detalhaOficio$id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered' role='document'>
                    <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLabel'>Ofício <strong>$numero/APSIRECE/INSS</strong></h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
                        <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <strong>Destinatário:</strong> $nomeDest<br>
                        <strong>Cargo:</strong> $cargo<br>
                        <strong>Órgão:</strong> $orgao<br>
                        <strong>Interessado:</strong> $interessado<br>
                        <strong>Emitido em:</strong> $dtEmissao<br>
                        <strong>Emissor:</strong> $nomeEmissor ($matricula)
                        <hr>
                        <strong>Referência:</strong> $textResposta
                        <hr>
                        <strong>Assunto:</strong> $assunto
                        <hr>           
                        <strong>ANEXOS</strong><br>";
                        // Verifica se possui anexo
                        $sqlAnexos = mysqli_query($conn, "SELECT * FROM anexos WHERE referencia = '$id'");
                        $qtAnexos = mysqli_num_rows($sqlAnexos);
                        if($qtAnexos =='') {

                            echo "<font class='text-muted'>Não possui anexos.</font>";
                    
                        } else {

                            while($a = mysqli_fetch_array($sqlAnexos)) {

                                $descricao = $a['descricao'];
                                $tam       = $a['tam'];
                                $idAnexo   = $a['id'];

                                echo "<a href='download_anexo.php?id=$idAnexo'>$descricao ($tam Mb)</a><br>";

                            }
                        }

                    echo "
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
                    </div>
                    </div>
                </div>
            </div>";*/

        } 
        
        ?>
        
            </tbody>
        </table>
    </div>
</div>

<div class='row'>
    <div class="col-12">
        <nav aria-label="Navegação de página exemplo">
            <ul class="pagination pagination-sm justify-content-end">
                <li class="page-item"><a class="page-link" href="sql_emitidos.php?pagina=1">Primeira</a></li>
                <?php 

                    for($i = 1; $i < $totalPaginas + 1; $i++) {

                        if($i == $pagina) {

                            echo "<li class='page-item active'><a class='page-link' href='sql_emitidos.php?pagina=$i'>$i</a></li>";

                        } else {

                            echo "<li class='page-item'><a class='page-link' href='sql_emitidos.php?pagina=$i'>$i</a></li>";

                        }
                        

                    }
                
                ?>                
                <li class="page-item"><a class="page-link" href="sql_emitidos.php?pagina=<?php echo "$totalPaginas"; ?>">Última</a></li>
            </ul>
        </nav>
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