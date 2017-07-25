<?php

namespace Arkschools\DataInputSheets\ColumnType;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceListColumn extends AbstractColumn
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var int[][][] Using keys
     */
    private $lists;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_service_list_cell.html.twig',
            self::STRING
        );
    }

    public function render(\Twig_Environment $twig, $columnId, $spineId, $content, $option = null, bool $readOnly = false)
    {
        [$service, $method] = $option;

        if (!isset($this->lists[$service][$method])) {
            $this->initialiseList($service, $method);
        }

        return $twig->render(
            'DataInputSheetsBundle:extension:data_input_sheets_service_list_cell.html.twig',
            [
                'columnId' => $columnId,
                'spineId'  => $spineId,
                'content'  => $content,
                'list'     => array_keys($this->lists[$service][$method]),
                'readOnly' => $readOnly
            ]
        );
    }

    public function castCellContent(?string $content, $option = null): ?string
    {
        if (null == $content) {
            return null;
        }

        [$service, $method] = $option;

        if (!isset($this->lists[$service][$method])) {
            $this->initialiseList($service, $method);
        }

        if (!isset($this->lists[$service][$method][$content])) {
            return null;
        }

        return $content;
    }

    private function initialiseList(string $service, string $method)
    {
        $this->lists[$service][$method] = array_flip($this->container->get($service)->$method());
    }
}
