# Template sections

This addon introduces *template inheritance* via **sections** for the [F3](https://github.com/bcosca/fatfree) template engine.

## Install

To install with composer, just run `composer require ikkez/f3-template-sections`.  
In case you do not use composer, add the `src/` folder to your `AUTOLOAD` path or copy the files from `src/` into your libs.


## Initialize

To use the new directives, you need to register them:

Init:

```php
\Template\Tags\Section::init('section');
\Template\Tags\Inject::init('inject');
```



## Usage

Imagine this main site template:


```html
<html>
	<head>
		<title>Site Name</title>
	</head>
	<body>
		<F3:section id="sidebar" class="sidebar">
			<!-- sidebar -->
		</F3:section>
	
		<div class="container">
			<include href="article.html" />
		</div>
	</body>
</html>
```

You see we have create a section with `id=sidebar`. This section can contain default content already if you want, 
but within `article.html` you can now inject content to the sidebar, which is actually in a parent level:

```html
<F3:inject id="sidebar">
	<ul>
		<li><a href="#parrot">Fear me parrot</a></li>
		<li><a href="#rum">Arrr</a></li>
	</ul>
</F3:inject>

<h1 id="parrot">Fear me parrot, ye evil whale!</h1>
<p>All comrades sail gutless, stormy jacks. Ho-ho-ho! hunger of riddle.</p>

<h2 id="rum">Arrr, fine hunger!</h2>
<p>What’s the secret to canned and sun-dried zucchini? Always use quartered rum.</p>
```

That's basically it. There are some more modes for injecting content:

### Append / Prepend

In this example we have a breadcrumb navigation. You can change the generated tag-element with the `tag`-attribute.

```html
<nav class="breadcrumb">
	<F3:section id="breadcrumb" tag="ul">
		<li class="home"><a href="/">Home</a></li>
	</F3:section>
</nav>
```

By default the content in the section is replaced upon inject. If you wish, you just append the existing content like this:


```html
<F3:inject id="breadcrumb" mode="append">
	<li><a href="wiki">Wiki</a></li>
</F3:inject>
```

You can also use `prepend` as inject mode. 


## API



### section

Attributes:

*  `section`  
	Used to identify the new section.
*  `id`  
	Alias to `section`, but the `id` attribute will be visible in the final markup.
*	`tag`  
	The tag-element name of the final section. Default: `section`.  
	When you set `tag="FALSE"`, the section content is not wrapped into any element.

Any other attributes are just passed to the final tag element.

### inject

Attributes:

*  `section`  
	Used to identify the destination section.
*  `id`  
	Alias to `section`.
*  `mode`  
	The injection mode for content:  
	**overwrite** (default): replaces the existing content in the section  
	**append**: adds content after the existing content  
	**prepend**: adds content before the existing content



## Licence

GPLv3


