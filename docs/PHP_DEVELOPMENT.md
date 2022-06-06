# PHP Development in Fragments

1. [Tools](#tools)
    * [Symfony](#symfony)
    * [PHPUnit](#phpunit)
    * [PHP CodeSniffer](#php-codesniffer)

## Tools

### Symfony

* [Symfony Getting Started Guide](https://symfony.com/doc/current/getting_started/index.html)
* [Symfony Documentation](https://symfony.com/doc/4.4/index.html)

### PHPUnit

* [Writing tests for PHPUnit](https://phpunit.readthedocs.io/en/7.4/writing-tests-for-phpunit.html)
* [Available PHPunit assertions](https://phpunit.readthedocs.io/en/7.4/assertions.html)

### PHP CodeSniffer

* [PHP CodeSniffer wiki](https://github.com/squizlabs/PHP_CodeSniffer/wiki)
* [PSR-2 Coding style guide](https://www.php-fig.org/psr/psr-2/)

#### Configure PHP CodeSniffer for your editor

##### Atom

1. Install [`linter-phpcs`](https://atom.io/packages/linter-phpcs)
2. Success!

##### IntelliJ / PHPStorm

1. Open *PHPStorm* > *Preferences* and search for "Code Sniffer".
2. In the *Development environment* > *Configuration* drop down, select *Local* and click the `...` button.
3. At *PHP Code Sniffer path:*, click the browser icon and go to `platform/vendor/bin/phpcs`.
4. In *PHPStorm* > *Preferences* search for "Code Sniffer" again. Check the *PHP Code Sniffer validation* box in *Inspections* > *Quality tools*.
5. In the *Coding standards:* drop down, select "Custom". Click the `...` button and browse to the `platform/phpcs.xml.dist` file to use the correct ruleset.

##### Sublime Text 3

1. Using Package Control install [SublimeLinter](https://github.com/SublimeLinter/SublimeLinter) unless it's already installed
2. Using Package Control install [SublimeLinter-phpcs](https://github.com/SublimeLinter/SublimeLinter-phpcs)
3. Assuming you have created a project inside the `fragment-boilerplate` root, open the project file and add:
```
"settings":
{
    "SublimeLinter.linters.phpcs.args": "--standard='${folder}/platform/phpcs.xml.dist'"
}
```

##### VSCode

1. Install [`vscode-phpcs`](https://github.com/ikappas/vscode-phpcs)
2. In VSCode Preferences > Settings search for "CodeSniffer" and add `./platform/vendor/bin/phpcs` as "Executable path"
