<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace Armenio\Shipping\Correios;

use Armenio\Shipping\AbstractShipping;

use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\Json\Json;

use Armenio\Currency\Currency as ArmenioCurrency;

/**
* Correios
* 
* Retrieves shipping cost from Correios
*/
class AbstractCorreios extends AbstractShipping
{	
	protected $options = array(
		// 'login' => '',
		// 'senha' => '',
		'servico' => '',
		'origem' => '',
		'destino' => '',
		'peso' => '',
		'altura' => '',
		'largura' => '',
		'comprimento' => '',
	);

	protected $credentials = array(
		'login' => '',
		'senha' => '',
	);

	public function setOptions($options = array())
	{
		foreach ( $options as $optionKey => $optionValue ) {
			if( isset( $this->options[$optionKey] ) ){
				$this->options[$optionKey] = $optionValue;
			}
		}

		return $this;
	}

	public function getOptions($option = null)
	{
		if( $option !== null ){
			return $this->options[$option];
		}

		return $this->options;
	}

	public function setCredentials($jsonStringCredentials = '')
	{
		try{
			$options = Json::decode($jsonStringCredentials, 1);
			foreach ( $options as $optionKey => $optionValue ) {
				if( isset( $this->credentials[$optionKey] ) ){
					$this->credentials[$optionKey] = $optionValue;
				}
			}

			$isException = false;
		} catch (\Zend\Json\Exception\RuntimeException $e) {
			$isException = true;
		} catch (\Zend\Json\Exception\RecursionException $e2) {
			$isException = true;
		} catch (\Zend\Json\Exception\InvalidArgumentException $e3) {
			$isException = true;
		} catch (\Zend\Json\Exception\BadMethodCallException $e4) {
			$isException = true;
		}

		if( $isException === true ){
			//código em caso de problemas no decode
		}

		return $this;
	}

	public function getCredentials($credential = null)
	{
		if( $credential !== null ){
			return $this->credentials[$credential];
		}

		return $this->credentials;
	}
	
	/**
	* Returns shipping's cost
	* 
	* @return float
	*/
	public function getShippingDetails()
	{
		$result = array();
		
		try{
			$url = 'http://aircode.com.br/webservice/correios/frete';
			$client = new Client($url);
			$client->setAdapter(new Curl());
			$client->setMethod('POST');
			$client->setOptions(array(
				'curloptions' => array(
					CURLOPT_HEADER => false,
				)
			));
			$client->setParameterPost($this->credentials+$this->options);

			
			$response = $client->send();
			
			$body = $response->getBody();
			
			$result = Json::decode($body, 1);

			if( ! empty($result['shipping_price']) ){
				$result['shipping_price'] = ArmenioCurrency::normalize($result['shipping_price']);
			}

			$isException = false;
		} catch (\Zend\Http\Exception\RuntimeException $e){
			$isException = true;
		} catch (\Zend\Http\Client\Adapter\Exception\RuntimeException $e){
			$isException = true;
		} catch (\Zend\Json\Exception\RuntimeException $e) {
			$isException = true;
		} catch (\Zend\Json\Exception\RecursionException $e2) {
			$isException = true;
		} catch (\Zend\Json\Exception\InvalidArgumentException $e3) {
			$isException = true;
		} catch (\Zend\Json\Exception\BadMethodCallException $e4) {
			$isException = true;
		}

		if( $isException === true ){
			//código em caso de problemas no decode
		}

		return $result;
	}
}