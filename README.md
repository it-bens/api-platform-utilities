# The API Platform Utilities Bundle

![Maintenance Status](https://img.shields.io/badge/Maintained%3F-yes-green.svg)
[![Build Status](https://app.travis-ci.com/it-bens/api-platform-utilities-bundle.svg?branch=master)](https://app.travis-ci.com/it-bens/api-platform-utilities-bundle)
[![Coverage Status](https://coveralls.io/repos/github/it-bens/api-platform-utilities-bundle/badge.svg?branch=master)](https://coveralls.io/github/it-bens/api-platform-utilities-bundle?branch=master)

This bundle provides a set of useful abstract classes that encapsulate some logic for the usage of [API platform](https://api-platform.com).
Furthermore, it implements an easily configurable input- and output transformer system that can be used without further classes.

## How to install the bundle?
The bundle can be installed via Composer:
```bash
composer require it-bens/api-platform-utilites-bundle
```

If you're using Symfony Flex, the bundle will be automatically enabled. For older apps, enable it in your Kernel class.
> ⚠ The ITB\ObjectTransformerBundle should be enabled, too.

## What does this bundle provide and how to use it?
Let's start with the non-configurable stuff.

### DataProvider

### DataPersister

### DataTransformer
Now we'll take a look at the `DataTransformer` system, that this bundle provides (or extends).

The typical use-case for the API Platform data transformer system is to transform DTOs to Entities/Objects and vice versa.
This bundle uses the `TransformationMediator` from the [Object Transformer Bundle](https://github.com/it-bens/object-transformer-bundle).

Normally, you would probably write an implementation of the API Platform `DataTransformerInterface`
for every DTO transformation that should be performed during the ordinary data flow, described [here](https://api-platform.com/docs/core/dto).
That's quite a mess if there are a lot of resources, requests DTOs and response DTOs.
The `ApiInputTransformer` and `ApiOutputTransformer` classes handle that complete process with a tiny bit of configuration.

After the bundle is enabled, a `itb_api_platform_utilities.yaml` file should be created.
Every transformation the two transformers should handle, has to be registered here. Here's an example:
```yaml
itb_api_platform_utilities:
  input_transformations:
    - { request_class: 'ITB\ObjectTransformerTestUtilities\Object1', object_class: 'ITB\ObjectTransformerTestUtilities\Object2' }
  output_transformations:
    - { object_class: 'ITB\ObjectTransformerTestUtilities\Object2', response_class: 'ITB\ObjectTransformerTestUtilities\Object1' }
```
Every line represents a registered transformation. The configuration is allowed to be empty,
which means that the transformers of this bundle won't be used. Otherwise, all the keys you see are required
and the have to present fully qualified class names.

> ⚠ The transformers will use the `TransformationMediator` as described. That means there should be transformers,
> that can handle the registered transformations.

That's all you have to configure or implement.

## What else?
For the configuration and usage of Object Transformer bundle see:
* https://github.com/it-bens/object-transformer
* https://github.com/it-bens/object-transformer-bundle

## Contributing
I am really happy that the software developer community loves Open Source, like I do! ♥

That's why I appreciate every issue that is opened (preferably constructive)
and every pull request that provides other or even better code to this package.

You are all breathtaking!