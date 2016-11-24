# Pressgang a Wordpress Parent Theme Framework
## Overview
_Pressgang_ is more than a standard WordPress parent theme framework designed especially for rapidly bootstrapping WordPress themes. It attempts to modernize many of the aspects of Wordpress theme design by using [.twig](http://twig.sensiolabs.org/) templating (thanks to the excellent [Timber plugin](http://upstatement.com/timber/) and incorporating Twitter's [Bootstrap framework](http://getbootstrap.com/) using [LESS](http://lesscss.org/) for rapid prototyping on the front-end. Pressgang also abstracts away many of the commonly repeated tasks used in building Wordpress themes using DRY (Don't Repeat Yourself) and Convention Over Configuration principles to finally declutter that functions.php.

To really hit the ground running you should be familiar with the following tools:
* http://upstatement.com/timber/
* https://github.com/timber/timber
* http://twig.sensiolabs.org/documentation
* http://getbootstrap.com/
* http://lesscss.org/
* http://gruntjs.com/
* https://getcomposer.org/

## Getting started
Pressgang is designed as a Wordpress _parent theme_ that acts as a library for your _child theme_. You will need to create a child theme to get started (for information on Wordpress Child Themes see the codex - https://codex.wordpress.org/Child_Themes)

The best way to get up and running quickly is using the [pressgang-child](https://github.com/benedict-w/pressgang-child) repository which has a [grunt-init template](http://gruntjs.com/project-scaffolding) for creating a Pressgang ready child theme.

Otherwise clone this repo `git clone https://github.com/benedict-w/pressgang.git` and create your own child theme, or use the composer package: https://packagist.org/packages/benedict-w/pressgang