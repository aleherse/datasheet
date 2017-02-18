<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DataInputSheetsController extends Controller
{
    /**
     * @Route("/data-input-sheet/selector", name="data_input_sheets_selector")
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
     * @Route("/data-input-sheet/show/{sheetId}/{viewId}", name="data_input_sheets_view")
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

        $view = $repository->findViewBy($sheetId, $viewId);
        if (null === $view) {
            $this->redirectToRoute('data_input_sheets_selector');
        }

        if ($request->isMethod('post')) {
            $repository->save($view, $request->request->all());
            $this->addFlash('success', 'Data successfully saved');

            return $this->redirectToRoute('data_input_sheets_view', ['sheetId' => $sheetId, 'viewId' => $viewId]);
        }

        return $this->render('DataInputSheetsBundle::view.html.twig', ['view' => $view]);
    }
}
