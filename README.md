# Pistis - Controlled pseudo-randomness and time for PHP [![Build Status](https://travis-ci.org/lstrojny/pistis.svg?branch=master)](https://travis-ci.org/lstrojny/pistis)

## Pseudo-randomness

Code generation often uses random identifiers / numbers to generate identifiers that are free of collisions. These 
random identifiers make reproducable builds impossible since there is no way to make the randomness deterministic.

To avoid that, **pistis** provides a simple interface to pseudo-random numbers and hexadecimal strings that can be used
as part of identifiers enabling reproducable builds. Pistis allows passing a previous seed as an environment variable
`PISTIS_SEED` for a second run.

## Time
A second source of quasi randomness is time. Time info can be received by calling functions like `time()`. By replacing
those functions with `Pistis\Clock::unixTimestamp()` one can fixate time for reproducible builds.

```php
<?php
include 'vendor/autoload.php';

use Pistis\PseudoRandom;
use Pistis\Clock;

var_dump(PseudoRandom::integer());
var_dump(PseudoRandom::integer());
var_dump(PseudoRandom::hex());
var_dump(PseudoRandom::hex());
var_dump(Clock::unixTimestamp());

echo 'PRNG seed: ' . PseudoRandom::getSeed() . "\n";
echo 'Time seed: ' . Clock::getSeed() . "\n";
```

Running this with PHP will output something like this:

```
int(1625705860186051574)
int(8240691892729656673)
string(8) "195251fc"
string(8) "1cc6a0e8"
int(1510246905)
PRNG seed: 1353038704721151717
Time seed: 1510246905
```

Re-running the same script with `PISTIS_SEED=1353038704721151717 PISTIS_TIME=1510246905 php example.php` will output
the exact same numbers again:

```
int(1625705860186051574)
int(8240691892729656673)
string(8) "195251fc"
string(8) "1cc6a0e8"
int(1510246905)
PRNG seed: 1353038704721151717
Time seed: 1510246905
```
