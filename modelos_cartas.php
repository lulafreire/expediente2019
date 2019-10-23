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
        <div class="col-6">

            <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Modelos de <b>CARTAS</b></th>                                       
                <th scope="col"><i class='fas fa-search' title='Visualizar'></i></th>
                <th scope="col"><i class="fas fa-check" title="Utilizar"></i></th>
                <th scope="col"><i class="fas fa-edit" title="Editar Modelo"></i></th>
                <th scope="col"><i class="fas fa-trash-alt" title="Excluir Modelo"></i></th>
            </tr>
            </thead>
            <tbody>
                
            <?php

            include_once("conn.php");
            include_once("functions.php");

            // Pesquisa os Modelos de Ofícios
            $modelos = mysqli_query($conn, "SELECT * FROM modelos WHERE tipo='CARTA'");
            $qtModelos = mysqli_num_rows($modelos);

            // Paginação
            $pagina = (isset($_GET['pagina']))? $_GET['pagina']: 1;
                    
            // Ofícios por página
            $modelosPorPagina = 7;

            // Total de páginas
            $totalPaginas = ceil($qtModelos / $modelosPorPagina);

            // Início
            $inicio = ($modelosPorPagina * $pagina) - $modelosPorPagina;

            // Selecionar para paginação
            $res_modelos = mysqli_query($conn, "SELECT * from modelos WHERE tipo='CARTA' ORDER by descricao ASC LIMIT $inicio, $modelosPorPagina");
            while($r=mysqli_fetch_array($res_modelos)) {

                $idModelo  = $r['id'];
                $descricao = utf8_encode($r['descricao']);            
                
                echo "
                <tr>            
                <td><small>$descricao</small></td>            
                <td><a href='modelo_pdf.php?idModelo=$idModelo' target='modelo' class='text-dark'><i class='fas fa-search' title='Visualizar'></i></a></td>
                <td><a href='nova-carta.php?idModelo=$idModelo' target='conteudo' class='text-dark'><i class='fas fa-check' title='Utilizar este modelo'></i></a></td>
                <td><a href='editar-modelo.php?idModelo=$idModelo' target='_self' class='text-dark'><i class='fas fa-edit' title='Editar modelo'></i></a></td>
                <td><a href='excluir-modelo.php?idModelo=$idModelo' target='_self' class='text-dark'><i class='fas fa-trash-alt' title='Excluir modelo'></i></a></td>
                </tr>";               

            } 
            
            ?>
            
                </tbody>
            </table>
        </div>
        <div class='col-6'>
            <iframe name="modelo" src="" frameborder="1" scrolling="no" height="100%" width="100%"></iframe>    
        </div>
    </div>

    <div class='row'>
        <div class="col-12">
            <nav aria-label="Navegação de página exemplo">
                <ul class="pagination pagination-sm justify-content">
                    <li class="page-item"><a class="page-link" href="modelos_cartas.php?pagina=1">Primeira</a></li>
                    <?php 

                        for($i = 1; $i < $totalPaginas + 1; $i++) {

                            if($i == $pagina) {

                                echo "<li class='page-item active'><a class='page-link' href='modelos_cartas.php?pagina=$i'>$i</a></li>";

                            } else {

                                echo "<li class='page-item'><a class='page-link' href='modelos_cartas.php?pagina=$i'>$i</a></li>";

                            }
                            

                        }
                    
                    ?>                
                    <li class="page-item"><a class="page-link" href="modelos_cartas.php?pagina=<?php echo "$totalPaginas"; ?>">Última</a></li>
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