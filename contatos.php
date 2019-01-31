<?php
include_once("conexao.php");

function retorna($destinatario, $conn)
{
    $query = "SELECT * FROM contatos WHERE id = '1' LIMIT 1";
    $resultado = mysqli_query($conn, $query);

    if($resultado->num_rows)
    {
        $row = mysqli_fetch_assoc($resultado);
        $valores['cargo'] = $row['cargo'];
        $valores['orgao'] = utf8_encode($row['orgao']);
       
    }
    else
    {
        $valores['orgao'] = '...';
        $valores['cargo'] = '...';
    }

    return json_encode($valores);
}

if(isset($_GET['destinatario']))
{
    echo retorna($_GET['destinatario'], $conn);
}

?>