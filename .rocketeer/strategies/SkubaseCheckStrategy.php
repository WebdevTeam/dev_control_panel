<?php
namespace SkubaseStrategy;

use Illuminate\Container\Container;
use WorldStores\AbstractSkubaseCheckStrategy;
use Rocketeer\Interfaces\Strategies\CheckStrategyInterface;

/**
* SkubaseCheckStrategy
*
* @uses     AbstractSkubaseCheckStrategy
*
* @todo     Finish Composer and Bower server checks - at the moment we only check that the manifest files for these exist locally or remotely.
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
class StandardChecks extends AbstractSkubaseCheckStrategy implements CheckStrategyInterface
{
	/**
	 * @type string
	 */
	protected $description = 'Checks if the server is ready to receive a WorldStores Skubase PHP application';

	/**
	 * The language of the strategy
	 *
	 * @type string
	 */
	protected $language = 'PHP';

	/**
	 * The PHP extensions loaded on server
	 *
	 * @var array
	 */
	protected $extensions = array();

	/**
	 * @param Container $app
	 */
	public function __construct(Container $app)
	{
		$this->app     = $app;
		$this->setManager($this->binary('composer'));
	}

	//////////////////////////////////////////////////////////////////////
	/////////////////////////////// CHECKS ///////////////////////////////
	//////////////////////////////////////////////////////////////////////

	/**
	 * Get the version constraint which should be checked against
	 *
	 * @param string $manifest The contents of composer.json file from
	 *
	 * @return string PHP version from composer file e.g. string(5) "5.4.*"
	 */
	protected function getLanguageConstraint($manifest)
	{
		$language_contraint = $this->getLanguageConstraintFromJson($manifest, 'require.php');

		if ($language_contraint === NULL)
		{
			$this->explainer->line('No PHP version constraint set in composer.json');
		}

		return $language_contraint;
	}

	/**
	 * Get the current version in use
	 *
	 * @example /usr/bin/php -r="print defined('HHVM_VERSION') ? HHVM_VERSION : PHP_VERSION;"
	 *
	 *
	 * @return string PHP version on server e.g. string(6) "5.4.36"
	 */
	protected function getCurrentVersion()
	{
		return $this->php()->runLast('version');
	}

	/**
	 * Check for the required extensions
	 *
	 * @return array
	 */
	public function extensions()
	{
		$extensions = array(
			'curl'     => ['checkPhpExtension', 'curl'],
			'gd'       => ['checkPhpExtension', 'gd'],
			'mcrypt'   => ['checkPhpExtension', 'mcrypt'],
			'database' => ['checkDatabaseDriver', 'mysql'],
		);

		// // Check PHP extensions
		$errors = [];
		foreach ($extensions as $check) {
			list($method, $extension) = $check;

			if (!$this->$method($extension)) {
				$errors[] = $extension;
			}
		}

		if (empty($errors))
		{
			$this->explainer->line("PASSED ==> All extensions are correctly installed on this server.");
		}

		return $errors;
	}

	/**
	 * Check for the required drivers
	 *
	 * @return array
	 */
	public function drivers()
	{
		$drivers = array(
			'cache'    => ['checkCacheDriver', 'redis'],
			// 'bower'    => ['checkCacheDriver', 'redis'],
			// 'composer'    => ['checkCacheDriver', 'redis'],
		// 	'session'  => ['checkCacheDriver', $this->app['config']->get('session.driver')],
		);

		// // Check PHP drivers
		$errors = [];
		foreach ($drivers as $check) {
			list($method, $driver) = $check;

			if (!$this->$method($driver)) {
				$errors[] = $driver;
			}
		}

		if (empty($errors))
		{
			$this->explainer->line("PASSED ==> All drivers are correctly installed on this server.");
		}

		return $errors;
	}

	//////////////////////////////////////////////////////////////////////
	////////////////////////////// HELPERS ///////////////////////////////
	//////////////////////////////////////////////////////////////////////

	/**
	 * Check the presence of the correct database PHP extension
	 *
	 * @param string $database
	 *
	 * @return boolean
	 */
	public function checkDatabaseDriver($database)
	{
		switch ($database) {
			case 'mysql':
				return $this->checkPhpExtension('mysql') && $this->checkPhpExtension('pdo_mysql');

			default:
				return true;
		}
	}

	/**
	 * Check the presence of the correct cache PHP extension
	 *
	 * @param string $cache
	 *
	 * @return boolean|string
	 */
	public function checkCacheDriver($cache)
	{
		switch ($cache) {
			case 'redis':
				return $this->which('redis-cli');

			default:
				return true;
		}
	}

	/**
	 * Check the presence of a PHP extension
	 *
	 * @param string $extension The extension
	 *
	 * @return boolean
	 */
	public function checkPhpExtension($extension)
	{
		// Get the PHP extensions available
		$this->extensions = (array) $this->bash->run($this->php()->extensions(), true, true);

		return in_array($extension, $this->extensions);
	}
}
