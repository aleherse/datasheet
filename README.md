# Data Input Sheets

Library that abstracts a custom data origin as the spine of a table,
and builds a data sheet using columns defined through a yml configuration file. The library
handle the logic of displaying the table and storing the user input values.

## Instructions

### Download using composer

```bash
composer require arkschools/data-input-sheets
```

### Enable the bundle in the kernel

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Arkschools\DataInputSheets\Bridge\Symfony\DataInputSheetsBundle(),
        // ...
    );
}
```

### Create the yml configuration

```yaml
# app/config/config.yml
data_input_sheets:
  extra_column_types:
    color: AppBundle/DataInputSheets/ColumnType/Color  
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

* `extra_column_types` is an optional section that allows to extend the library with new column types
* Create one sheet per custom data origin
* Create as many views as required, columns can belong to several views
* Create all the needed columns for the sheet and set the appropriate type of each one, base types are "integer", "float", "string" and "text" 

### Create the spine service

```yaml
    app.data_input_sheets.cars_spine:
        class: AppBundle\DataInputSheets\CarsSpine
        arguments:
          - @app.repositories.car
        tags:
            - { name: data_input_sheets.spine, sheet: cars }
```

* The tag `data_input_sheets.spine` marks the service as a data input sheet spine and the `sheet` attribute links it with the configured sheet 
* The spine class has to extend `Arkschools\DataInputSheets\Spine` and add the logic for `load` and `__construct`

### Update your database schema 

```bash
php bin/console doctrine:schema:update --force
```

### Create\reuse controller and views

A basic controller and template are provided but we do recommend to build your own one tailored to your project needs

If you want to use the default one just add the following route configuration

```yaml
# app/config/routing.yml
data_input_sheets:
    resource: "@DataInputSheetsBundle/Controller/DataInputSheetsController"
    type:     annotation
```

## Advanced use cases

Out of the box this library allow user to store the data in a table created by the library, but this behaviour can be changed

### Data not stored in the default entity manager

A different Entity Manager can be set by adding a parameter to the `parameters.yml` file

```yaml
    data_input_sheets.entity_manager_name: data
```

### Data stored in library user controlled table

A custom table can be set to store the data input of an specific sheet
 
* Create a table with the same structure as `data_input_sheets_cell`
* Update the spine `getTableName` method to return your previously created table name
* From now on that sheet data will be stored in the custom table

### Data stored in library user controlled entity

More control can be obtained setting the entity that will store the data input of an specific sheet 

* Create the desired entity with as many attributes as sheet columns (including one for the spine id)
* Update the spine `getEntity` method to return the fully qualified entity class name
* Update the spine `getEntitySpineField` method to return the entity attribute that will store the spine id (by default is `id`)
* Update the sheet configuration with the entity field linked with each column (example below)

Given the configuration:

```yaml
# app/config/config.yml
data_input_sheets:
  sheets:
    cars:
      views:
        "Brand and model": ["Brand name", "Model name", "Description"]
      columns:
        "Brand name":
            type: string
            field: brand
        "Model name":
            type: string
            field: model
        "Description": 
            type: text
            field: description
```

An entity with the fields `id`, `brand`, `model` and `description` is required to store this sheet input data, take in consideration the column types when setting the entity field types 

### Create a custom column type

As explained above new column can be added through the configuration parameter `extra_column_types`

* Extend `Column` abstract class and implement the abstract methods
* Extend the other methods if required for your implementation
* Add the newly created class using the `extra_column_types` configuration

### Create a custom column type as a service

```yaml
    app.data_input_sheets.grade_column:
        class: AppBundle\DataInputSheets\ColumnGrade
        arguments:
          - @app.repositories.grades
        tags:
            - { name: data_input_sheets.column, type: grade }
```

* The tag `data_input_sheets.column` marks the service as a data input sheet extra column and the `type` attribute links it with the newly added column type 
* The column class has to extend `Arkschools\DataInputSheets\ColumnType\AbstractColumn` and add at least the logic for `__construct`
