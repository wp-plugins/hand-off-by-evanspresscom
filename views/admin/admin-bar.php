<div id="<?php echo $pre; ?>-admin-bar" data-admin-url="<?php echo admin_url(); ?>">
    <div class="<?php echo $pre; ?>-admin-bar-menu hide"><div><ul class="<?php echo $pre; ?>-admin-bar-menus">
            <?php foreach($menu as $item): ?><?php if($item[0] != '' && ! $item['hidden']): ?><li class="<?php echo $pre; ?>-admin-bar-menu-item <?php if($item['active']): ?>active<?php endif; ?>">
                <a href="<?php echo $item['link']; ?>"><?php echo $item[0]; ?></a>
                <ul class="<?php echo $pre; ?>-admin-bar-submenu">
                    <?php foreach($item['submenu'] as $sub): ?>
                        <?php if(! $sub['hidden']): ?>
                            <li class="<?php echo $pre; ?>-admin-bar-submenu-item">
                                <a class="<?php if($sub['active']): ?>active<?php endif; ?>" href="<?php echo $sub['link']; ?>"><?php echo $sub[0]; ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                </li><?php endif; ?><?php endforeach; ?></ul>
        <?php if(! isset($hand_off['hide_pages']) && ! empty($pages_show)): ?>
        <hr />
        <ul class="<?php echo $pre; ?>-admin-bar-pages">
            <?php if(! empty($hand_off['page_header'])):?><li class="<?php echo $pre; ?>-admin-bar-menu-item <?php echo $pre; ?>-admin-bar-page-header"><?php echo $hand_off['page_header']; ?>:</li><?php endif; ?><?php foreach($pages as $page): ?><?php if(isset($pages_show[$page -> ID])): ?><li class="<?php echo $pre; ?>-admin-bar-menu-item">
                <a href="<?php echo $page -> link; ?>"><?php echo $page -> post_title; ?></a>
                </li><?php endif; ?><?php endforeach; ?>
        </ul><?php endif; ?></div>
        <div class="<?php echo $pre; ?>-admin-bar-action">
            <?php if(! empty($post)): ?><a href="<?php echo $link; ?>"><?php echo $label; ?></a><?php else: ?><a href="<?php echo home_url();?>">Home Page</a><?php endif; ?>
            <a class="<?php echo $pre; ?>-admin-bar-advance" href="<?php echo $advance; ?>">Advance</a>
            <?php if(! empty($support)): ?><a href="<?php echo $support; ?>" target="_blank" onclick="window.open('<?php echo $support; ?>', 'Support', 'menubar=0, status=0'); return false;">Support</a><?php endif; ?>
            <a class="<?php echo $pre; ?>-admin-bar-settings" href="<?php echo admin_url("options-general.php?page=hand-off"); ?>">Hand Off</a>
            <a class="<?php echo $pre; ?>-admin-bar-logout" href="<?php echo $logout; ?>">Log Out</a>
        </div>
    </div>
    <div class="<?php echo $pre; ?>-admin-bar-show"><span>&#x25BC;</span><span class="hide">&#x25B2;</span></div>
</div>