# HashPath

HashPath provides functionality for generating URLs with a checksum either embedded in the path, or appended as a query string. In combination with a web server rewrite rule, browser caching can be completely mitigated as the file URL sent to the browser changes whenever the file does.

```php
// Actual file on disk:
css/style.css

// Using Hashpath:      ↙ File hash
css/style.vpOQ8F6ybteKQQYND5dzZQ.css
```

## License

Hash Path is licensed under an [MIT license](http://heyday.mit-license.org/)

## Installation

### Web server config

As Hash Path returns paths that don't exist on disk by default (`FORMAT_INLINE`), a rewrite rule needs to be added to your web server in order to return the file that was originally given to Hash Path. The inline format is `.v[hash]` inserted before the file extension, so you end up with `.v[hash].[extension]`.

You may need to adjust the regular expression used for the rewrite rule to match the files you are serving. Ideally the pattern should be as specific as possible to avoid unexpected matches.

#### Apache

The following is required in your `.htaccess` file or virtual host config.

```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.+)\.(v[A-Za-z0-9\-\._~]+)\.(js|css|png|jpg|gif)$ $1.$3 [L]
</IfModule>
```

#### Nginx

```
# Hashpath module
location /path/to/your/assets {
	rewrite "^(.+)\.(?:v[A-Za-z0-9\-\._~]+)\.(js|css|png|jpg|gif)$" $1.$2 last;
	try_files $uri =404;
}
```


##Unit Testing

If you have `phpunit` installed you can run HashPath's unit tests to see if everything is functioning correctly:

```bash
$ phpunit
```

## Contributing

### Code guidelines

This project follows the standards defined in:

* [PSR-1](https://github.com/pmjones/fig-standards/blob/psr-1-style-guide/proposed/PSR-1-basic.md)
* [PSR-2](https://github.com/pmjones/fig-standards/blob/psr-1-style-guide/proposed/PSR-2-advanced.md)
