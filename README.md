# Usage

Create an instance of chart factory
---
``` 
$chartFactory = new \Slonyaka\Market\ChartFactory(); 
```

Chart can receive an instance of Slonyaka\Market\Collection with MarketData to print chart. 
---

With these data we can build chart. Line shaped chart:
---
```
$rates = Slonyaka\Market\CurrencyRateFactory::make('alpha_vantage_api_key');
$ratesCollection = $rates->getRates('usd', 'eur', '5min');
$chart = $chartFactory->createLine($ratesCollection);

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