# JavaScript Development in Fragments

1. [Tools](#tools)
   1. [Vue](#vue)
   2. [Jest](#jest)
   3. [ESLint](#eslint)
   4. [Webpack](#webpack)
5. [Recommended Component Structure](#recomended-component-structure)

## Tools

### Vue

* [Vue Getting Started Guide](https://vuejs.org/v2/guide/)
* [Vue API](https://vuejs.org/v2/api/)
* [Vue Style Guide](https://vuejs.org/v2/style-guide/)

### Jest

* [Jest Getting Started Guide](https://jestjs.io/docs/en/getting-started)
* [Jest API](https://jestjs.io/docs/en/api)

### ESLint

* [ESLint Getting Started Guide](https://eslint.org/docs/user-guide/getting-started)
* [ESLint Rules](https://eslint.org/docs/rules/)
* We use some presets for rules, if two rules collide the later rule will have precedence:
  * [eslint:recommended](https://eslint.org/docs/rules/)
  * [airbnb](https://github.com/airbnb/javascript)
  * [vue/recommended](https://github.com/vuejs/eslint-plugin-vue#priority-c-recommended-minimizing-arbitrary-choices-and-cognitive-overhead)

#### Configure ESLint for your editor

##### Atom

1. Install [`linter-eslint`](https://atom.io/packages/linter-eslint) and its dependencies
2. Install [`language-vue`](https://atom.io/packages/language-vue)
3. Add `text.html.vue` to `linter-eslint`'s *List of scopes to run ESLint on* configuration option

##### IntelliJ / PHPStorm

1. Follow steps 3, 4, 5, and 7 from [IntelliJ IDE - ESLint](https://www.jetbrains.com/help/idea/eslint.html).
2. Add the following to *Extra ESLint Options*:
```
--ext .js,.vue
```

##### Sublime Text 3

1. Install [`SublimeLinter-eslint`](https://github.com/SublimeLinter/SublimeLinter-eslint)
2. Follow the steps under [Using eslint with plugins (e.g. vue)](https://github.com/SublimeLinter/SublimeLinter-eslint#using-eslint-with-plugins-eg-vue) to configure ESLint to lint `.vue` files

##### VSCode

1. Install [`ESLint`](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint)
2. Add the following you to your `settings.json`:
```
"eslint.validate": [
    "javascript",
    {
        "language": "vue",
        "autoFix": true
    }
]
```

### Webpack

* [Webpack Getting Started Guide](https://webpack.js.org/guides/getting-started/)
* [Webpack Configuration Documentation](https://webpack.js.org/configuration/)

## Recommended Component Structure

It is recommended to split your Vue Component into three distinct parts. These are [Views](#views), [Components](#components) and [API](#api). The separation is meant to separate out what is responsible for what as well as increase reusability and testability.

### Views

A view is the glue that holds your components together. They are still [Vue components](https://vuejs.org/v2/guide/components.html) but their goal is to keep track of what to display to the user at what point. A view can wrap multiple views and multiple components. It can hold a state but rarely emit events. Examples of use cases for views are:
* Showing a loading spinner component until the data is loaded and then swapping to a second view.
* Sidebars with components not directly related to main content components
* Tabbed pages

### Components

In the Components folder we have [Vue components that represent individual functionality](https://vuejs.org/v2/guide/components.html). This can be a form item, a jackpot ticker, a button for selecting a payment provider, or anything that has its own state and that will emit events.

### API

In the API folder we have functions and classes that handle fetching requests and uploading responses from and to the backend. If we separate out this it is easier to write tests for both the API code and for the Components. It also helps reusability.
