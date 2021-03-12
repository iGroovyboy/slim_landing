#SLIMLAND

Landing page cms with block editing and themes.

Can be installed on any shared hosting: uses php and basic sql db or file storage.

## INSTALLATION

//TODO

Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

## THEMES
Themes can use twig or php as a templating engine. To use twig all of your templates must haave 'html' file extension and to use php - use 'php' file extension.  

In order to be found, theme must be placed into root folder 'themes' under some unique folder name like 'my-theme' or 'theme_2021' and must follow rules:
- no spaces
- lower case 
- only english literals
- snake/kebab notation.

Theme developers must place all css, js and images into 'assets' folder. So theme might look like this: 

    /themes
        /my-custom-theme
            /assets
                /css
                /js
                /images
                /fonts
                /somedirectory
            /blocks
                banner.html
            /partials
            home.html


## CACHE
Slimland compiles page aftger every cache bust to be a generic html page when user gets it. While developing you might want to disable caching:
1. ...
2. ...

##DEVELOPERS INFO

- **make server**  - runs local dev php server

###Windows **make** installation:

    choco install make

**Chokolatey** comes with nodejs installation.
