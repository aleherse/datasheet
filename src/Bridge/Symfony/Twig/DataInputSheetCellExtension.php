<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\Twig;

use Arkschools\DataInputSheet\Column;
use Arkschools\DataInputSheet\View;

class DataInputSheetCellExtension extends \Twig_Extension
{
    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'dataInputSheetCell',
                [$this, 'dataInputSheetCell'],
                [
                    'is_safe'           => array('html'),
                    'needs_environment' => true,
                ]
            ),
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param View $view
     * @param string $spineId
     * @param string $columnId
     *
     * @return string
     */
    public function dataInputSheetCell(\Twig_Environment $twig, View $view, $columnId, $spineId)
    {
        $column = $view->getColumn($columnId);
        if (null === $column) {
            return '';
        }

        return $twig->render($column->getTemplate(), [
            'cell'   => $view->getCell($columnId, $spineId),
            'column' => $column
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dataInputSheetCell';
    }
}
