# Data Input Sheets

Library that abstracts a custom data origin as the spine of a table,
and builds a data sheet using columns defined through a yml configuration file. The library
handle the logic of displaying the table and storing the user input values.
Each row can be accessed into a new page that will display a table with one row per object column  

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
        "Brand and model":
          columns: ["Brand name", "Model name", "Description", "Color"]
        "Performance":
          columns: ["Brand name", "Top speed", "Acceleration"]
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
* Create all the needed columns for the sheet and set the appropriate type of each one 

### Advanced yml configuration

Sometimes a view has too many columns to be displayed as a table for each spine value, in that case the view can be declared like this

```yaml
  sheets:
    cars:
      views:
        "Brand and model":
            columns:
                - "Brand name"
                - "Model name"
                - { column: "Description", hide: true }
                - "Color"
```

All the columns will be displayed when accessed an individual spine element

It is possible to lock down a column to prevent modifications via configuration, this is useful if there are periods of time when you don't want users to modify some of the columns 

```yaml
# app/config/config.yml
data_input_sheets:
  sheets:
    cars:
      views:
        "Brand and model":
          columns: ["Brand name", "Model name", "Description"]
      columns:
        "Brand name":
            type: string
            read_only: true
        "Model name": string
        "Description": text
```

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

A filter can be added on each view to limit the spine elements shown in there
                             
```yaml
  sheets:
    cars:
      views:
        "Classics":
          filters: {age: '>25'}
          columns: ["Brand name", "Model name", "Description", "Color"]
```

In the previous example "Classics" will show just cars older than 25 years, 
to make this possible `defaultFilter` should be extended to have the default filter values
and `load` should make use of the values of the filters attribute to query the spine objects 

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


## Available column types

### integer

* Uses an input html element to capture the data
* Casts the input value into an integer and stores it in the database as an integer

### float

* Uses an input html element to capture the data
* Casts the input value into a float and stores it in the database as a float

### string

* Uses an input html element to capture the data
* Stores the input value in the database as a string casting empty strings into null values

### text

* Uses a textarea html element to capture the data
* Stores the input value in the database as a string casting empty strings into null values

### yes/no

* Uses a select element with options '' => '', Y' => yes and 'N' => no
* Uses '', 'Y' or 'N' values casting them into null or boolean and stores them in the database as empty or boolean

### gender

* Uses a select element with options '' => '', 'M' => M and 'F' => F
* Uses '', M' or 'F' values and stores them in the database as a string or null

### serviceList

* Requires the usage of the attribute option that contains an array with as first element a service name and as a second element a method name from that service 

```yaml
    - column: 'Car Design'
      type:   'serviceList'
      option: ['app.data_input_sheets.car_lists', 'getCarDesigns']
```

* A service with the name `app.data_input_sheets.car_lists` should exists with a method named `getCarDesigns` that will return and array with the list of allowed values 

```php
class CarLists
{
    public function getCarDesigns()
    {
        return ['Coupé', 'Sedan', 'SUV', 'Crossover'];
    }
}
```

* Uses a select element with the options from the list adding at the start an empty option
* Stores the selected value in the database as a string casting empty strings as null

### ->methodName

* `methodName` can be any string that can be used as a PHP object method name
* Requires that the Spine in the `load` method stores in the `spineObjects` property and array of spine objects indexed by the spineId

```php
    protected function load()
    {
        if (null === $this->spine) {
            $this->spine        = [];
            $this->spineObjects = [];

            foreach ($this->cars as $car) {
                $this->spine[$car->id] = $car->getModel();
                $this->spineObjects[$car->id] = $car;
            }

            asort($this->spine);
        }

        return $this;
    }
```

* Spine objects should have a method named `methodName` and arguments can be optionally passed to that method through the `option` attribute 

```yaml
    - column: 'Car length'
      type:   '->getLength'
      option: ['meters']
```

* It is used just to display values, nothing will be stored in the database 

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

More control can be obtained setting an entity class that will store the data of an specific sheet 

* Create the desired entity class with as many properties as columns needs the sheet (including one for the spine id)
* Update the spine `getEntity` method to return the fully qualified entity class name
* Update the spine `getEntitySpineField` method to return the entity property that will store the spine id (by default is `id`)
* Update the sheet configuration setting per each column the attribute field with the entity linked property name (example below)

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

An entity with the properties `id`, `brand`, `model` and `description` is required to make it possible to store this sheet input data, please take in consideration the column types when setting the entity property types 

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
