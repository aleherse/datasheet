<?php

namespace Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheet\Bridge\Symfony\Entity\CustomCell;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class View
{
    /**
     * @var string
     */
    private $sheetId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $id;

    /**
     * @var Spine
     */
    private $spine;

    /**
     * @var Column[]
     */
    private $columns;

    /**
     * @var array
     */
    private $objects;

    /**
     * @var mixed
     */
    private $contents;

    /**
     * @var bool
     */
    private $useExternalEntity;

    /**
     * @var bool
     */
    private $useCustomTable = false;

    public function __construct(
        $sheetId,
        $title,
        Spine $spine,
        array $columns
    ) {
        $this->sheetId    = $sheetId;
        $this->id         = \slugifier\slugify($title);
        $this->title      = $title;
        $this->spine      = $spine;

        $this->useExternalEntity = false;
        if (null !== $spine->getEntity()) {
            $this->useExternalEntity = true;
        }

        $this->useCustomTable = false;
        if (null !== $spine->getTableName()) {
            $this->useCustomTable = true;
        }

        /** @var Column $column */
        $this->columns = [];
        foreach ($columns as $column) {
            $this->columns[$column->getId()] = $column;
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSheetId()
    {
        return $this->sheetId;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSpineHeader()
    {
        return $this->spine->getHeader();
    }

    /**
     * @return \string[]
     */
    public function getSpine()
    {
        return $this->spine->getSpine();
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $columnId
     * @return Column
     */
    public function getColumn($columnId)
    {
        return (isset($this->columns[$columnId])) ? $this->columns[$columnId] : null;
    }

    /**
     * @param string $spineId
     * @param string $columnId
     * @param ClassMetadataInfo $metadata
     * @return \StdClass
     */
    private function getObject($spineId, $columnId = null, ClassMetadataInfo $metadata = null)
    {
        if (!isset($this->objects[$spineId][$columnId])) {
            if ($this->useExternalEntity) {
                $this->objects[$spineId][$columnId] = $metadata->newInstance();
            } else {
                $this->objects[$spineId][$columnId] = $this->getColumn($columnId)->createCell($this->sheetId, $spineId, null, $this->useCustomTable);
            }
        }

        return $this->objects[$spineId][$columnId];
    }

    /**
     * @param $spineId
     * @param $columnId
     * @return mixed
     */
    public function getContent($spineId, $columnId)
    {
        if (isset($this->contents[$spineId][$columnId])) {
            return $this->contents[$spineId][$columnId];
        }

        return null;
    }

    /**
     * @param string $spineId
     * @param string $columnId
     * @param string $content
     * @return bool
     */
    private function contentChanged($spineId, $columnId, $content)
    {
        return $this->getContent($spineId, $columnId) !== $content;
    }

    public function loadContent(EntityManager $em)
    {
        if ($this->useExternalEntity) {
            $objects  = $this->spine->getQueryBuilder($em)->getQuery()->execute();
            $metadata = $em->getClassMetadata($this->spine->getEntity());

            $this->objects = [];
            foreach ($objects as $object) {
                $spineId = $metadata->getFieldValue($object, $this->spine->getEntitySpineField());
                $this->objects[$spineId][null] = $object;
                foreach ($this->columns as $column) {
                    $this->contents[$spineId][$column->getId()] = $metadata->getFieldValue($object, $column->getField());
                }
            }
        } else {
            $cells = $this->getCells($em);

            $this->objects = [];
            foreach ($cells as $cell) {
                $this->objects[$cell->getSpine()][$cell->getColumn()] = $cell;
                $this->contents[$cell->getSpine()][$cell->getColumn()] = $cell->getContent();
            }
        }

        return $this;
    }

    public function persist(EntityManager $em, $data)
    {
        if ($this->useExternalEntity) {
            $metadata = $em->getClassMetadata($this->spine->getEntity());
            foreach ($data as $spineId => $columnsData) {
                $object  = $this->getObject($spineId, null, $metadata);
                $persist = false;
                $metadata->setFieldValue($object, $this->spine->getEntitySpineField(), $spineId);
                foreach ($columnsData as $columnId => $content) {
                    $column = $this->getColumn($columnId);
                    if (null !== $column) {
                        $content = $column->castCellContent($content);
                        if ($this->contentChanged($spineId, $columnId, $content)) {
                            $persist = true;
                            $metadata->setFieldValue($object, $column->getField(), $content);
                        }
                    }
                }

                if ($persist) {
                    $em->persist($object);
                }
            }
        } else {
            if ($this->useCustomTable) {
                $this->setCustomTableName($em);
            }

            foreach ($data as $spineId => $columnsData) {
                foreach ($columnsData as $columnId => $content) {
                    if (isset($this->columns[$columnId])) {
                        $content = $this->columns[$columnId]->castCellContent($content);

                        if ($this->contentChanged($spineId, $columnId, $content)) {
                            /** @var CustomCell $cell */
                            $cell = $this->getObject($spineId, $columnId);
                            if (null !== $content) {
                                $em->persist($cell->setContent($content));
                            } else {
                                $em->remove($cell);
                            }
                        }
                    }
                }
            }
        }
    }

    private function setCustomTableName(EntityManager $em)
    {
        $em
            ->getClassMetadata(CustomCell::class)
            ->setPrimaryTable(['name' => $this->spine->getTableName()]);
    }

    /**
     * @param EntityManager $em
     * @return Bridge\Symfony\Entity\CustomCell[]
     */
    private function getCells(EntityManager $em)
    {
        $query = $em
            ->createQueryBuilder()
            ->select('c')
            ->where('c.column IN (:columns)')
            ->setParameter('columns', array_keys($this->columns));

        if ($this->useCustomTable) {
            $this->setCustomTableName($em);
            $query->from(CustomCell::class, 'c');
        } else {
            $query
                ->from(Cell::class, 'c')
                ->andWhere('c.sheet = :sheetId')
                ->setParameter('sheetId', $this->sheetId);
        }

        return $query->getQuery()->execute();
    }
}
