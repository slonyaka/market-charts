##Usage

Create instance of chart factory
---
``` 
$chartFactory = new \Slonyaka\Market\ChartFactory(); 
```

Chart can receive an array to print chart. Array item must contain time and set of prices.
---
```
$data = [
   
   	[
   		'time' => '2020-08-18',
   		'closePrice' => 1.11,
   		'highPrice' => 1.14,
   		'lowPrice' => 1.02,
   		'openPrice' => 1.08
   	],
];
   	
```

With these data we can get concrete chart type. Line shaped chart:
---
```

$chart = $chartFactory->createLine($data);

```

As a builder chart instance can receive some settings using fluid interface
---

```

$chart->setPeriod(5)->setHeight(300);

```

And build chart returning valid SVG object
---

```

echo $chart->build();

```