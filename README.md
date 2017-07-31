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

### Create the spine and its service

This is the most important file and where the spine column data is queried from, we need to create a class that extends
from `Arkschools\DataInputSheets\Spine` and add our logic to `load` and `__construct`

```PHP
class CarsSpine extends Arkschools\DataInputSheets\Spine
{
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        parent::__construct(
            'Available Cars',
            []
        );

        $this->carRepository = $carRepository;
    }

    protected function load()
    {
        if (empty($this->spine)) {
            $this->spine = [];

            $cars = $this->carRepository->findAll();

            foreach ($cars as $car) {
                $this->spine[$car->id()] = $car->getName();
            }

            asort($this->spine);
        }

        return $this;
    }
}
```

Next step is to create a tagged service with our spine, to do so add the tag `data_input_sheets.spine` that marks
the service as a data input sheet spine and the `sheet` attribute links it with the configured sheet 

```yaml
    AppBundle\DataInputSheets\CarsSpine:
        tags:
            - { name: data_input_sheets.spine, sheet: cars }
```

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
 
Create a table with the same structure as `data_input_sheets_cell`, in this example is called 'cars', and modify the spine accordingly

```PHP
class CarsSpine extends Arkschools\DataInputSheets\Spine
{
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        parent::__construct(
            'Available Cars',
             [],
             'cars'
         );

        $this->carRepository = $carRepository;
    }
...
```

From now on that sheet data will be stored in the custom table

### Data stored in library user controlled entity

More control can be obtained setting an entity class that will store the data of an specific sheet 

Create the desired entity class with as many properties as columns needs the sheet, for this example it would require
`id`, `brand`, `model` and `description`, please take into account the column types when setting the entity property types

Then modify accordingly the spine class

```PHP
class CarsSpine extends Arkschools\DataInputSheets\Spine
{
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        parent::__construct(
            'Available Cars',
             [],
             null
             AppBundle\Entity\Car::class,
             'id'
         );

        $this->carRepository = $carRepository;
    }
...
```

For the previous example this should be the new configuration file content

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

### Create a custom column type

As explained above new column can be added through the configuration parameter `extra_column_types`

* Extend `Column` abstract class and implement the abstract methods
* Extend the other methods if required for your implementation
* Add the newly created class using the `extra_column_types` configuration

### Create a custom column type as a service

```yaml
    AppBundle\DataInputSheets\ColumnState:
        tags:
            - { name: data_input_sheets.column, type: state }
```

* The tag `data_input_sheets.column` marks the service as a data input sheet extra column and the `type` attribute links it with the newly added column type 
* The column class has to extend `Arkschools\DataInputSheets\ColumnType\AbstractColumn` and add at least the logic for `__construct`

### Filter a spine at view level

A filter can be added on each view to limit the spine elements displayed in that view
                             
```yaml
  sheets:
    cars:
      views:
        "Classics":
          filters: {age: '>25'}
          columns: ["Brand name", "Model name", "Description", "Color"]
```

In the previous example "Classics" view will just display cars older than 25 years, 
to make this possible we need to modify the spine class to have a default filter and to use it during load
 
```PHP
class CarsSpine extends Arkschools\DataInputSheets\Spine
{
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        parent::__construct(
            'Available Cars',
            []
        );

        $this->carRepository = $carRepository;
    }

    protected function defaultFilter()
    {
        return ['age' => null];
    }
        
    protected function load()
    {
        if (empty($this->spine) || $this->filtersChanged) {
            $this->spine = [];
            
            if (empty($this->filters['age']) {
                $cars = $this->carRepository->findAll();
            else {
                $cars = $this->carRepository->findByAge($this->filters['age']);
            }

            foreach ($cars as $car) {
                $this->spine[$car->id()] = $car->getName();
            }

            $this->filtersChanged = false;
            asort($this->spine);
        }

        return $this;
    }
}
```

### Filter a spine via user selection

Apart of the filters that can be added at the view level of the configuration file, a custom selector can be added after choosing a view
and before showing its content. This selector can be used to narrow further down the spine elements displayed in the view.

As we saw previously a `Spine` can extend the methods `defaultFilter` to add a configuration level filter and the `load` 
method to make use of it, the idea behind this new filter is to add the user selection filter to this very same place.

To make it possible we need to create a new class that extends `Arkschools\DataInputSheets\Selector\AbstractSelector` 

```php
class DealerSelector extends Arkschools\DataInputSheets\Selector\AbstractSelector
{
    const DEALER = 'dealer';

    private $dealerRepository;

    public function __construct(DealerRepository $dealerRepository)
    {
        $this->dealerRepository = $dealerRepository;
        $this->filters          = [self::DEALER => null];
    }

    public function render(\Twig_Environment $twig, array $filters): string
    {
        // $filters contains filters that are declared in the spine, like age in the previous example
        
        $dealers = $this->dealerRepository->findAll();

        return $twig->render(
            'AppBundle:selector:dealer_selector.html.twig',
            ['dealers' => $dealers]
        );
    }

    public function applyFilters(Request $request): bool
    {
        $dealer  = $request->query->get(self::DEALER);
        $changed = false;

        if ($this->filters[self::DEALER] !== $dealer) {
            $this->filters[self::DEALER] = $dealer;
            $changed = true;
        }

        return $changed;
    }

    public function isRequired(): bool
    {
        return empty($this->filters[self::DEALER]);
    }
}
```

Then add the selector as a service tagged with `data_input_sheets.selector` and a type that will be used in the configuration

```yaml
    AppBundle\DataInputSheets\DealerSelector:
        tags:
            - { name: data_input_sheets.selector, type: dealer }
```

Next step is to create the Twig template that will display the selector, it should be something like this

```twig
<form name="selector" method="get">
    <label for="{{ constant('AppBundle\\DataInputSheets\\DealerSelector::DEALER') }}">Dealer:</label>
    <select class="form-control" name="{{ constant('AppBundle\\DataInputSheets\\DealerSelector::DEALER') }}">
        <option value="">Dealers:</option>
        {% for dealer in dealers %}
            <option value="{{ dealer.code }}">{{ dealer.name }}</option>
        {% endfor %}
    </select>
    <button type="submit" class="btn btn-primary">Continue</button>
</form>
```

And finally modify the sheet configuration file to make use of the new selector 

```yaml
# app/config/config.yml
data_input_sheets:
  sheets:
    cars:
      selector: 'dealer'
      views:
        "Brand and model":
          columns: ["Brand name", "Model name", "Description"]
      columns:
        "Brand name": string
        "Model name": string
        "Description": text
```
