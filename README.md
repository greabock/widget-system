#DA EPIC WIDGET-SYSTEM
laravel widget-system

composer

```
"greabock/widget-system": "0.01.*@dev"
```


facade
```
'Widget'=> 'Greabock\Widget\Facade'
```


Example Widget
```php
<?php namespace App\Widgets;

class MyCustomWidget { 

	protected $something;

	public function __construct(SomeRepository $repository)
	{
		$this->something = $repository->getSomething();
	}
	
	
	public function render($param)
	{
		$data = [
			'something' => $this->something,
			'someElse'  => $param,
		];
		
		return view('view', $data);
	}
}

```


Registration:
```php
Widget::register('App\Widgets\MyCustomWidget', 'myWidget' );
```

Template:
```tpl
{!! Widget::show('myWidget', 'param') !!}
{-- or --}
{!! Widget::myWidget('param') !!}
```

Positions
```php
Widget::register('App\Widgets\MyCustomWidget', 'myWidget', 'menu_position', 1 );
Widget::register('App\Widgets\OtherCustomWidget', 'OtherWidget', 'menu_position', 2 );
```

Template:

```php
{!! Widget::position('menu_position') !!}
// same as
{!! Widget::show('myWidget') !!}
{!! Widget::show('OtherWidget') !!}
```

