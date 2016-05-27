<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace Armenio\Shipping\Correios;
use Armenio\Shipping\Correios\AbstractCorreios;
/**
* Pac
* 
* Retrieves delivery cost from Correios
*/
class Pac extends AbstractCorreios
{
	protected $serviceCode = '41106';
	
	/**
	* setOptions
	* 
	* @param array $options
	*/
	public function setOptions($options = array())
	{
		$options['servico'] = $this->serviceCode;

		return parent::setOptions($options);
	}   
}