<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony;

use Arkschools\DataInputSheet\Bridge\Symfony\DependencyInjection\Compiler\ImportDataInputSheetSpinePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * DataInputSheetBundle
 *
 * @package    Arkschools/datasheet
 * @subpackage bridge
 * @author     Alejandro Hernandez
 * @copyright  2016-2017 Alejandro Hernandez
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class DataInputSheetBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ImportDataInputSheetSpinePass());
    }
}
