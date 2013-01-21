BelsymGuzzleBundle
===================

BelsymGuzzleBundle is a fork of the work undertaken on [DdeboerGuzzleBundle](https://github.com/ddeboer/GuzzleBundle). It is a Symfony2 bundle for integrating the [Guzzle PHP library](http://github.com/guzzle/guzzle) in your project.

I made the decision to not submit pull requests and re-release it as BelsymGuzzleBundle as no development appeared to have been done on Ddeboer's version in a few months at the time of forking and it wasn't in a working state 'out of the box'. I must give full credit and thanks to David De Boer for his work on his bundle though as I would not have figured it all out without his insights!

I have made some fundamental changes to the bundle code to get it working with Guzzle 3.*. The upgrade of Guzzle to version 3 introduced a number of changes that broke BC with earlier versions completely.

The changes within this bundle from the original are two-fold:

1. Update the project to work with the latest version of Guzzle (3.0)
2. Introduce a method of defining the Guzzle service descriptions, clients, commands etc using the Symfony2 Dependency Injection configuration system.

Next Steps
----------

- add command configuration
- add operation and response object structure

Installation
------------

Installation is pretty easy, and far easier if you take the composer route (hint!)

1. Download this Bundle
2. Configure the Autoloader (only necessary if you **didn't** install using composer)
3. Enable the bundle (only necessary if you **didn't** install using composer)

### Step 1: Download this Bundle

Ultimately, the GuzzleBundle files should be downloaded to the `vendor/belsym/bundles/Belsym/GuzzleBundle` directory. This can be done in a number of ways.

#### Using Composer

The first (and recommended) method is to use Composer to grab the bundle. This ensures that you are not only using the specified version of this bundle, but also the compatible guzzle library (or libraries)

Add the following to your composer.json file's require section:

``` json
{

        "belsym/GuzzleBundle": "dev-master"


}
```

From the command line (in your project root of course), type `php composer.phar install` and wait for the dependencies to be resolved. Once done, and the cache has been cleared, you can use the bundle and move onto configuration

#### Using The Vendors Script

The second method is the old Symfony2 method of using the vendor's script. This is general practice for symfony 2.0 I believe - although this bundle is only tested with Symfony >2.1.7

Add the following lines in your `deps` file:

``` ini
[guzzle]
    git=git://github.com/guzzle/guzzle.git
    target=guzzle

[BelsymGuzzleBundle]
    git=git://github.com/belsym/GuzzleBundle.git
    target=belsy/bundles/Belsym/GuzzleBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

#### Using A Git SubModule

If you prefer instead to use git submodules, then run the following:

``` bash
$ git submodule add git://github.com/guzzle/guzzle.git vendor/guzzle
$ git submodule add git://github.com/belsym/GuzzleBundle vendor/belsym/bundles/Belsym/GuzzleBundle
$ git submodule update --init
```

### Step 2: Configure the Autoloader (if you added the bundle using composer you can skip this step)

Add the `Guzzle` and `Belsym` namespace to your autoloader:

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'Guzzle'           => __DIR__.'/../vendor/guzzle/src',
    'Belsym'          => __DIR__.'/../vendor/belsym/bundles',
));
```

### Step 3: Enable the bundle (if you added the bundle using composer you can skip this step as well)

Finally, enable the bundle in the kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Belsym\GuzzleBundle\BelsymGuzzleBundle(),
    );
}
```

Configuration
-------------

Configuration can be added in a number of ways. You are able to use a Guzzle service configuration file to make your configurations more portable or you can define your services using YAML markup using the container configuration system built into Symfony.

#### Using a configuration file

Add a configuration containing the path to a valid guzzle service builder configuration file following the example below.

``` yaml
# app/config/config.yml
belsym_guzzle:
    configuration: "%kernel.root_dir%/config/webservices.json"
```

The file at the location entered must be either a `.json`, `.js` or `.php` file. See the [Guzzle documentation](http://guzzlephp.org/tour/using_services.html#instantiating-web-service-clients-using-a-servicebuilder).

``` json
{
    "includes": [],
    "services": {
        "abstract.default_client": {
            "class": "Guzzle\\Service\\Client"
        },
        "unfuddle": {
            "extends": "abstract.default_client",
            "params": {
                "apiVersion": "1.0",
                "baseUrl": "https://unfuddle.com/",
                "description": "Bug Tracking and source code integration for the masses",
            }
        },
        "httpbin": {
            "extends": "abstract.default_client",
            "params": {
                "first": "one thing",
                "second": "or another"
            }
        }
    }
}
```

#### Using the configuration system

In order to define your services through the Symfony Config component, instead of adding a filepath as the value of `configuration`, you should create `configuration` as an array containing a `services` array

The main benefit of this is to add easily overridden configurations for different environments. For instance, you might want to use an alternative set of parameters when connecting to a service from your dev or test environments rather than production. Using the configuration files offers a method of doing that.

**Note:** It's currently not possible to have a mixture of file and configured services. You must choose one strategy or the other

```yaml
belsym_guzzle:
    configuration:
        services:
            abstract.default_client:
                class: "Guzzle\\Service\\Client"
            unfuddle:
                extends: "abstract.default_client"
                params:
                    apiVersion: "1.0"
                    baseUrl: "https://unfuddle.com/"
                    description: "Bug Tracking and source code integration for the masses"
            httpbin:
                extends: "abstract.default_client"
                params:
                    first: "one thing"
                    second: "or another"
```

**Note:** The examples shown here are just examples. Don't expect any actual results using the configurations in this documentation!


## License

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
