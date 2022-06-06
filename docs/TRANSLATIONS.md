# Translations

Translations are stored in `/translations`. We have adopted a convention where the translation [domain](https://symfony.com/doc/current/components/translation.html#using-message-domains) is utilized to handle per-site translations, with an optional fallback to a `default` domain.

When using translations in the Twig templates, we need to specify the domain/site to reference the correct translation.

Twig examples using the [trans](https://symfony.com/doc/current/reference/twig_reference.html#trans) filter:

```
{% trans_default_domain site_domain %} <- Will set domain for this entire template using a passed variable
<h1>{{ "example.some_heading" | trans }}</h1> <- Pipe the translation message to a twig filter for translation
<h2>{{ "example.some_sub_heading" | trans({'%name%': firstname}) }}</h2> <- Replace translation %variable% with dynamic data
```

```
<h1>{{ "example.some_heading" | trans([], site_domain) }}</h1> <- Manually set domain per translation
<h2>{{ "example.some_sub_heading" | trans([], 'hard-coded-domain') }}</h2>
```
