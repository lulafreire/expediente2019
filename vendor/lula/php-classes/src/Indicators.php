<?php 
namespace Lula;
use \Lula\DB\Sql;
use \Lula\Mailer;
use \Lula\Model;

class Indicators {

    // IMA-GDASS
    public static function imaGdass()
    {
        $sql = new Sql();
        $imaGdass12 = $sql->select("SELECT SQL_CALC_FOUND_ROWS * from tb_imagdass WHERE unidade = :lotacao ORDER BY competencia DESC limit 12", array(
            ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
        ));

        $registros = $sql->select("SELECT FOUND_ROWS() AS total");

        if($registros[0]['total']<=1)
        {
            if($registros[0]['total']==0)
            {
                return [
                    "imaGdass12"=>array(),
                    "imaGdassAtual"=>0,
                    "imaGdassAnt"=>0,
                    "iconeImaGdass"=>"far fa-pause-circle"
                ];
            }
            else
            {
                $imaGdassAtual = $sql->select("SELECT * from tb_imagdass WHERE unidade = :lotacao ORDER BY competencia DESC limit 1", array(
                    ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
                ));
                
                return [
                    "imaGdass12"=>array_reverse($imaGdass12, true),
                    "imaGdassAtual"=>$imaGdassAtual,
                    "imaGdassAnt"=>0,
                    "iconeImaGdass"=>"far fa-pause-circle"
                ];
            }
        }
        else
        {
            $imaGdassAtual = $sql->select("SELECT * from tb_imagdass WHERE unidade = :lotacao ORDER BY competencia DESC limit 1", array(
                ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
            ));
    
            $imaGdassAnt = $sql->select("SELECT * from tb_imagdass WHERE unidade = :lotacao and competencia < :compImaGdassAtual ORDER BY competencia DESC limit 1", array(
                ":lotacao"=>$_SESSION[User::SESSION]['lotacao'],
                ":compImaGdassAtual" => $imaGdassAtual[0]['competencia']
            ));
    
            if($imaGdassAtual[0]['indicador']>$imaGdassAnt[0]['indicador']) 
            {
                $iconeImaGdass = "far fa-arrow-alt-circle-up";
            } 
            if($imaGdassAtual[0]['indicador']<$imaGdassAnt[0]['indicador']) 
            {
                $iconeImaGdass = "far fa-arrow-alt-circle-down";
            }
            if($imaGdassAtual[0]['indicador']==$imaGdassAnt[0]['indicador'])
            {
                $iconeImaGdass = "far fa-pause-circle";
            }
    
            return [
                "imaGdass12"=>array_reverse($imaGdass12, true),
                "imaGdassAtual"=>$imaGdassAtual,
                "imaGdassAnt"=>$imaGdassAnt,
                "iconeImaGdass"=>$iconeImaGdass
            ];
        }               

    }
    
    //IIB
    public static function iib()
    {
        $sql = new Sql();
        $iib12 = $sql->select("SELECT SQL_CALC_FOUND_ROWS * from tb_iib WHERE unidade = :lotacao ORDER BY competencia DESC limit 12", array(
            ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
        ));

        $registros = $sql->select("SELECT FOUND_ROWS() AS total");

        if($registros[0]['total']<=1)
        {
            if($registros[0]['total']==0)
            {
                return [
                    "iib12"=>array(),
                    "iibAtual"=>0,
                    "iibAnt"=>0,
                    "iconeiib"=>"far fa-pause-circle"
                ];
            }
            else
            {
                $iibAtual = $sql->select("SELECT * from tb_iib WHERE unidade = :lotacao ORDER BY competencia DESC limit 1", array(
                    ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
                ));
                
                return [
                    "iib12"=>array_reverse($iib12, true),
                    "iibAtual"=>$iibAtual,
                    "iibAnt"=>0,
                    "iconeiib"=>"far fa-pause-circle"
                ];
            }
        }        
        else
        {

            $iibAtual = $sql->select("SELECT * from tb_iib WHERE unidade = :lotacao ORDER BY competencia DESC limit 1", array(
                ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
            ));

            $iibAnt = $sql->select("SELECT * from tb_iib WHERE unidade = :lotacao and competencia < :compiibAtual ORDER BY competencia DESC limit 1", array(
                ":lotacao"=>$_SESSION[User::SESSION]['lotacao'],
                ":compiibAtual" => $iibAtual[0]['competencia']
            ));

            if($iibAtual[0]['indicador']>$iibAnt[0]['indicador']) 
            {
                $iconeiib = "far fa-arrow-alt-circle-up";
            } 
            if($iibAtual[0]['indicador']<$iibAnt[0]['indicador']) 
            {
                $iconeiib = "far fa-arrow-alt-circle-down";
            }
            if($iibAtual[0]['indicador']==$iibAnt[0]['indicador'])
            {
                $iconeiib = "far fa-pause-circle";
            }

            return [
                "iib12"=>array_reverse($iib12, true),
                "iibAtual"=>$iibAtual,
                "iibAnt"=>$iibAnt,
                "iconeiib"=>$iconeiib
            ];
        }        

    } 

    // Tarefas
    public static function tarefas()
    {
        $sql = new Sql();
        $tarefas12 = $sql->select("SELECT SQL_CALC_FOUND_ROWS * from tb_tarefas WHERE unidade = :lotacao ORDER BY competencia DESC limit 12", array(
            ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
        ));

        $registros = $sql->select("SELECT FOUND_ROWS() AS total");

        if($registros[0]['total']<=1)
        {
            if($registros[0]['total']==0)
            {
                return [
                    "tarefas12"=>array(),
                    "tarefasAtual"=>0,
                    "tarefasAnt"=>0,
                    "iconetarefasconcluidas"=>"far fa-pause-circle",
                    "iconetarefaspendentes"=>"far fa-pause-circle"
                ];
            }
            else
            {
                $tarefasAtual = $sql->select("SELECT * from tb_tarefas WHERE unidade = :lotacao ORDER BY competencia DESC limit 1", array(
                    ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
                ));
                
                return [
                    "tarefas12"=>array_reverse($tarefas12, true),
                    "tarefasAtual"=>$tarefasAtual,
                    "tarefasAnt"=>0,
                    "iconetarefasconcluidas"=>"far fa-pause-circle",
                    "iconetarefaspendentes"=>"far fa-pause-circle"
                ];
            }
        }
        else
        {

            $tarefasAtual = $sql->select("SELECT * from tb_tarefas WHERE unidade = :lotacao ORDER BY competencia DESC limit 1", array(
                ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
            ));

            $tarefasAnt = $sql->select("SELECT * from tb_tarefas WHERE unidade = :lotacao and competencia < :comptarefasAtual ORDER BY competencia DESC limit 1", array(
                ":lotacao"=>$_SESSION[User::SESSION]['lotacao'],
                ":comptarefasAtual" => $tarefasAtual[0]['competencia']
            ));

            if($tarefasAtual[0]['concluidas']>$tarefasAnt[0]['concluidas']) 
            {
                $iconetarefasconcluidas = "far fa-arrow-alt-circle-up";
            } 
            if($tarefasAtual[0]['concluidas']<$tarefasAnt[0]['concluidas']) 
            {
                $iconetarefasconcluidas = "far fa-arrow-alt-circle-down";
            }
            if($tarefasAtual[0]['concluidas']==$tarefasAnt[0]['concluidas'])
            {
                $iconetarefasconcluidas = "far fa-pause-circle";
            }

            if($tarefasAtual[0]['pendentes']>$tarefasAnt[0]['pendentes']) 
            {
                $iconetarefaspendentes = "far fa-arrow-alt-circle-up";
            } 
            if($tarefasAtual[0]['pendentes']<$tarefasAnt[0]['pendentes']) 
            {
                $iconetarefaspendentes = "far fa-arrow-alt-circle-down";
            }
            if($tarefasAtual[0]['pendentes']==$tarefasAnt[0]['pendentes'])
            {
                $iconetarefaspendentes = "far fa-pause-circle";
            }

            return [
                "tarefas12"=>array_reverse($tarefas12, true),
                "tarefasAtual"=>$tarefasAtual,
                "tarefasAnt"=>$tarefasAnt,
                "iconetarefasconcluidas"=>$iconetarefasconcluidas,
                "iconetarefaspendentes"=>$iconetarefaspendentes
            ];        
        }

    } 

    // Represados
    public static function represados()
    {
        $sql = new Sql();
        $represados12 = $sql->select("SELECT SQL_CALC_FOUND_ROWS * from tb_represados WHERE unidade = :lotacao ORDER BY data DESC limit 12", array(
            ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
        ));

        $registros = $sql->select("SELECT FOUND_ROWS() AS total");

        if($registros[0]['total']<=1)
        {
            if($registros[0]['total']==0)
            {
                return [
                    "represados12"=>array(),
                    "represadosAtual"=>0,
                    "represadosAnt"=>0,
                    "iconerepresados"=>"far fa-pause-circle"
                ];
            }
            else
            {
                $represadosAtual = $sql->select("SELECT * from tb_represados WHERE unidade = :lotacao ORDER BY data DESC limit 1", array(
                    ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
                ));
                
                return [
                    "represados12"=>array_reverse($represados12, true),
                    "represadosAtual"=>$represadosAtual,
                    "represadosAnt"=>0,
                    "iconerepresados"=>"far fa-pause-circle"
                ];
            }
        }
        
        /*if($registros[0]['total']==0)
        {
            return [
                "represados12"=>array(),
                "represadosAtual"=>0,
                "represadosAnt"=>0,
                "iconerepresados"=>"far fa-pause-circle"
            ];
        }*/
        else
        {
            $represadosAtual = $sql->select("SELECT * from tb_represados WHERE unidade = :lotacao ORDER BY data DESC limit 1", array(
                ":lotacao"=>$_SESSION[User::SESSION]['lotacao']
            ));

            $represadosAnt = $sql->select("SELECT * from tb_represados WHERE unidade = :lotacao and data < :dtrepresadosAtual ORDER BY data DESC limit 1", array(
                ":lotacao"=>$_SESSION[User::SESSION]['lotacao'],
                ":dtrepresadosAtual" => $represadosAtual[0]['data']
            ));

            if($represadosAtual[0]['indicador']>$represadosAnt[0]['indicador']) 
            {
                $iconerepresados = "far fa-arrow-alt-circle-up";
            } 
            if($represadosAtual[0]['indicador']<$represadosAnt[0]['indicador']) 
            {
                $iconerepresados = "far fa-arrow-alt-circle-down";
            }
            if($represadosAtual[0]['indicador']==$represadosAnt[0]['indicador'])
            {
                $iconerepresados = "far fa-pause-circle";
            }

            return [
                "represados12"=>array_reverse($represados12, true),
                "represadosAtual"=>$represadosAtual,
                "represadosAnt"=>$represadosAnt,
                "iconerepresados"=>$iconerepresados
            ];        
        }
    } 
    
    public static function atualizaRepresados($data, $adm, $pm_as, $lotacao)
    {
        $sql = new Sql();

        // Verifica se a data já existe no banco de dados
        $results = $sql->select("SELECT data from tb_represados WHERE unidade = :lotacao and data = :data", array(
            ":lotacao"=>$lotacao,
            ":data"=>converteData($data)
        ));

        if(!$results)
        {
            $sql->query("INSERT into tb_represados (data, adm, pm_as, indicador, unidade) VALUES (:data, :adm, :pm_as, :indicador, :lotacao)", array(
                ":data"=>converteData($data),
                ":adm"=>$adm,
                ":pm_as"=>$pm_as,
                ":indicador"=>$adm + $pm_as,
                ":lotacao"=>$lotacao
            ));
        }
        else
        {
            $indicador = $pm_as + $adm;
            if($indicador == 0)
            {
                $sql->query("DELETE FROM tb_represados WHERE data = :data AND unidade = :lotacao", array(
                    ":data"=>converteData($data),
                    ":lotacao"=>$lotacao
                ));
            }
            else
            {
                $sql->query("UPDATE tb_represados set adm = :adm, pm_as = :pm_as, indicador = :indicador WHERE data = :data AND unidade = :lotacao", array(
                    ":data"=>converteData($data),
                    ":adm"=>$adm,
                    ":pm_as"=>$pm_as,
                    ":indicador"=>$adm + $pm_as,
                    ":lotacao"=>$lotacao 
                ));
            }
            
        }
    }

    public static function atualizaTarefas($competencia, $concluidas, $pendentes, $lotacao)
    {
        $sql = new Sql();

        // Verifica se a competência já existe no banco de dados
        $results = $sql->select("SELECT competencia from tb_tarefas WHERE unidade = :lotacao and competencia = :competencia", array(
            ":lotacao"=>$lotacao,
            ":competencia"=>reverteComp($competencia)
        ));

        if(!$results)
        {
            $mes = mesComp($competencia);

            $sql->query("INSERT into tb_tarefas (competencia, mes, concluidas, pendentes, unidade) VALUES (:competencia, :mes, :concluidas, :pendentes, :lotacao)", array(
                ":competencia"=>reverteComp($competencia),
                ":mes"=>$mes,
                ":concluidas"=>$concluidas,
                ":pendentes"=>$pendentes,
                ":lotacao"=>$lotacao
            ));
        }
        else
        {
            $soma = $concluidas + $pendentes;
            if($soma == 0)
            {
                $sql->query("DELETE FROM tb_tarefas WHERE competencia = :competencia AND unidade = :lotacao", array(
                    ":competencia"=>reverteComp($competencia),
                    ":lotacao"=>$lotacao
                ));
            }
            else
            {
                $sql->query("UPDATE tb_tarefas set concluidas = :concluidas, pendentes = :pendentes WHERE competencia = :competencia AND unidade = :lotacao", array(
                    ":competencia"=>reverteComp($competencia),
                    ":concluidas"=>$concluidas,
                    ":pendentes"=>$pendentes,
                    ":lotacao"=>$lotacao
                ));
            }
            
        }
    }

    public static function atualizaiib($competencia, $indice, $lotacao)
    {
        $sql = new Sql();

        // Verifica se a competência já existe no banco de dados
        $results = $sql->select("SELECT competencia from tb_iib WHERE unidade = :lotacao and competencia = :competencia", array(
            ":lotacao"=>$lotacao,
            ":competencia"=>reverteComp($competencia)
        ));

        if(!$results)
        {
            $mes = mesComp($competencia);

            $sql->query("INSERT into tb_iib (competencia, mes, indicador, unidade) VALUES (:competencia, :mes, :indice, :lotacao)", array(
                ":competencia"=>reverteComp($competencia),
                ":mes"=>$mes,
                ":indice"=>$indice,
                ":lotacao"=>$lotacao
            ));
        }
        else
        {
            if($indice == 0)
            {
                $sql->query("DELETE FROM tb_iib WHERE competencia = :competencia AND unidade = :lotacao", array(
                    ":competencia"=>reverteComp($competencia),
                    ":lotacao"=>$lotacao
                ));
            }
            else
            {

                $sql->query("UPDATE tb_iib set indicador = :indice WHERE competencia = :competencia AND unidade = :lotacao", array(
                    ":competencia"=>reverteComp($competencia),
                    ":indice"=>$indice,
                    ":lotacao"=>$lotacao
                ));
            }
            
        }
    }

    public static function atualizaImaGdass($competencia, $indice, $lotacao)
    {
        $sql = new Sql();

        // Verifica se a competência já existe no banco de dados
        $results = $sql->select("SELECT competencia from tb_imagdass WHERE unidade = :lotacao and competencia = :competencia", array(
            ":lotacao"=>$lotacao,
            ":competencia"=>reverteComp($competencia)
        ));

        if(!$results)
        {
            $mes = mesComp($competencia);

            $sql->query("INSERT into tb_imagdass (competencia, mes, indicador, unidade) VALUES (:competencia, :mes, :indice, :lotacao)", array(
                ":competencia"=>reverteComp($competencia),
                ":mes"=>$mes,
                ":indice"=>$indice,
                ":lotacao"=>$lotacao
            ));
        }
        else
        {
            if($indice == 0)
            {
                $sql->query("DELETE FROM tb_imagdass WHERE competencia = :competencia AND unidade = :lotacao", array(
                    ":competencia"=>reverteComp($competencia),
                    ":lotacao"=>$lotacao
                ));
            }
            else
            {

                $sql->query("UPDATE tb_imagdass set indicador = :indice WHERE competencia = :competencia AND unidade = :lotacao", array(
                    ":competencia"=>reverteComp($competencia),
                    ":indice"=>$indice,
                    ":lotacao"=>$lotacao
                ));
            }
            
        }
    }

}
 ?>