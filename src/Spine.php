<?php

namespace Arkschools\DataInputSheets;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Spine
{
    /**
     * @var string
     */
    protected $header;

    /**
     * @var string[]
     */
    protected $spine;

    /**
     * @var object[]
     */
    protected $spineObjects = [];

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var bool
     */
    protected $filtersChanged = false;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $entitySpineField;

    /**
     * @var OptionsResolver
     */
    private $resolver;

    public function __construct($header, array $spine, $tableName = null, $entity = null, $entitySpineField = 'id')
    {
        $this->header           = $header;
        $this->spine            = $spine;
        $this->entity           = $entity;
        $this->tableName        = $tableName;
        $this->entitySpineField = $entitySpineField;
        $this->resolver         = new OptionsResolver();
        $this->filters          = $this->defaultFilter();

        $this->resolver->setDefaults($this->defaultFilter());
    }

    protected function defaultFilter()
    {
        return [];
    }

    /**
     * Title to be used in the view when displaying the available spines
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return $this
     */
    protected function load()
    {
        if (empty($this->spine) || $this->filtersChanged) {
            $this->spine        = [];
            $this->spineObjects = [];

            $this->query();

            $this->filtersChanged = false;
        }

        return $this;
    }

    protected function query()
    {
        // Query spine objects using $this->filters and sort if needed
    }

    /**
     * @param array $filters
     */
    public function setFilters(array $filters)
    {
        $filters = $this->resolver->resolve($filters);

        $this->filtersChanged = false;

        foreach ($filters as $name => $value) {
            if ($this->filters[$name] !== $value) {
                $this->filtersChanged = true;
                break;
            }
        }

        $this->filters = $filters;
    }


    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Array with the spine data
     *
     * @return string[]
     */
    public function getSpine()
    {
        return $this->load()->spine;
    }

    /**
     * @param string $spineId
     *
     * @return bool
     */
    public function hasSpine($spineId)
    {
        return isset($this->load()->spine[$spineId]);
    }

    /**
     * @param string $spineId
     *
     * @return string
     */
    public function getSpineFromId($spineId)
    {
        return (isset($this->load()->spine[$spineId])) ? $this->spine[$spineId] : '';
    }

    /**
     * @param string $spineId
     *
     * @return object
     */
    public function getSpineObject(string $spineId)
    {
        return $this->spineObjects[$spineId] ?? null;
    }

    /**
     * @param string $position
     *
     * @return string |null
     */
    public function getSpineIdFromPosition($position)
    {
        $iterator = new \ArrayIterator($this->load()->spine);

        try {
            $iterator->seek($position);
        } catch (\OutOfBoundsException $e) {
            return null;
        }

        return $iterator->key();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->load()->spine);
    }

    /**
     * Use in case you want to store the user input data in a custom table with the specified structure
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Class of the entity that will store the user input data
     *
     * @return null|string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Field in the entity that stores the SpineId
     *
     * @return null|string
     */
    public function getEntitySpineField()
    {
        return $this->entitySpineField;
    }

    /**
     * Extend this method if you want to filter your own entity query
     *
     * @param EntityManagerInterface $em
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder(EntityManagerInterface $em)
    {
        return $em
            ->createQueryBuilder()
            ->select('o')
            ->from($this->getEntity(), 'o');
    }
}
