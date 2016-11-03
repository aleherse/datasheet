<?php

use Arkschools\DataInputSheet\Sheet;
use Arkschools\DataInputSheet\Column;
use Arkschools\DataInputSheet\DataInputSheetRepository;
use Arkschools\DataInputSheet\Spine;
use Arkschools\DataInputSheet\View;
use Symfony\Component\Yaml\Yaml;

class DataInputSheetRepositoryTest extends \PHPUnit\Framework\TestCase
{
    public function testFindAll()
    {
        $datasheets = $this->createDataInputSheetRepository()->findAll();

        $spine = new CarSpine();
        $expected = [
            new DataInputSheet('cars', $spine, [
                new View('Brand and model', $spine, [Column::integer()]),
                new View('Performance', $spine, [])
            ])
        ];
    }

    /**
     * @return DataInputSheetRepository
     */
    private function createDataInputSheetRepository()
    {
        $config = Yaml::parse(file_get_contents(__DIR__ . '/files/datasheet.yml'));

        $repository = new DataInputSheetRepository($config);

        $repository->addSpine(new CarSpine(), 'cars');

        return $repository;
    }
}

class CarSpine extends Spine
{
    public function __construct()
    {
        parent::__construct('cars', [
            'Lexus IS 200 1999 - 2005',
            'Audi 80 1.6 E 1992 - 1994',
            'Hyundai i20 1.25 i-Motion 2010 - 2012',
            'Renault Fluence Z.E. Expression 2011 - 2015',
            'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014'
        ]);
    }
}
