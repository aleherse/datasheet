<?php

namespace Arkschools\DataInputSheets;

use Arkschools\DataInputSheets\Bridge\Symfony\Entity\Cell;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpFoundation\Request;

class View
{
    const FORM_NAME = 'DIS';

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
     * @var Column[]
     */
    private $visibleColumns;

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

    /**
     * @param string   $sheetId
     * @param string   $title
     * @param Spine    $spine
     * @param Column[] $columns
     * @param string[] $hiddenColumns
     */
    public function __construct(
        string $sheetId,
        string $title,
        Spine $spine,
        array $columns,
        array $hiddenColumns = []
    ) {
        $this->sheetId = $sheetId;
        $this->id      = \slugifier\slugify($title);
        $this->title   = $title;
        $this->spine   = $spine;

        $this->useExternalEntity = false;

        if (null !== $spine->getEntity()) {
            $this->useExternalEntity = true;
        }

        $this->useCustomTable = false;

        if (null !== $spine->getTableName()) {
            $this->useCustomTable = true;
        }

        $this->columns = [];

        foreach ($columns as $column) {
            $columnId                 = $column->getId();
            $this->columns[$columnId] = $column;

            if (!isset($hiddenColumns[$columnId])) {
                $this->visibleColumns[$columnId] = $column;
            }

            if ($column->isValueColumn()) {
                foreach (array_keys($spine->getSpine()) as $spineId) {
                    $this->contents[$spineId][$columnId] = $column->getValue($spine->getSpineObject($spineId));
                }
            }
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSheetId(): string
    {
        return $this->sheetId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSpineHeader(): string
    {
        return $this->spine->getHeader();
    }

    /**
     * @return string[]
     */
    public function getSpine(): array
    {
        return $this->spine->getSpine();
    }

    public function getSpineFromId(string $spineId): string
    {
        return $this->spine->getSpineFromId($spineId);
    }

    public function getSpineIdFromPosition(int $position): ?string
    {
        return $this->spine->getSpineIdFromPosition($position);
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return Column[]
     */
    public function getVisibleColumns(): array
    {
        return $this->visibleColumns;
    }

    /**
     * @return bool
     */
    public function hasHiddenColumns(): bool
    {
        return count($this->columns) === count($this->visibleColumns);
    }

    public function getColumn(string $columnId): Column
    {
        return (isset($this->columns[$columnId])) ? $this->columns[$columnId] : null;
    }

    public function hasColumn(string $columnId): bool
    {
        return isset($this->columns[$columnId]);
    }

    private function getObject(string $spineId, ?string $columnId = null, ClassMetadataInfo $metadata = null)
    {
        if (!isset($this->objects[$spineId][$columnId])) {
            $this->objects[$spineId][$columnId] = $this->useExternalEntity
                ? $metadata->newInstance()
                : $this->getColumn($columnId)->createCell($this->sheetId, $spineId, null);
        }

        return $this->objects[$spineId][$columnId];
    }

    public function getContent(string $spineId, string $columnId)
    {
        if (isset($this->contents[$spineId][$columnId])) {
            return $this->contents[$spineId][$columnId];
        }

        return null;
    }

    public function hasSpine(string $spineId): bool
    {
        return $this->spine->hasSpine($spineId);
    }

    public function count(): int
    {
        return $this->spine->count();
    }

    private function contentChanged(string $spineId, string $columnId, $content): bool
    {
        return $this->getContent($spineId, $columnId) !== $content;
    }

    public function extractDataFromRequest(Request $request)
    {
        return $request->request->get(self::FORM_NAME, []);
    }

    public function loadContent(EntityManager $em): View
    {
        if ($this->useExternalEntity) {
            $entity = $this->spine->getEntity();
            if ($this->useCustomTable) {
                $this->setCustomTableName($em, $entity);
            }

            $objects       = $this->spine->getQueryBuilder($em)->getQuery()->execute();
            $metadata      = $em->getClassMetadata($entity);
            $this->objects = [];

            foreach ($objects as $object) {
                $spineId                       = $metadata->getFieldValue($object, $this->spine->getEntitySpineField());
                $this->objects[$spineId][null] = $object;

                foreach ($this->columns as $column) {
                    if (!$column->isValueColumn()) {
                        $this->contents[$spineId][$column->getId()] = $metadata->getFieldValue($object, $column->getField());
                    }
                }
            }
        } else {
            $cells         = $this->getCells($em);
            $this->objects = [];

            foreach ($cells as $cell) {
                $spine  = $cell->getSpine();
                $column = $cell->getColumn();

                $this->objects[$spine][$column]  = $cell;
                $this->contents[$spine][$column] = $cell->getContent();
            }
        }

        return $this;
    }

    public function persist(EntityManager $em, array $data): void
    {
        if ($this->useExternalEntity) {
            $entity = $this->spine->getEntity();
            if ($this->useCustomTable) {
                $this->setCustomTableName($em, $entity);
            }

            $metadata = $em->getClassMetadata($entity);

            foreach ($data as $spineId => $columnsData) {
                $object  = $this->getObject($spineId, null, $metadata);
                $persist = false;

                $metadata->setFieldValue($object, $this->spine->getEntitySpineField(), $spineId);

                foreach ($columnsData as $columnId => $content) {
                    $column = $this->getColumn($columnId);

                    if (null === $column) {
                        continue;
                    }

                    if (!$column->isStored()) {
                        continue;
                    }

                    $content = $column->castCellContent($content);

                    if ($this->contentChanged($spineId, $columnId, $content)) {
                        $persist = true;

                        $metadata->setFieldValue($object, $column->getField(), $content);
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
                    if (!isset($this->columns[$columnId])) {
                        continue;
                    }

                    if (!$this->columns[$columnId]->isStored()) {
                        continue;
                    }

                    $content = $this->columns[$columnId]->castCellContent($content);

                    if ($this->contentChanged($spineId, $columnId, $content)) {
                        /** @var Cell $cell */
                        $cell = $this->getObject($spineId, $columnId);

                        null !== $content
                            ? $em->persist($cell->setContent($content))
                            : $em->remove($cell);
                    }
                }
            }
        }
    }

    private function setCustomTableName(EntityManager $em, string $class = null): void
    {
        $em
            ->getClassMetadata($class ?? Cell::class)
            ->setPrimaryTable(['name' => $this->spine->getTableName()]);
    }

    /**
     * @param EntityManager $em
     *
     * @return Bridge\Symfony\Entity\Cell[]
     */
    private function getCells(EntityManager $em): array
    {
        $query = $em
            ->createQueryBuilder()
            ->select('c')
            ->from(Cell::class, 'c');

        if ($this->useCustomTable) {
            $this->setCustomTableName($em);
        } else {
            $query
                ->where('c.sheet = :sheetId')
                ->setParameter('sheetId', $this->sheetId);
        }

        $columns = [];

        foreach ($this->columns as $columnName => $column) {
            if (!$column->isValueColumn()) {
                $columns[] = $columnName;
            }
        }

        $query
            ->andWhere('c.column IN (:columns)')
            ->setParameter('columns', $columns);

        return $query->getQuery()->execute();
    }
}
