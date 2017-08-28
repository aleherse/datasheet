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
            $this->initialiseList($service, $method, $option[2]['method'] ?? null);
        }

        if (is_array($content)) {
            $content = array_flip($content);
        }

        return $twig->render(
            $this->template,
            [
                'columnId' => $columnId,
                'spineId'  => $spineId,
                'content'  => $content,
                'list'     => array_keys($this->lists[$service][$method]),
                'readOnly' => $readOnly,
                'multiple' => isset($option[2]['multiple']) ? true : false
            ]
        );
    }

    public function castCellContent($content, $option = null)
    {
        if (null == $content) {
            return null;
        }

        [$service, $method] = $option;

        if (!isset($this->lists[$service][$method])) {
            $this->initialiseList($service, $method, $option[2]['method'] ?? null);
        }

        if (isset($option[2]['multiple']) ? true : false) {
            foreach ($content as $key => $value) {
                if (!isset($this->lists[$service][$method][$value])) {
                    unset($content[$key]);
                }
            }
        } else {
            if (!isset($this->lists[$service][$method][$content])) {
                return null;
            }
        }

        return $content;
    }

    private function initialiseList(string $service, string $method, string $objectMethod = null)
    {
        $list = [];

        if (null === $objectMethod) {
            $list = $this->container->get($service)->$method();
        } else {
            $objects = $this->container->get($service)->$method();
            foreach ($objects as $object) {
                $list[] = $object->$objectMethod();
            }
        }

        $this->lists[$service][$method] = array_flip($list);
    }
}
