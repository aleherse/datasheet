<?php

namespace Aleherse\Datasheet;

class DatasheetRepository
{
    /**
     * @var Datasheet[]
     */
    private $datasheets;

    /**
     * @var array
     */
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function addStub(DatasheetStub $stub, $datasheetId)
    {
        if (isset($this->config[$datasheetId])) {
            $columns = [];
            foreach ($this->config[$datasheetId]['columns'] as $columnTitle => $columnType) {
                $columns[$columnTitle] = DatasheetColumn::$columnType($columnTitle);
            }

            $views = [];
            foreach ($this->config[$datasheetId]['views'] as $viewTitle => $columnNames) {
                $viewColumns = [];
                foreach ($columnNames as $title) {
                    if (isset($columns[$title])) {
                        $viewColumns[] = $columns[$title];
                    }
                }
                $views[] = new DatasheetView($viewTitle, $stub, $viewColumns);
            }

            $this->datasheets[$datasheetId] = new Datasheet($datasheetId, $stub, $views);
        }
    }

    /**
     * @return Datasheet[]
     */
    public function findAll()
    {
        return $this->datasheets;
    }

    /**
     * @param string $id
     * @return Datasheet|null
     */
    public function findById($id)
    {
        return (isset($this->datasheets[$id])) ? $this->datasheets[$id] : null;
    }

    public function findViewBy($datasheetId, $viewId)
    {
        $datasheet = $this->findById($datasheetId);
        if (null !== $datasheet) {
            return $datasheet->getView($viewId);
        }

        return null;
    }
}
