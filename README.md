# Usage

Create an instance of chart factory
---
``` 
$chartFactory = new \Slonyaka\Market\ChartFactory(); 
```

Chart can receive an instance of Slonyaka\Market\Collection with MarketData to print chart. 
---

With these data we can get concrete chart type. Line shaped chart:
---
```
$rates = new Slonyaka\Market\CurrencyRate();
$rates->setApiKey('api_key');
$data = $rates->getRates('usd', 'eur', '5min');
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