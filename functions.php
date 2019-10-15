<?php
use \Lula\DB\Sql;
use \Lula\Query;

function diaDaSemana()
{
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    echo strftime('%A, %d de %B de %Y', strtotime('today'));

}

function dataExtenso($data) {

	$m = explode("/", $data);
	$dia = $m[0];
	$mes = $m[1];
	$ano = $m[2];

	switch($mes)
	{
		case 1: $mes = "janeiro";
		break;
		case 2: $mes = "fevereiro";
		break;
		case 3: $mes = "março";
		break;
		case 4: $mes = "abril";
		break;
		case 5: $mes = "maio";
		break;
		case 6: $mes = "junho";
		break;
		case 7: $mes = "julho";
		break;
		case 8: $mes = "agosto";
		break;
		case 9: $mes = "setembro";
		break;
		case 10: $mes = "outubro";
		break;
		case 11: $mes = "novembro";
		break;
		case 12: $mes = "dezembro";
		break;
	}

	return "$dia de $mes de $ano";

}

function retiraAcentos($string){
	$acentos  =  'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
	$sem_acentos  =  'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
	$string = strtr($string, utf8_decode($acentos), $sem_acentos);
	return utf8_decode($string);
 }

function pronomeDeTratamento($cargo, $genero) {

	$tags = 'juiz,juiza,juiz de direito,juiza de direito,juiz do trabalho,juiza do trabalho,juiz titular,juiza titular,juiz substituto,juiza substituta,presidente da republica,vice-presidente da republica,presidente do congresso nacional,ministro de estado,secretario-executivo,presidente do supremo tribunal federal,deputado federal,deputado estadual,oficial-general das forças armadas,embaixador,senador,senador da republica,ministro';
	$tagsArray = explode(',', $tags);
	$termo = utf8_encode(strtolower(retiraAcentos($cargo)));

	if (in_array($termo, $tagsArray)) {

		if($genero =='M' or $genero =='m') {
			  
			$vocativo = 'A Sua Excelência o Senhor|Excelentíssimo Senhor '. utf8_encode($cargo);
		  
		} else {

			$vocativo = 'A Sua Excelência a Senhora|Excelentíssima Senhora '. utf8_encode($cargo);
			
		}
		  
	} else {

		if($genero =='M' or $genero =='m') {
			  
			$vocativo = 'Ao Senhor|Senhor '. utf8_encode($cargo);
		  
		} else {

			$vocativo = 'À Senhora|Senhora '. utf8_encode($cargo);
			
		}
	}

	return $vocativo;

}

$ar_especial = array(   'á'=>'&aacute;',
                        'Á'=>'&Aacute;',
                        'ã'=>'&atilde;',
                        'Ã'=>'&Atilde;',
                        'â'=>'&acirc;',
                        'Â'=>'&Acirc;',
                        'à'=>'&agrave;',
                        'À'=>'&Agrave;',
                        'é'=>'&eacute;',
                        'É'=>'&Eacute;',
                        'ê'=>'&ecirc;',
                        'Ê'=>'&Ecirc;',
                        'í'=>'&iacute;',
                        'Í'=>'&Iacute;',
                        'ó'=>'&oacute;',
                        'Ó'=>'&Oacute;',
                        'õ'=>'&otilde;',
                        'Õ'=>'&Otilde;',
                        'ô'=>'&ocirc;',
                        'Ô'=>'&Ocirc;',
                        'ú'=>'&uacute;',
                        'Ú'=>'&Uacute;',
                        'ç'=>'&ccedil;',
                        'Ç'=>'&Ccedil;',
                        ' '=>'&nbsp;',
                        '\&'=>'\&amp;',
                        'ˆ'=>'&circ;',
                        '˜'=>'&tilde;',
                        '¨'=>'&uml;',
                        '´'=>'&cute;',
                        '¸'=>'&cedil;',
                        '"'=>'&quot;',
                        '“'=>'&ldquo;',
                        '”'=>'&rdquo;',
                        '‘'=>'&lsquo;',
                        '’'=>'&rsquo;',
                        '‚'=>'&sbquo;',
                        '„'=>'&bdquo;',
                        'º'=>'&ordm;',
                        'ª'=>'&ordf;',
                        '‹'=>'&lsaquo;',
                        '›'=>'&rsaquo;',
                        '«'=>'&laquo;',
                        '»'=>'&raquo;',
                        '–'=>'&ndash;',
                        '—'=>'&mdash;',
                        '¯'=>'&macr;',
                        '…'=>'&hellip;',
                        '¦'=>'&brvbar;',
                        '•'=>'&bull;',
                        '‣'=>'&#8227;',
                        '¶'=>'&para;',
                        '§'=>'&sect;',
                        '©'=>'&copy;',
                        '®'=>'&reg',
                        'ü'=>'&uuml;',
                        'Ü'=>'&Uuml;',
                        "'"=>'&#39;',
                        '½'=>'&frac12;',
                        '⅓'=>'&#8531;',
                        '≠'=>'&ne;',
                        '≅'=>'&cong;',
                        '≤'=>'&le;',
                        '≥'=>'&ge;');

function conversao($palavra) { // converte texto normal para o formato html)
    global $ar_especial;
    return str_replace(array_keys($ar_especial), array_values($ar_especial), $palavra);
}

function desconversao($palavra) { // converte o formato html para texto normal
    global $ar_especial;
    return str_replace(array_values($ar_especial), array_keys($ar_especial), $palavra);
}

function tratar_nome ($string) {
	$string = mb_strtolower(trim(preg_replace("/\s+/", " ", $string)));//transformo em minuscula toda a sentença
	$palavras = explode(" ", $string);//explodo a sentença em um array
	$t =  count($palavras);//conto a quantidade de elementos do array
	for ($i=0; $i <$t; $i++){ //entro em um for limitando pela quantidade de elementos do array
		$retorno[$i] = ucfirst($palavras[$i]);//altero a primeira letra de cada palavra para maiuscula
			if($retorno[$i] == "Dos" || $retorno[$i] == "De" || $retorno[$i] == "Do" || $retorno[$i] == "Da" || $retorno[$i] == "E" || $retorno[$i] == "Das"):
				$retorno[$i] = mb_strtolower($retorno[$i]);//converto em minuscula o elemento do array que contenha preposição de nome próprio
			endif;  
	}
	return implode(" ", $retorno);
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

function anoEmissao($data)
{
	if(strstr($data, "/"))
	{
		$d = explode("/", $data);
		$r = "$d[2]";
	}
	else
	{
		$d = explode("-", $data);
		$r = "$d[0]";

	}

	return $r;
}

function converteDataHora($dataHora){

	$dt = explode(" ", $dataHora);
	$data = $dt[0];
	$hora = $dt[1];

	if(strstr($data, "/"))
	{
		$d = explode("/", $data);
		$r = "$d[2]-$d[1]-$d[0] $hora";
	}
	else
	{
		$d = explode("-", $data);
		$r = "$d[2]/$d[1]/$d[0] $hora";

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

function formataNumero($numero)
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

		case 20: // Ação Judicial
			$n01 = substr($numero, 0, 7);
			$n02 = substr($numero, 7, 2);
			$n03 = substr($numero, 9, 4);
			$n04 = substr($numero, 13, 1);
			$n05 = substr($numero, 14, 2);
			$n06 = substr($numero, 16, 4);

			return $numero = "$n01-$n02.$n03.$n04.$n05.$n06";
		break;		

		default: // Outros formatos indefinidos
			return $numero;
		break;
	}
}


?>