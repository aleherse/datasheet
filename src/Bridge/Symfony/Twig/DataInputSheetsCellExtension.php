<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony\Twig;

use Arkschools\DataInputSheets\View;

class DataInputSheetsCellExtension extends \Twig_Extension
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

        return $column->render($twig, $columnId, $spineId, $view->getContent($spineId, $columnId));

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dataInputSheetCell';
    }
}
