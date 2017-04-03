<?php
// Fun��o apra filtrar SQL
function remove_remarks($sql) {

	$lines = explode("\n", $sql);

	$sql = "";
	$linecount = count($lines);
	$output = "";

	for ($i = 0; $i < $linecount; $i++) {

		if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {

			if (@$lines[$i][0] != "#") {
				$output .= $lines[$i] . "\n";
			} else {
				$output .= "\n";
			}


			$lines[$i] = "";
		}
	}

return $output;

}

function split_sql_file($sql, $delimiter) {

	$tokens = explode($delimiter, $sql);

	$sql = "";
	$output = array();
	$matches = array();

	$token_count = count($tokens);
	for ($i = 0; $i < $token_count; $i++) {

	if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {

			$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
			$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
			$unescaped_quotes = $total_quotes - $escaped_quotes;

		if (($unescaped_quotes % 2) == 0) {

			$output[] = $tokens[$i];
			$tokens[$i] = "";
			
		} else {

				$temp = $tokens[$i] . $delimiter;
				$tokens[$i] = "";

				$complete_stmt = false;

				for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {

						$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
						$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
						$unescaped_quotes = $total_quotes - $escaped_quotes;

						if (($unescaped_quotes % 2) == 1) {

						$output[] = $temp . $tokens[$j];

						$tokens[$j] = "";
						$temp = "";

						$complete_stmt = true;
						$i = $j;
					} else {

						$temp .= $tokens[$j] . $delimiter;
						$tokens[$j] = "";
					}

				}
			}
		}
	}

	return $output;
}

// Fun��o b�sica para tratamento de erros
function tratar_erro($erro,$msg_erro) {

	if($erro == "mysql1") {

	$erro = '<style type="text/css">
	.texto_erro {
		background-color:#F2BBA5;
		padding-top:10px;
		padding-bottom:10px;
		width:100%;
		text-align:center;
		color: #923614;
		font-family:Arial;
		font-size:12px;
		font-weight:normal;
	}
	</style>

	<div class="texto_erro" align="center"><h3><strong>Erro ao conectar-se ao servidor mysql.</strong></h3><strong>Mensagem de erro:</strong> '.$msg_erro.'<br><br><a href="javascript:history.back(-1)">Clique aqui para voltar e corrigir!</a></div>';

	} elseif($erro == "mysql2") {

	$erro = '<style type="text/css">
	.texto_erro {
		background-color:#F2BBA5;
		padding-top:10px;
		padding-bottom:10px;
		width:100%;
		text-align:center;
		color: #923614;
		font-family:Arial;
		font-size:12px;
		font-weight:normal;
	}
	</style>

	<div class="texto_erro" align="center"><h3><strong>Erro ao selecionar o banco de dados.</strong></h3><strong>Mensagem de erro:</strong> '.$msg_erro.'<br><br><a href="javascript:history.back(-1)">Clique aqui para voltar e corrigir!</a></div>';

	} elseif($erro == "arquivo") {

	$erro = '<style type="text/css">
	.texto_erro {
		background-color:#F2BBA5;
		padding-top:10px;
		padding-bottom:10px;
		width:100%;
		text-align:center;
		color: #923614;
		font-family:Arial;
		font-size:12px;
		font-weight:normal;
	}
	</style>

	<div class="texto_erro" align="center"><h3><strong>Erro ao criar arquivo de configura��es.</strong></h3>Altere as permiss�es das seguintes pastas para 0777:<br><br>inc/<br>tickets_anexos/<br>backups/<br><br><a href="javascript:history.back(-1)">Clique aqui para voltar!</a></div>';

	}

	return $erro;

}

?>
