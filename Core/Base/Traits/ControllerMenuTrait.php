<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Base\Traits;

use Abc\Orm\DataLayerTrait;

trait ControllerMenuTrait
{
    use DataLayerTrait;
    use BaseReflectionTrait;
    use ControllerTrait;

//    private function getMenus(): array
//    {
////        //Log::evo_log('Getting menus');
//        return (new MenuModel)->getRepository()->findBy(['id', 'name', 'section_id', 'position', 'link', 'icon'],['state_id' => ACTIVE_STATE_ID],[],['orderby' => 'position ASC']);
//    }
//
//    private function getSections(): array
//    {
////        //Log::evo_log('Getting sections');
//        return (new SectionModel)->getRepository()->findBy(['id', 'name', 'position'],['state_id' => ACTIVE_STATE_ID],[],['orderby' => 'position ASC']);
//    }
}