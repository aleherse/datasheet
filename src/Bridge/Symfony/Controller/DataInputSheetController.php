<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DataInputSheetController extends Controller
{
    /**
     * @Route("/data-input-sheet/selector", name="data_input_sheet_selector")
     *
     * @return Response
     */
    public function dataInputSheetSelectorAction()
    {
        $sheets = $this->get('arkschools.repository.data_input_sheet')->findAll();

        return $this->render('DataInputSheetBundle::selector.html.twig', [
            'sheets' => $sheets
        ]);
    }

    /**
     * @Route("/data-input-sheet/show/{sheetId}/{viewId}", name="data_input_sheet_view")
     *
     * @param Request $request
     * @param string $sheetId
     * @param string $viewId
     * @return Response
     *
     */
    public function viewDataInputSheetAction(Request $request, $sheetId, $viewId)
    {
        $repository = $this->get('arkschools.repository.data_input_sheet');

        $view = $repository->findViewBy($sheetId, $viewId);
        if (null === $view) {
            $this->redirectToRoute('data_input_sheet_selector');
        }

        if ($request->isMethod('post')) {
            $repository->save($view, $request->request->all());
            $this->addFlash('success', 'Data successfully saved');

            return $this->redirectToRoute('data_input_sheet_view', ['sheetId' => $sheetId, 'viewId' => $viewId]);
        }

        return $this->render('DataInputSheetBundle::view.html.twig', ['view' => $view]);
    }
}
