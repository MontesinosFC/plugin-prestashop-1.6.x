<?php
/*
* Copyright 2015 Compropago. 
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
*     http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/
/**
 * ComproPago Prestashop WebHook
 * @author Rolando Lucio <rolando@compropago.com>
 * @since 2.0.0
 */
//El request es valido?
$request = @file_get_contents('php://input');
if(!$jsonObj = json_decode($request)){
	die('Tipo de Request no Valido');
}
//Include prestashop files
$prestaFiles= array(
		dirname(__FILE__).'/../../config/config.inc.php',
		dirname(__FILE__).'/../../init.php',
		dirname(__FILE__).'/../../classes/PrestaShopLogger.php'
);
foreach($prestaFiles as $prestaFile){
	if(file_exists($prestaFile)){
		include_once	$prestaFile;
	}else{
		echo "ComproPago Warning: No se encontro el archivo de Prestashop:".$prestaFile."<br>";
	}
}
//prestashop Rdy?
if (!defined('_PS_VERSION_')){
	die("No se pudo inicializar Prestashop");
}
//include ComproPago SDK & dependecies
$compropagoComposer= dirname(__FILE__).'/vendor/autoload.php';
if ( file_exists( $compropagoComposer ) ){
	require $compropagoComposer;
}else{
	die('No se encontro el autoload para Compropago y sus dependencias:'.$compropagoComposer);
}
//Compropago Plugin Installed?
if (!Module::isInstalled('compropago')){
	die('El módulo de ComproPago no se encuentra instalado');
}
//ComproPago Prestashop Config
$config = Configuration::getMultiple(array('COMPROPAGO_PUBLICKEY', 'COMPROPAGO_PRIVATEKEY','COMPROPAGO_MODE'));
//keys set?
if (!isset($config['COMPROPAGO_PUBLICKEY']) || !isset($config['COMPROPAGO_PRIVATEKEY'])
	|| empty($config['COMPROPAGO_PUBLICKEY']) || empty($config['COMPROPAGO_PRIVATEKEY'])){
	die("Se requieren las llaves de compropago");
}
//mode defined
if(!isset($config['COMPROPAGO_MODE']) || empty($config['COMPROPAGO_MODE'])){
	die("Se requiere definir un modo de ejecución");
}
//Compropago SDK config
$moduleLive=($config['COMPROPAGO_MODE']=='yes')? true:false;
$compropagoConfig= array(
		'publickey'=>$config['COMPROPAGO_PUBLICKEY'],
		'privatekey'=>$config['COMPROPAGO_PRIVATEKEY'],
		'live'=>$moduleLive,
		'contained'=>'plugin; cpps 2.0.0;prestashop '._PS_VERSION_.'; webhook;'		
);
// consume sdk methods
try{
	$compropagoClient = new Compropago\Client($compropagoConfig);
	$compropagoService = new Compropago\Service($compropagoClient);
	// Valid Keys?
	if(!$compropagoResponse = $compropagoService->evalAuth()){
		die("ComproPago Error: Llaves no validas");
	}
	// Store Mode Vs ComproPago Mode, Keys vs Mode & combinations
	if(! Compropago\Controllers\Store::validateGateway($compropagoClient)){
		die("ComproPago Error: La tienda no se encuentra en un modo de ejecución valido");
	}
}catch (Exception $e) {
	//something went wrong at sdk lvl
	die($e->getMessage());
}
echo '<pre>';
print_r($compropagoResponse);
echo '</pre><br>';