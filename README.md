# Pistis - pseudo-random numbers/identifiers for reproducable builds

Code generation often uses random identifiers / numbers to generate identifiers that are free of collisions. These 
random identifiers make reproducable builds impossible since there is no way to make the randomness deterministic.

To avoid that, **pistis** provides a simple interface to pseudo-random numbers and hexadecimal strings that can be used
as part of identifiers enabling reproducable builds. Pistis allows passing a previous seed as an environment variable
`PISTIS_SEED` for a second run.

```php
<?php
include 'vendor/autoload.php';

use Pistis\PseudoRandom;

var_dump(PseudoRandom::integer());
var_dump(PseudoRandom::getSeed());
```

Running this with PHP will output something like this:

```
int(1625705860186051574)
int(1353038704721151717)
```

Re-running the same script with `PISTIS_SEED=1353038704721151717 php example.php` will output the exact same numbers
again:

```
int(1625705860186051574)
int(1353038704721151717)
```
