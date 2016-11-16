# Data Input Sheet

Library that abstracts a custom data origin as the spine of a table,
and builds a data sheet using columns defined through a yml configuration file. The library
handle the logic of displaying the table and storing the user input values.

## Instructions

### Download using composer

```bash
composer require arkschools/data-input-sheet
```

### Enable the bundle in the kernel

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Arkschools\DataInputSheet\Bridge\Symfony\DataInputSheetBundle(),
        // ...
    );
}
```

### Create the yml configuration

```yaml
# app/config/config.yml
data_input_sheet:
  config:
    connection: "doctrine.dbal.default_connection"
  extra_column_types:
    color: AppBundle/DataInputSheet/ColumnType/Color  
  sheets:
    cars:
      views:
        "Brand and model": ["Brand name", "Model name", "Description", "Color"]
        "Performance": ["Brand name", "Top speed", "Acceleration"]
      columns:
        "Brand name": string
        "Model name": string
        "Description": text
        "Top speed": integer
        "Acceleration": float
        "Color": color
```

* `config` and `extra_column_types` are optional sections
* `extra_column_types` allows to extend the library with new column types
* Create one sheet per custom data origin
* Create as many views as required, columns can belong to several views
* Create all the needed columns for the sheet and set the appropriate type of each one, base types are "string", "text", "integer" and "float"

### Create the spine service

```yaml
    app.data_input_sheet.cars_spine:
        class: AppBundle\DataInputSheet\CarsSpine
        arguments:
          - @app.repositories.car
        tags:
            - { name: data_input_sheet.spine, sheet: cars }
```

* The tag `data_input_sheet.spine` marks the service as a data input sheet spine and the `sheet` attribute links it with the configured sheet 
* The spine class has to extend `Arkschools\DataInputSheet\Spine` and add the logic for `getSpine` and `getHeader`

### Update your database schema

```bash
php bin/console doctrine:schema:update --force
```

### Create\reuse controller and views

A basic controller and template are provided but we do recommend to build your own one tailored to your project needs

If you want to use the default one just add the following route configuration

```yaml
# app/config/routing.yml
data_input_sheet:
    resource: "@DataInputSheetBundle/Controller/DataInputSheetController"
    type:     annotation
```
