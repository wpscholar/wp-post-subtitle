# Post Subtitle

Adds a subtitle field below the post title field in WordPress.

## Requirements

- PHP 5.4+
- WordPress 4.6+

## Installation

1. Add the module to your project via [Composer](https://getcomposer.org/):

```php
composer require wpscholar/wp-post-subtitle
```

2. Make sure you have added the Composer autoloader to your project:

```php
<?php

require __DIR__ . '/vendor/autoload.php';
```

## Usage

The subtitle feature is activated by adding post type support.

If you are adding support to a pre-existing post type, just add this code:

```php
<?php

add_post_type_support( 'post', 'subtitle' );
```

Be sure to replace `post` with the name of your post type.

Or, in the `supports` argument when registering a post type, just add `subtitle`.

## Available Methods

The following static methods are publicly available:

- `getSubtitle( $post_id )` - Get the subtitle for a specific post.
- `setSubtitle( $post_id, $value )` - Set the subtitle for a specific post. 

## Notes

If you are adding the code to a WordPress plugin or theme, there is no initialization step required. However, if you are adding the code at a higher level in your WordPress project you will need to call the initialization function on the `after_setup_theme` hook, like so:

```php
<?php

add_action( 'after_setup_theme', '\wpscholar\WordPress\PostSubtitle::initialize' );
```
