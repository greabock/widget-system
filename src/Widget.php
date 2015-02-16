<?php namespace Greabock\Widget;

use App;

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
    public function register($class, $name)
    {
        $this->classes[$name] = $class;
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

    /**
     * @param $widget
     * @param string     $name
     * @return void
     */
    public function addInstance($widget, $name)
    {
        $this->instances[$name] = $widget;
    }

}