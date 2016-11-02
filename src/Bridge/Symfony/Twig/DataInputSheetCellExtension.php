<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\Twig;

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
                'datasheetCell',
                [$this, 'datasheetCell'],
                [
                    'is_safe'           => array('html'),
                    'needs_environment' => true,
                ]
            ),
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param View     $view
     * @param string            $spineId
     * @param string            $columnId
     *
     * @return string
     */
    public function datasheetCell(\Twig_Environment $twig, View $view, $spineId, $columnId)
    {
        //$column = $view->getColumn($columnId);

        return $twig->render('DataInputSheetBundle:extension:data_input_sheet_cell_string.html.twig', [
            'cell' => $view->getCell($spineId, $columnId)
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'datasheetCell';
    }
}
