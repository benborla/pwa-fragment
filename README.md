# Fragment Boilerplate

The fragment boilerplate attempts to provide a number of common examples that can hopefully help to serve as a starting point when building your own fragment service.

If you wish to get started without the examples you can use the code that exists in the [fragment-starter](https://git.plaingaming.net/dumarca/fragment-boilerplate/-/archive/fragment-starter/fragment-boilerplate-fragment-starter.zip) branch.

## Project structure

The project is based on a standard Symfony installation with a few extra packages. Code is organised as follows:

```
platform/
    assets/         Vue components and Jest tests
    config/         Symfony configuration
    public/         Application and web server root
    src/            Fragment controllers, routes, services
    templates/      Twig templates
    tests/          PHPUnit tests
    translations/   Fragment translations
```

## Development

See [GETTING_STARTED.md](docs/GETTING_STARTED.md)

## Translations

See [TRANSLATIONS.md](docs/TRANSLATIONS.md)
