# Flowdception

**Flowdception** is a Codeception Extension for integrating the [Flowder](https://github.com/imjoehaines/flowder) fixture loader into Codeception test suites.

## Usage

1. Install Flowdception as a development dependency through [Composer](https://getcomposer.org/)

   ```sh
   $ composer install imjoehaines/flowdception --dev
   ```

2. Enable Flowdception as an extension in your main `codeception.yml`, or a specific suite's YAML configuration file (e.g. `integration.suite.yml`)

   ```yaml
   extensions:
     enabled:
       - \Imjoehaines\Flowder\Codeception\Flowdception
   ```

3. Bootstrap Flowdception by calling `Flowdception::bootsrap` in one of your `_bootstrap.php` Codeception files, passing in an instance of `\Imjoehaines\Flowder\Flowder` (see the [Flowder documentation](https://github.com/imjoehaines/flowder) for more information).

   A simple SQLite example might look like this:

   ```php
   <?php

   require __DIR__ . '/../vendor/autoload.php';

   use Imjoehaines\Flowder\Codeception\Flowdception;

   use Imjoehaines\Flowder\Loader\PhpFileLoader;
   use Imjoehaines\Flowder\Truncator\SqliteTruncator;
   use Imjoehaines\Flowder\Persister\SqlitePersister;

   $db = new PDO(...);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   Flowdception::bootstrap(
       __DIR__ . '/_data/example.php',
       new PhpFileLoader(),
       new SqliteTruncator($db),
       new SqlitePersister($db)
   );
   ```

4. That's it! Before any Codeception test file runs, Flowder will load your fixture data for you