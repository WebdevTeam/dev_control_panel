<?php

namespace SkubaseStrategy;

use Rocketeer\Abstracts\Strategies\AbstractStrategy;
use Rocketeer\Interfaces\Strategies\MigrateStrategyInterface;

/**
* PhinxStrategy
*
* @uses     AbstractStrategy
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class PhinxStrategy extends AbstractStrategy implements MigrateStrategyInterface
{
	/**
	 * @type string
	 */
	protected $description = 'Migrates your database with Phinx Migrations';

	protected $phinx;
	/**
	 * Whether this particular strategy is runnable or not
	 *
	 * @return boolean
	 */
	public function isExecutable()
	{
		$this->phinx = $this->binary('SkubaseStrategy\PhinxBinary');
		return (bool) $this->phinx;
	}

	/**
	 * Run outstanding migrations
	 *
	 * @return boolean|null
	 */
	public function migrate()
	{
		//Make sure the application config file has been symlinked before running the migration.
		$file = '{path.public}config/application.config.php';
		$this->share($file);
		return $this->phinx->runForCurrentRelease('migrate');
	}

	public function seed()
	{

	}
}
