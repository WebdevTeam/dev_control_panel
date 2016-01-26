<?php

namespace Skubase;

use Rocketeer\Abstracts\Strategies\AbstractPolyglotStrategy;
use Rocketeer\Interfaces\Strategies\DependenciesStrategyInterface;

/**
* DependenciesStrategy
*
* @uses     AbstractPolyglotStrategy
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class DependenciesStrategy extends AbstractPolyglotStrategy implements DependenciesStrategyInterface
{
	/**
	 * @type string
	 */
	protected $description = 'Run Composer and Bower Dependencies';

	/**
	 * The various strategies to call
	 *
	 * @type array
	 */
	protected $strategies = ['Composer', 'Bower'];

	/**
	 * Install the dependencies
	 *
	 * @return boolean[]
	 */
	public function install()
	{
		return $this->executeStrategiesMethod('install');
	}

	/**
	 * Update the dependencies
	 *
	 * @return boolean[]
	 */
	public function update()
	{
		return $this->executeStrategiesMethod('update');
	}
}
