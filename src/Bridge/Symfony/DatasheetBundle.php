<?php

namespace Aleherse\Datasheet\Bridge\Symfony;

use Aleherse\Datasheet\Bridge\Symfony\DependencyInjection\Compiler\ImportDatasheetStubPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * DatasheetBundle
 *
 * @package    aleherse/datasheet
 * @subpackage bridge
 * @author     Alejandro Hernandez
 * @copyright  2016-2017 Alejandro Hernandez
 * @license    http://www.opensource.org/licenses/MIT The MIT License
 */
class DatasheetBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ImportDatasheetStubPass());
    }
}
