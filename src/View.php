<?php

namespace Arkschools\DataInputSheets;

use Arkschools\DataInputSheets\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheets\Selector\AbstractSelector;
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
     * @var array
     */
    private $filters;

    /**
     * @var bool
     */
    private $filtersApplied;

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
     * @var AbstractSelector
     */
    private $selector;

    /**
     * @param string $sheetId
     * @param string $title
     * @param Spine $spine
     * @param array $spineFilter
     * @param Column[] $columns
     * @param string[] $hiddenColumns
     * @param AbstractSelector|null $selector
     */
    public function __construct(
        string $sheetId,
        string $title,
        Spine $spine,
        array $spineFilter,
        array $columns,
        array $hiddenColumns = [],
        AbstractSelector $selector = null
    )
    {
        $this->sheetId  = $sheetId;
        $this->id       = \slugifier\slugify($title);
        $this->title    = $title;
        $this->spine    = $spine;
        $this->filters  = $spineFilter;
        $this->selector = $selector;

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
        return $this->filteredSpine()->getSpine();
    }

    public function getSpineFromId(string $spineId): string
    {
        return $this->filteredSpine()->getSpineFromId($spineId);
    }

    public function getSpineObjectFromId(string $spineId)
    {
        return $this->filteredSpine()->getSpineObject($spineId);
    }

    public function getSpineIdFromPosition(int $position): ?string
    {
        return $this->filteredSpine()->getSpineIdFromPosition($position);
    }

    private function filteredSpine(): Spine
    {
        if (!$this->filtersApplied) {
            $selectorFilters = $this->selector ? $this->selector->getFilters() : [];
            $this->spine->setFilters(array_merge($this->filters, $selectorFilters));
            $this->filtersApplied = true;
        }

        return $this->spine;
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
        return count($this->columns) !== count($this->visibleColumns);
    }

    public function getColumn(string $columnId): ?Column
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
        return $this->filteredSpine()->hasSpine($spineId);
    }

    public function count(): int
    {
        return $this->filteredSpine()->count();
    }

    private function contentChanged(string $spineId, string $columnId, $content): bool
    {
        return $this->getContent($spineId, $columnId) !== $content;
    }

    public function extractDataFromRequest(Request $request): array
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

        /** @var Column[] $valueColumns */
        $valueColumns = [];
        foreach ($this->columns as $column) {
            if ($column->isValueColumn()) {
                $valueColumns[] = $column;
            }
        }

        if (!empty($valueColumns)) {
            foreach ($this->getSpine() as $spineId => $spine) {
                $object = $this->getSpineObjectFromId($spineId);
                foreach ($valueColumns as $column) {
                    $this->contents[$spineId][$column->getId()] = $column->getValue($object);
                }
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
                if (!$this->hasSpine($spineId)) {
                    continue;
                }

                $object  = $this->getObject($spineId, null, $metadata);
                $persist = false;

                $metadata->setFieldValue($object, $this->spine->getEntitySpineField(), $spineId);

                foreach ($columnsData as $columnId => $content) {
                    $column = $this->getColumn($columnId);

                    if (null === $column || !$column->isStored() || $column->isReadOnly()) {
                        continue;
                    }

                    $content = $column->castCellContent($content);

                    if ($this->contentChanged($spineId, $columnId, $content)) {
                        $persist = true;

                        $metadata->setFieldValue($object, $column->getField(), $content);
                    }
                }

                if ($persist) {
                    if (method_exists($object, 'processSpineId')) {
                        $object->processSpineId();
                    }

                    $em->persist($em->merge($object));
                }
            }
        } else {
            if ($this->useCustomTable) {
                $this->setCustomTableName($em);
            }

            foreach ($data as $spineId => $columnsData) {
                if (!$this->hasSpine($spineId)) {
                    continue;
                }

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

    public function isSelectionRequired(): bool
    {
        if (null === $this->selector) {
            return false;
        }

        return $this->selector->isRequired();
    }

    public function applySelection(Request $request): void
    {
        if (null === $this->selector) {
            return;
        }

        $changed              = $this->selector->applyFilters($request);
        $this->filtersApplied = $this->filtersApplied && !$changed;
    }

    public function renderSelector(\Twig_Environment $twig): string
    {
        if (null === $this->selector) {
            return '';
        }

        return $this->selector->render($twig, $this->filteredSpine()->getFilters());
    }

    public function getSelectorFilters(): array
    {
        if (null === $this->selector) {
            return [];
        }

        return $this->selector->getFilters();
    }
}
