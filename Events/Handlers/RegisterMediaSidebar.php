<?php

namespace Modules\Media\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;

class RegisterMediaSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('media::media.title.media'), function (Item $item) {

                $item->authorize(
                    $this->auth->hasAccess('media.medias.index')
                );

                $item->item(trans('media::media.title.media'), function (Item $item) {
                    $item->weight(0);
                    $item->icon('fa fa-camera');
                    $item->route('admin.media.media.index');
                });

                $item->item(trans('media::galleries.title.galleries'), function (Item $item) {
                    $item->icon('fa fa-file-image-o');
                    $item->weight(1);
                    $item->append('admin.media.gallery.create');
                    $item->route('admin.media.gallery.index');
                });
            });
        });

        return $menu;
    }
}
