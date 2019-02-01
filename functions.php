<?php
use \Lula\DB\Sql;
use \Lula\Query;

function diaDaSemana()
{
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    echo strftime('%A, %d de %B de %Y', strtotime('today'));

}

function converteData($data)
{
	if(strstr($data, "/"))
	{
		$d = explode("/", $data);
		$r = "$d[2]-$d[1]-$d[0]";
	}
	else
	{
		$d = explode("-", $data);
		$r = "$d[2]/$d[1]/$d[0]";

	}

	return $r;
}

function reduzData($data)
{
	$d = explode("/", $data);
	$data = $d[0]."/".$d[1];
	return $data;
}

function converteComp($comp)
{
	$ano = substr($comp, 0, 4);
	$mes = substr($comp, 4, 2);
	$c = $mes."/".$ano;

	return $c;
}

function reverteComp($comp)
{
	$c = explode("/", $comp);
	$competencia = $c[1].$c[0];
	return $competencia;
}

function mesComp($competencia)
{
	$m = explode("/", $competencia);
	$mes = $m[0];
	$ano = substr($m[1], 2, 2);

	switch($mes)
	{
		case 1: $mes = "JAN/".$ano;
		break;
		case 2: $mes = "FEV/".$ano;
		break;
		case 3: $mes = "MAR/".$ano;
		break;
		case 4: $mes = "ABR/".$ano;
		break;
		case 5: $mes = "MAI/".$ano;
		break;
		case 6: $mes = "JUN/".$ano;
		break;
		case 7: $mes = "JUL/".$ano;
		break;
		case 8: $mes = "AGO/".$ano;
		break;
		case 9: $mes = "SET/".$ano;
		break;
		case 10: $mes = "OUT/".$ano;
		break;
		case 11: $mes = "NOV/".$ano;
		break;
		case 12: $mes = "DEZ/".$ano;
		break;
	}
	
	return $mes;
}

function hoje()
{
	return date('Y-m-d');
}

function decimal($num)
{
	$n = str_replace(',', '.', $num);
	return $n;
}

function minusculas($text)
{
	$t = strtolower($text);
	return $t;
}

function siglaEquipe($equipe)
{
	return $sigla = substr($equipe, 0, 1);
}

function nomeEquipe($equipe)
{
	$e = explode ("-", $equipe);
	return $nome = $e[1];
}

function limpaPesquisa($q)
{
	$q = str_replace(".", "", $q);
	$q = str_replace(",", "", $q);
	$q = str_replace("-", "", $q);
	$q = str_replace("/", "", $q);
	return $q;
}

function contarCaracteres($texto)
{
	$qtCaracteres = strlen($texto);
	return $restantes = 280 - $qtCaracteres;
}

function formataNumero($numero, $origem)
{
	$qNum = strlen($numero);

	switch($qNum)
	{
		case 10: //NB
			$n01 = substr($numero, 0, 3);
			$n02 = substr($numero, 3, 3);
			$n03 = substr($numero, 6, 3);
			$n04 = substr($numero, 9, 1);

			return $numero = "$n01.$n02.$n03-$n04";
		break;

		case 11: //NIT PIS PASEP
			$n01 = substr($numero, 0, 3);
			$n02 = substr($numero, 3, 5);
			$n03 = substr($numero, 8, 2);
			$n04 = substr($numero, 10, 1);

			return $numero = "$n01.$n02.$n03-$n04";
		break;

		case 17: //PROTOCOLO SIPPS ou CTC
			if($origem == 'CNIS')
			{
				$n01 = substr($numero, 0, 5);
				$n02 = substr($numero, 5, 6);
				$n03 = substr($numero, 11, 4);
				$n04 = substr($numero, 15, 2);

				return $numero = "$n01.$n02/$n03-$n04";
			}

			if($origem == 'CTC')
			{
				$n01 = substr($numero, 0, 8);
				$n02 = substr($numero, 8, 1);
				$n03 = substr($numero, 9, 5);
				$n04 = substr($numero, 14, 2);
				$n05 = substr($numero, 16, 1);

				return $numero = "$n01.$n02.$n03/$n04-$n05";
			}			
		break;

		default: // Outros formatos indefinidos
			return $numero;
		break;
	}
}


?>