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
                <th scope="col"><i class="far fa-trash-alt" title="Excluir"></i></th>
            </tr>
            </thead>
            <tbody>
                
            <?php

            // Pesquisa os Contatos
            $contatos = mysqli_query($conn, "SELECT * from contatos WHERE unidade ='$codUnidade'");
            $qtContatos = mysqli_num_rows($contatos);

            // Paginação
            $pagina = (isset($_GET['pagina']))? $_GET['pagina']: 1;
                    
            // Contatos por página
            $contatosPorPagina = 7;

            // Total de páginas
            $totalPaginas = ceil($qtContatos / $contatosPorPagina);

            // Início
            $inicio = ($contatosPorPagina * $pagina) - $contatosPorPagina;

            // Selecionar para paginação
            $res_contatos = mysqli_query($conn, "SELECT * from contatos WHERE unidade ='$codUnidade' ORDER by nome ASC LIMIT $inicio, $contatosPorPagina");
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
                <td><a href='detalha-contato.php?id=$idContato' target='_self' class='text-dark'><i class='far fa-file-alt' title='Ver detalhes'></i></a></td>
                <td><a href='editar-contato.php?id=$idContato' target='_self' class='text-dark'><i class='fas fa-edit' title='Editar contato'></i></a></td>
                <td><a href='excluir-contato.php?id=$idContato' target='_self' class='text-dark'><i class='far fa-trash-alt' title='Excluir contato'></i></a></td>
                </tr>";               

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