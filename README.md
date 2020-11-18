 > **THIS PROJECT HAS BEEN ARCHIVED**
 >
 > Please see https://code.usgs.gov/ghsc/hazdev/earthquake-network-operations

earthquake-network-operations
=============================

Seismic network operations monitoring pages.

## Getting Started
- run `npm install` to install application development dependencies
- configure the application
- run `grunt` from the install directory

## Configuration
- run `src/lib/pre-install` to setup config.ini
- configuration options are defined in `src/lib/configure.inc.php`
- `MOUNT_PATH` is the base url for the application

## CSS
- SCSS files (`*.scss`, `!_*.scss`) in the `src/htdocs/css` directory are compiled.

- Path is configured in `gruntconfig/config.js`:
```
cssPath: [
  'src/htdocs/css',
  'node_modules/hazdev-webutils/src'
]
```

## JS
- JS files (`*.js`) in the `src/htdocs/js` directory are compiled.

- Path is configured in `gruntconfig/config.js`:
```
jsPath: {
  // DIRECTORY: EXPORT_PATTERN,

  # export all files in these directories in htdocs/js/bundle.js
  # for use in testing
  'src/htdocs/js': '*/*.js',
  'node_modules/hazdev-webutils/src': '**/*.js',

  # add to path, but don't export
  'node_modules/other-module/dist': null
}
```

## Docker

### Building a container

From root of project, run:
    ```
    docker build -t earthquake-network-operations:version .
    ```

### Running container

- Run the container using the tag
    ```
    docker run -it -p 8000:8881 earthquake-network-operations:version
    ```

- Connect to running container in browser
    ```
    open http://localhost:8000/
    ```


## MySQL

### Loading data

This script assumes that you already have a MySQL instance running with a
database that you can load data into.

- From root of project, run:
    ```
    src/lib/pre-install
    ```

### Removing data
- From root of project, run:
    ```
    src/lib/uninstall
    ```
