<?php

namespace SkubaseStrategy;

use Illuminate\Container\Container;
use Rocketeer\Abstracts\AbstractBinary;
use Rocketeer\Binaries\Php;

/**
* PhinxBinary
* The wrapper class for Phinx commands
*
* @uses     AbstractBinary
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class PhinxBinary extends AbstractBinary
{

	protected $target_string;

	/**
	 * @param Container $app
	 */
	public function __construct(Container $app)
	{
		parent::__construct($app);

		// Set PHP as parent - not required for phinx
		// $php = new Php($this->app);
		// $this->setParent($php);
	}

	/**
	 * Get an array of default paths to look for
	 *
	 * @return string[]
	 */
	protected function getKnownPaths()
	{
		return array(
			'phinx',
			$this->releasesManager->getCurrentReleasePath().'/vendor/bin/phinx',
		);
	}

    /**
     * setTarget
     *
     * @param string $target yyyymmddhhiiss
     * @example 20110103081132
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function setTarget($target)
	{
		$this->target_string = "-t {$target}";
	}

	/**
	 * Run outstanding migrations
	 *
	 * @return string
	 */
	public function migrate()
	{
		return $this->getCommand('migrate', ['-e application-config', $this->target_string]);
	}

    /**
     * rollback
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function rollback()
	{
		return $this->getCommand('rollback', ['-e application-config', $this->target_string]);
	}

    /**
     * rollbackAll
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function rollbackAll()
	{
		return $this->getCommand('rollback', ['-e application-config', '-t 0']);
	}
}
