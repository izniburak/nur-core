<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Kernel;

use Nur\Container\Container;

abstract class Facade
{
	/**
	 * Application List in Service Provider
	 * 
	 * @var array
	 */
	protected static $applications;

	/**
	 * Resolved instances of objects in Facade
	 * 
	 * @var array
	 */
	protected static $resolvedInstance;

	/**
	 * Created instances of objects in Facade
	 * 
	 * @var array
	 */
	protected static $createdInstances = [];

	/**
	 * Resolved Instance
	 *
	 * @param string $facadeName
	 * @return string
	 */
	protected static function resolveInstance($facadeName)
	{
		if (is_object($facadeName)) {
			return $facadeName;
		}

		if (isset(static::$resolvedInstance[$facadeName])) {
			return static::$resolvedInstance[$facadeName];
		}

		return static::$resolvedInstance[$facadeName] = static::$applications->get($facadeName);
	}

	/**
	 * Set Facade Application (Container)
	 *
	 * @param string $app
	 * @return void
	 */
	public static function setApplication($app)
	{
		static::$applications = $app;
	}

	/**
	 * Clear Resolved Instance
	 *
	 * @param string $facadeName
	 * @return void
	 */
	public static function clearResolvedInstance($facadeName)
	{
		unset(static::$resolvedInstance[$facadeName]);
	}

	/**
	 * Clear All Resolved Instances
	 *
	 * @return void
	 */
	public static function clearResolvedInstances()
	{
		static::$resolvedInstance = [];
	}

	/**
	 * Call Methods in Application Object
	 *
	 * @param string $method
	 * @param array $parameters
	 * @return mixed
	 */
	public static function __callStatic($method, $parameters)
	{
		$accessor 	= strtolower(static::getFacadeAccessor());
		$provider 	= static::resolveInstance(strtolower($accessor));

		if (!array_key_exists($accessor, static::$createdInstances)) {
			static::$createdInstances[$accessor] = $provider;
		}
        
		return call_user_func_array([static::$createdInstances[$accessor], $method], $parameters);
	}	
}
