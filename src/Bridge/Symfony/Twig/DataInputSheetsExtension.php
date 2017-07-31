<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony\Twig;

use Arkschools\DataInputSheets\View;

class DataInputSheetsExtension extends \Twig_Extension
{
    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'dataInputSheetCell',
                [$this, 'dataInputSheetCell'],
                [
                    'is_safe'           => ['html'],
                    'needs_environment' => true,
                ]
            ),
            new \Twig_SimpleFunction(
                'dataInputSheetSelector',
                [$this, 'dataInputSheetSelector'],
                [
                    'is_safe'           => ['html'],
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    public function dataInputSheetCell(\Twig_Environment $twig, View $view, string $columnId, string $spineId): string
    {
        $column = $view->getColumn($columnId);

        if (null === $column) {
            return '';
        }

        return $column->render($twig, $columnId, $spineId, $view->getContent($spineId, $columnId));
    }

    public function dataInputSheetSelector(\Twig_Environment $twig, View $view)
    {
        return $view->renderSelector($twig);
    }

    public function getName(): string
    {
        return 'dataInputSheet';
    }
}
