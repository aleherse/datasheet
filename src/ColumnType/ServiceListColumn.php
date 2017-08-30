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
            $this->initialiseList($service, $method, $option);
        }

        if (is_array($content)) {
            $content = array_flip($content);
        }

        return $twig->render(
            $this->template,
            [
                'columnId'      => $columnId,
                'spineId'       => $spineId,
                'content'       => $content,
                'list'          => $this->lists[$service][$method],
                'readOnly'      => $readOnly,
                'multiple'      => isset($option[2]['multiple']) && $option[2]['multiple'],
                'useKeyAsValue' => isset($option[2]['useKeyAsValue']) && $option[2]['useKeyAsValue']
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
            $this->initialiseList($service, $method, $option);
        }

        if (isset($option[2]['multiple']) && $option[2]['multiple']) {
            foreach ($content as $key) {
                if (!isset($this->lists[$service][$method][$key])) {
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

    private function initialiseList(string $service, string $method, array $options = null)
    {
        $list          = [];
        $objectMethod  = $options[2]['method'] ?? null;
        $keyMethod     = $options[2]['key'] ?? null;
        $useKeyAsValue = $options[2]['useKeyAsValue'] ?? false;

        if (null === $objectMethod) {
            $list = $this->container->get($service)->$method();
            if (!$useKeyAsValue) {
                $list = array_combine($list, $list);
            }
        } else {
            $objects = $this->container->get($service)->$method();
            if ($useKeyAsValue && null !== $keyMethod) {
                foreach ($objects as $object) {
                    $list[$object->$keyMethod()] = $object->$objectMethod();
                }
            } else {
                foreach ($objects as $object) {
                    $value = $object->$objectMethod();
                    $list[$value] = $value;
                }
            }
        }

        $this->lists[$service][$method] = $list;
    }
}
