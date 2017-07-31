<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony\Controller;

use Arkschools\DataInputSheets\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DataInputSheetsController extends Controller
{
    /**
     * @Route("/data-input-sheets/selector", name="data_input_sheets_selector")
     *
     * @return Response
     */
    public function dataInputSheetSelectorAction()
    {
        $sheets = $this->get('arkschools.repository.data_input_sheets')->findAll();

        return $this->render('DataInputSheetsBundle::selector.html.twig', [
            'sheets' => $sheets
        ]);
    }

    /**
     * @Route("/data-input-sheets/show/{sheetId}/{viewId}", name="data_input_sheets_view")
     *
     * @param Request $request
     * @param string $sheetId
     * @param string $viewId
     * @return Response
     *
     */
    public function viewDataInputSheetsAction(Request $request, $sheetId, $viewId)
    {
        $repository = $this->get('arkschools.repository.data_input_sheets');

        /** @var View $view */
        $view = $repository->findViewBy($sheetId, $viewId);
        if (null === $view) {
            $this->redirectToRoute('data_input_sheets_selector');
        }

        $view->applySelection($request);

        if ($request->isMethod('post')) {
            $repository->save($view, $view->extractDataFromRequest($request));
            $this->addFlash('success', 'Data successfully saved');

            return $this->redirectToRoute('data_input_sheets_view', array_merge($view->getSelectorFilters(), ['sheetId' => $sheetId, 'viewId' => $viewId]));
        }

        return $this->render('DataInputSheetsBundle::view.html.twig', ['view' => $view]);
    }

    /**
     * @Route("/data-input-sheets/show/{sheetId}/{viewId}/edit/{position}", name="data_input_sheets_edit")
     *
     * @param Request $request
     * @param string  $sheetId
     * @param string  $viewId
     * @param int     $position
     *
     * @return Response
     */
    public function editDataInputSheetsAction(Request $request, $sheetId, $viewId, $position)
    {
        $repository = $this->get('arkschools.repository.data_input_sheets');
        /** @var View $view */
        $view       = $repository->findViewBy($sheetId, $viewId);

        if (null === $view) {
            return $this->redirectToRoute('data_input_sheets_selector');
        }

        $view->applySelection($request);

        if (0 > $position || $view->count() <= $position) {
            return $this->redirectToRoute('data_input_sheets_view', array_merge($view->getSelectorFilters(), ['sheetId' => $sheetId, 'viewId' => $viewId]));
        }

       if ($request->isMethod('post')) {
            $action = $request->request->get('submit');

            if ('Next' === $action || 'Previous' === $action || 'Back' === $action) {
                $repository->save($view, $view->extractDataFromRequest($request));
                $this->addFlash('success', 'Data successfully saved');

                if ('Next' === $action && $position < $view->count() - 1) {
                    return $this->redirectToRoute('data_input_sheets_edit', array_merge($view->getSelectorFilters(), ['sheetId' => $sheetId, 'viewId' => $viewId, 'position' => $position + 1]));
                } elseif ('Previous' === $action && $position > 0) {
                    return $this->redirectToRoute('data_input_sheets_edit', array_merge($view->getSelectorFilters(), ['sheetId' => $sheetId, 'viewId' => $viewId, 'position' => $position - 1]));
                } elseif ('Back' === $action) {
                    return $this->redirectToRoute('data_input_sheets_view', array_merge($view->getSelectorFilters(), ['sheetId' => $sheetId, 'viewId' => $viewId]));
                } else {
                    return $this->redirectToRoute('data_input_sheets_edit', array_merge($view->getSelectorFilters(), ['sheetId' => $sheetId, 'viewId' => $viewId, 'position' => $position]));
                }
            }
        }

        return $this->render('DataInputSheetsBundle::view.html.twig', ['view' => $view, 'position' => $position]);
    }
}
