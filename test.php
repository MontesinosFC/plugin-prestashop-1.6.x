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
 */

//include ComproPago SDK & dependecies
$compropagoComposer= dirname(__FILE__).'/vendor/autoload.php';
if ( file_exists( $compropagoComposer ) ){
	require $compropagoComposer;
}else{
	die('No se encontro el autoload para Compropago y sus dependencias:'.$compropagoComposer);
}
/* 1.0
'publickey'=>'pk_live_8c9301f6-2af12d',
'privatekey'=>'sk_live_8c3308e0915e61',
*/
/* 1.1
'publickey'=>'pk_live_570a6884d69e263',
'privatekey'=>'sk_live_7ff93be105c732dc',
*/
$compropagoConfig= array(
'publickey'=>'pk_live_8c9301f6-2af12d',
'privatekey'=>'sk_live_8c3308e0915e61',
		'live'=>true
);


try {
$compropagoClient= new Compropago\Client($compropagoConfig);

$compropagoService= new Compropago\Service($compropagoClient);

//Val keys vs mode here


//$response=$compropagoService->evalAuth();
//$response=$compropagoService->getProviders();


//$data=array('order_total'=>20000);
//$response=Compropago\Http\Rest::doExecute($compropagoClient,'providers',$data);

//$response = (Compropago\Controllers\Store::validateGateway($compropagoClient))? 'vdd':'neg';
//$response= Compropago\Controllers\Store::test('otro param');

//Campos Obligatorios para poder realizar una nueva orden
$data = array(
		'order_id'           => 'testorderid',             // string para identificar la orden
		'order_price'        => '58.30,10',                  // float con el monto de la operación
		'order_name'         => 'Test Order Name',         // nombre para la orden
		'customer_name'      => 'Compropago Test',         // nombre del cliente
		'customer_email'     => 'test@compropago.com',     // email del cliente
		'payment_type'       => 'OXXO'                     // identificador de la tienda donde realizar el pago
);
//Obtenemos el JSON de la respuesta
//$response = $compropagoService->placeOrder($data);
$response=Compropago\Http\Rest::doExecute($compropagoClient,'charges',$data,'POST');

//$response = $compropagoService->verifyOrder('ch_f5ee0b70-666e-4672-9f21-bca75ffc2409');

}catch (Exception $e){
	die($e->getMessage());
}
	
//if($response['responseCode']=='401'){
	//die('aca proceso msj');
//}
//if($response['responseCode']=='200'){
//	$response=$response['responseBody'];
//}


echo '<pre>';
print_r($response);
echo '</pre>';
