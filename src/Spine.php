<?php

namespace Arkschools\DataInputSheet;

use Doctrine\ORM\EntityManager;

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

    public function __construct($header, array $spine, $tableName = null, $entity = null, $entitySpineField = 'id')
    {
        $this->header           = $header;
        $this->spine            = $spine;
        $this->entity           = $entity;
        $this->tableName        = $tableName;
        $this->entitySpineField = $entitySpineField;
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
     * Array with the spine data
     *
     * @return string[]
     */
    public function getSpine()
    {
        return $this->spine;
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
     * @param EntityManager $em
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder(EntityManager $em)
    {
        return $em
            ->createQueryBuilder()
            ->select('o')
            ->from($this->getEntity(), 'o');
    }
}
