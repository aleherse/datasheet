<?php

namespace Arkschools\DataInputSheet;

class DataInputSheetRepository
{
    /**
     * @var DataInputSheet[]
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

    public function addSpine(Spine $spine, $datasheetId)
    {
        if (isset($this->config[$datasheetId])) {
            $columns = [];
            foreach ($this->config[$datasheetId]['columns'] as $columnTitle => $columnType) {
                $columns[$columnTitle] = Column::$columnType($columnTitle);
            }

            $views = [];
            foreach ($this->config[$datasheetId]['views'] as $viewTitle => $columnNames) {
                $viewColumns = [];
                foreach ($columnNames as $title) {
                    if (isset($columns[$title])) {
                        $viewColumns[] = $columns[$title];
                    }
                }
                $views[] = new DataInputSheetView($viewTitle, $spine, $viewColumns);
            }

            $this->datasheets[$datasheetId] = new DataInputSheet($datasheetId, $spine, $views);
        }
    }

    /**
     * @return DataInputSheet[]
     */
    public function findAll()
    {
        return $this->datasheets;
    }

    /**
     * @param string $id
     * @return DataInputSheet|null
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

    public function save(View $view, $data)
    {

    }
}
