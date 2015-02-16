#DA EPIC WIDGET-SYSTEM
laravel widget-system


Example Widget (must implements Renderable)
```php
<?php namespace App\Widgets;

use Illuminate\Contracts\Support\Renderable;

class MyCustomWidget implements Renderable { 

	protected $something;

	public function __construct(SomeRepository $repository)
	{
		$this->something = $repository->getSomething();
	}
	
	
	public function render($someElse)
	{
		$data = [
			'something' => $this->something,
			'someElse'  => $someElse,
		];
		
		return view('view', $data);
	}
}

```


Registration:
```php
Widget::register('App\Widgets\MyCustomWidget', 'myWidget' );
//or
Widget::addInstance(new App\Widgets\MyCustomWidget($params), 'myWidget' );
```

In template:
```blade
{!! Widget::show('myWidget', 'someElse') !!}
//or
{!! Widget::myWidget('someElse') !!}
```

