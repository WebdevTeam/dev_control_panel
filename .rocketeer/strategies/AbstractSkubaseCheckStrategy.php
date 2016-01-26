<?php
namespace Worldstores;

use Illuminate\Support\Arr;
use Rocketeer\Abstracts\Strategies\AbstractStrategy;

/**
* AbstractSkubaseCheckStrategy
*
* @uses     AbstractStrategy
*
* @author   Dave Ward <dave.ward@worldstores.co.uk>
*/
abstract class AbstractSkubaseCheckStrategy extends AbstractStrategy
{
	/**
	 * @type \Rocketeer\Abstracts\AbstractPackageManager
	 */
	protected $manager;

	/**
	 * The language of the strategy
	 *
	 * @type string
	 */
	protected $language;

	/**
	 * @return \Rocketeer\Abstracts\AbstractPackageManager
	 */
	public function getManager()
	{
		return $this->manager;
	}

	/**
	 * @param \Rocketeer\Abstracts\AbstractPackageManager $manager
	 */
	public function setManager($manager)
	{
		$this->manager = $manager;
	}

	/**
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	//////////////////////////////////////////////////////////////////////
	/////////////////////////////// CHECKS ///////////////////////////////
	//////////////////////////////////////////////////////////////////////


	public function checkManagers(array $managers)
	{
		$check = TRUE;

		foreach ($managers as $manager)
		{
			$manager = $this->binary($manager);
			$manager_name = class_basename($manager);
			$manager_name = str_replace('Strategy', null, $manager_name);

			if ($manager != $this->manager)
			{
				$this->explainer->line('Checking presence of '.$manager_name);
			}

			$manager_check = $manager && $manager->isExecutable();
			if ($manager_check === TRUE)
			{
				$this->explainer->line("PASSED ==> Manifests for {$manager_name} package manager are found (locally or remotely).");
			}
			else
			{
				$this->explainer->line("FAILED ==> Manifests for {$manager_name} package manager could not be found (locally or remotely).");
				$check = FALSE;
			}
		}

		return $check;
	}

	/**
	 * Check that the PM that'll install
	 * the app's dependencies is present
	 *
	 * @return boolean
	 */
	public function manager()
	{
		return $this->checkManagers(array('composer','bower'));
	}

	/**
	 * Check that the language used by the
	 * application is at the required version
	 *
	 * @return boolean
	 */
	public function language()
	{
		$required = null;

		// Get the minimum version of the application
		if ($this->manager && $manifest = $this->manager->getManifestContents()) {
			$required = $this->getLanguageConstraint($manifest);
		}

		// Cancel if no version constraint
		if (!$required) {
			return true;
		}

		$version = $this->getCurrentVersion();

		$language_check = version_compare($version, $required, '>=');

		if ($language_check === TRUE)
		{
			$this->explainer->line("PASSED ==> {$this->language} version installed on server [{$version}] satisfies minimium requirements [{$required}].");
		}

		return $language_check;
	}

	//////////////////////////////////////////////////////////////////////
	////////////////////////////// LANGUAGE //////////////////////////////
	//////////////////////////////////////////////////////////////////////

	/**
	 * Get the version constraint which should be checked against
	 *
	 * @param string $manifest
	 *
	 * @return string
	 */
	abstract protected function getLanguageConstraint($manifest);

	/**
	 * Get the current version in use
	 *
	 * @return string
	 */
	abstract protected function getCurrentVersion();

	//////////////////////////////////////////////////////////////////////
	////////////////////////////// HELPERS ///////////////////////////////
	//////////////////////////////////////////////////////////////////////

	/**
	 * @param string $manifest
	 * @param string $handle
	 *
	 * @return string
	 */
	protected function getLanguageConstraintFromJson($manifest, $handle)
	{
		$manifest   = json_decode($manifest, true);
		$constraint = (string) Arr::get($manifest, $handle);
		$constraint = preg_replace('/[~>= ]+ ?(.+)/', '$1', $constraint);

		return $constraint;
	}
}
