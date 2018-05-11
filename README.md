# htaccess-env
Merges .htaccess contents from environment defined files.

#How to use
##1. Install the package
`$ composer require dreamproduction/htaccess-env`

##2. Add/Change composer scripts
First we define a new script entry named **htaccess-env** then add a new script which will fire after each install command in **post-install-cmd** event.

When bundling with composer-boilerplate we have to modify the **/vendor/dreamproduction/composer-boilerplate/composer.settings.json**  file.

// File /vendor/dreamproduction/composer-boilerplate/composer.settings.json
```javascript
"scripts": {
    ...
    "post-install-cmd": [
       "@composer run-script drupal-scaffold",
       "@composer run-script htaccess-env"
    ],
    ...
    "htaccess-env": [
        "./vendor/bin/htaccess-env"
    ],
    ...
```
##3. Create a GIT branch-based file
We need to define a git branch-based file which will be merged with the main file. For example, when targeting **docroot/.htaccess** file, then for each git branch we define the contents that will be merged into the main destination file (i.e. **master.htaccess, dev.htaccess**, etc...). Initially, each branch-based file will have the same contents as the target file.

For targeting **docroot/robots.txt** file the corresponding branch files are as such: **master.robots, dev.robots, stage.robots**, etc..
