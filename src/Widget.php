<?php namespace Greabock\Widget;

use Illuminate\Foundation\Application as App;

class Widget {

	/**
	 * classes registered widgets
	 *
	 * @var array
	 */
	protected $classes = [];

	/**
	 * Prepared instances of widgets
	 *
	 * @var array
	 */
	protected $instances = [];

	/**
	 * Positions for widgets
	 *
	 * @var array
	 */
	protected $positions = [];


	public function __construct(App $app)
	{
		$this->app = $app;
	}

	/**
	 * @param string $method
	 * @param array  $arguments
	 * @return mixed
	 */
	public function __call($method, array $arguments)
	{
		array_unshift($arguments, $method);

		return call_user_func_array([$this, 'show'], $arguments);
	}

	/**
	 * @param  string $class
	 * @param  string $name
	 * @return void
	 */
	public function register($class, $name, $position = null, $order = null)
	{
		$this->classes[$name] = $class;

		if ($position)
		{
			$this->positions[$position][] = compact('name', 'order');
		}

	}

	/**
	 * @param  string $name
	 * @return mixed|null
	 */
	public function show($name)
	{

		if ( ! array_key_exists($name, $this->classes)) return null;

		if ( ! array_key_exists($name, $this->instances))
		{
			$widget = $this->app->make($this->classes[$name]);

			$this->addInstance($widget, $name);
		}

		return call_user_func_array([$this->instances[$name], 'render'], array_slice(func_get_args(), 1));
	}


	public function position($position)
	{
		if ( ! array_key_exists($position, $this->positions)) return null;

		usort($this->positions[$position], function ($a, $b)
		{
			return ($a['order'] == $b['order']) ? 0 : (($a['order'] > $b['order']) ? -1 : 1);

		});

		$carpet = '';

		$arguments = array_slice(func_get_args(), 1);


		foreach ($this->positions[$position] as $widget)
		{

			$parameters = $arguments;

			array_unshift($parameters, $widget['name']);

			$carpet .= call_user_func_array([$this, 'show'], $parameters);
		}

		return $carpet;
	}


	public function exists($name)
	{
		if (array_key_exists($name, $this->classes)) return true;

		return false;
	}


	public function isEmptyPosition($position)
	{
		if ( ! array_key_exists($position, $this->positions)) return true;

		if ( ! count($this->positions[$position])) return true;

		return false;
	}

	/**
	 * @param        $widget
	 * @param string $name
	 * @return void
	 */
	protected function addInstance($widget, $name)
	{
		$this->instances[$name] = $widget;
	}

}

