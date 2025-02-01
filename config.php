<?php 

$nome_oficina = "Oficina";

$url = "https://$_SERVER[HTTP_HOST]/";
$ur = explode("//", $url);
if($ur[1] == 'codigoquatro.com.br/'){
	$url = "http://$_SERVER[HTTP_HOST]/oficina/";
}


$endereco_oficina = "Rua Alameda Campos, 157, Maranguape Ceara";
$telefone_oficina = "(99) 9999-9999";
$email_adm = 'codigoquatro2022@gmail.com';
$rodape_relatorios = "Desenvolvido por CodigoQuatro";

//VARIAVEIS DO BANCO DE DADOS LOCAL
//$servidor = 'localhost';
//$usuario = 'root';
//$senha = '';
//$banco = 'oficina';

//VARIAVEIS DO BANCO DE DADOS HOSPEDAGEM
$servidor = '108.167.151.55';
$usuario = 'codig267_oficina';
$senha = 'Alves1974#';
$banco = 'codig267_oficina';



//ALGUMAS VARIAVEIS GLOBAIS

//A PARTIR DE X PRODUTOS O NIVEL DO ESTOQUE ESTARÁ BAIXO
$nivel_estoque = 5;
$desconto_orc = 'Não';
$valor_desconto = 5; //VALOR EM PORCENTAGEM, POR EXEMPLO 5 VAI SER 5 % SOBRE O VALOR FINAL
$validade_orcamento_dias = 5; //5 DIAS PARA VALIDADE DO ORÇAMENTO
$excluir_orcamento_dias = 15; //APÓS 15 DIAS O ORÇAMENTO QUE NÃO FOR APROVADO PELO CLIENTE SERÁ EXCLUÍDO

$comissao_mecanico = 'Não';  // Se não for ter comissão no sisteema mude para não
$valor_comissao = 0.30; // COLOCAR O VALOR DA COMSISÃO COM A PORCENTAGEM MANTENDO O 0 NA FRENTEM, 0.30 COORESPONDE A 30% 

$dias_alerta_retorno = 180; //DIAS PARA AVISAR A RECEPÇÃO QUE O VEÍCULO NÃO RETORNOU AO SERVIÇO A PARTIR DE 180 DIAS

$mensagem_retorno = "Vimos que já faz um tempo que não fazemos nenhum serviço em seu veículo, estamos com uma promoção para serviços de Balanceamento, troca de óleo e vários outros, aproveite nossa promoção... "; //TEXTO DA MENSAGEM NO EMAIL PARA O CLIENTE QUANDO COMPLETAR XX DIAS QUE ELE NÃO FAZ NENHUM SERVIÇO
 ?>