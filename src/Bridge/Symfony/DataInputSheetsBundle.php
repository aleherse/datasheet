<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony;

use Arkschools\DataInputSheets\Bridge\Symfony\DependencyInjection\Compiler\ImportDataInputSheetsColumnTypePass;
use Arkschools\DataInputSheets\Bridge\Symfony\DependencyInjection\Compiler\ImportDataInputSheetsSpinePass;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * DataInputSheetsBundle
 *
 * @package    Arkschools/datasheet
 * @subpackage bridge
 * @author     Alejandro Hernandez
 * @copyright  2016-2017 Alejandro Hernandez
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class DataInputSheetsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ImportDataInputSheetsSpinePass());
        $container->addCompilerPass(new ImportDataInputSheetsColumnTypePass());

        $mappings = array(
            realpath(__DIR__.'/Resources/config/doctrine-mapping') => 'Arkschools\DataInputSheets\Bridge\Symfony\Entity',
        );

        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createYamlMappingDriver($mappings, ['data_input_sheets.entity_manager_name']));
        }
    }
}
