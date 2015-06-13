<div class="<?php echo $pre; ?>-settings">
    <form method="post" action="options.php">
    <?php

    settings_fields($pre . 'settings_options');
    do_settings_sections($pre . 'settings_options');

    ?>
        <?php submit_button(); ?>
        <?php foreach($menu as $item): ?>
            <?php if(isset($hidden[$item[0] . $pre . $item[2]]) && $item[0] != ''): ?>
        <input type="text" hidden name="<?php echo $pre; ?>menu_orig_names[<?php echo $item['index'] ?>]" value="<?php echo strip_tags($item[0]); ?>"/>
            <?php endif; ?>
        <?php endforeach; ?>
        <input type="text" hidden name="<?php echo $pre; ?>active_tab" value="<?php echo $active_tab; ?>" />
        <div class="<?php echo $pre; ?>-tabs">
            <div class="<?php echo $pre; ?>-tabs-wrap">
                <div class="<?php echo $pre; ?>-tabs-wrapper">
                    <div data-target="#<?php echo $pre; ?>general" class="title selected">General</div>
                    <div data-target="#<?php echo $pre; ?>menu" class="title">Menu</div>
                    <div data-target="#<?php echo $pre; ?>pages" class="title">Pages</div>
                    <div data-target="#<?php echo $pre; ?>toggles" class="title">Toggles</div>
                    <div data-target="#<?php echo $pre; ?>media" class="title">Media</div>
                    <div data-target="#<?php echo $pre; ?>roles" class="title">Roles</div>
                    <div data-target="#<?php echo $pre; ?>addons" class="title">Add-Ons</div>
                </div>
            </div>
        </div>
        <div id="<?php echo $pre; ?>general" class="<?php echo $pre; ?>-content">
            <div class="<?php echo $pre; ?>-hint">
                <p>"Hand Off by EvansPress.com" is meant to make WordPress as a CMS easier to use for clients and end-users alike. The name "Hand Off" literally means the suggested interface that designers wants to hand off to the administrator a WP powered website. Hand Off by EvansPress.com does all of this without restricting the administrator in any sort of way. The administrator at anytime can select the "Advance" button to go back to a full and standard WP admin section. Get started by personalizing the WP CMS for your clients by placing a custom welcome message or you can add a logo to brand the login screen. Keep your client up to date by embedding your news feed or maybe even an industry related feed. Hand Off by EvansPress.com was created as a collaborative effort between Johnathan Evans as the user experience designer for the project and Lex Marion as core developer. This plugin is was created after years of requests for assistance on the WP admin backend by EP clients. We hope you enjoy it and it becomes a useful tool for you and your clients.</p>
                <a href="#">Dismiss</a>
                <hr />
            </div>
            <div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Welcome Message</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <p>Customize your Dashboard Welcome Message Panel Content.</p>
                    <hr />
                    <?php wp_editor($welcome_message, $pre . 'welcome_message', array('media_buttons' => false)); ?>
                </div>
            </div><div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Login Logo</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <p>Customize your Login Logo Image here. Upload a local image to the server.<br />Uploading a custom logo will direct Wordpress Logo links to your Wordpress Install.</p>
                    <hr />
                    <div class="<?php echo $pre; ?>-login-image-form">
                        <div>
                            <input id="<?php echo $pre; ?>-default-logo" type="radio" name="<?php echo $pre; ?>login_logo" value="<?php echo $default_logo; ?>" <?php if(! strcmp($default_logo, $logo)): ?>checked<?php endif; ?> />
                            <label for="<?php echo $pre; ?>-default-logo">Default Logo</label>
                        </div>
                        <br />
                        <div>
                            <input id="<?php echo $pre; ?>-custom-logo" type="radio" name="<?php echo $pre; ?>login_logo" value="<?php echo $custom_logo; ?>"  <?php if(! strcmp($custom_logo, $logo)): ?>checked<?php endif; ?> />
                            <label for="<?php echo $pre; ?>-custom-logo">Custom Logo</label>
                        </div>
                    </div>
                    <div class="<?php echo $pre; ?>-login-image-preview">
                        <input class="<?php echo $pre; ?>-custom-logo" type="hidden" name="<?php echo $pre; ?>custom_logo" value="<?php echo $custom_logo; ?>" />
                        <img src="<?php if(empty($custom_logo)) { echo $default_logo; } else { echo $custom_logo; } ?>" alt="Image Preview" />
                    </div>
                    <div class="<?php echo $pre; ?>-login-image-upload">
                        <div class="<?php echo $pre; ?>-login-image-error hide"></div>
                        <div class="<?php echo $pre; ?>-login-image-progress">
                            <div>Upload Custom Image</div>
                            <div class="hide">Uploading</div>
                        </div>
                        <input class="<?php echo $pre; ?>-login-image-file" type="file" name="files[]" data-url="<?php echo admin_url( 'admin-ajax.php' ); ?>" accept="image/*" />
                    </div>
                </div>
            </div><div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">RSS</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <p>Dashboard News Feed</p>
                    <hr />
                    <div class="<?php echo $pre; ?>-rss-form">
                        <div>
                            <label for="<?php echo $pre; ?>-rss-label">RSS Title</label>
                            <input id="<?php echo $pre; ?>-rss-label" type="text" name="<?php echo $pre; ?>rss_title" value="<?php echo $rss_title; ?>" />
                        </div>
                        <br />
                        <div>
                            <input id="<?php echo $pre; ?>-default-rss" type="radio" name="<?php echo $pre; ?>rss_url" value="<?php echo $default_rss; ?>" <?php if(! strcmp($default_rss, $rss)): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-default-rss">Default RSS Feed</label>
                        </div>
                        <br />
                        <div>
                            <input id="<?php echo $pre; ?>-custom-rss" type="radio" name="<?php echo $pre; ?>rss_url" value="<?php echo $rss; ?>" <?php if(strcmp($default_rss, $rss) !== 0): ?>checked<?php endif; ?>/>
                            <input type="text" value="<?php echo $rss; ?>" placeholder="Custom RSS Feed" />
                        </div>
                    </div>
                </div>
            </div><div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Support</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <p>Support Link</p>
                    <hr />
                    <div class="<?php echo $pre; ?>-rss-form">
                        <div>
                            <label for="<?php echo $pre; ?>-support-link">Link</label>
                            <input id="<?php echo $pre; ?>-support-link" type="text" name="<?php echo $pre; ?>support_link" value="<?php echo $support; ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="<?php echo $pre; ?>menu" class="<?php echo $pre; ?>-content hide">
            <div class="<?php echo $pre; ?>-hint">
                <p>Use this section to remove all of the clutter from plugins, WordPress and more. You can literally direct the administrator to go directly to the most commonly edited pages but again without any restriction to the administrator. All admin menu entries can as well be further customized by changing the actual link name. We have got you started by only showing the "Edit Pages" link which is customized from Pages and Dashboard.</p>
                <a href="#">Dismiss</a>
                <hr />
            </div>
            <div class="<?php echo $pre; ?>-menu-wrap">
                <div class="<?php echo $pre; ?>-menu-show">
                    <h2>Shown Menu Items</h2>
                    <p>Click on the item to rename it.</p>
                    <p>Unchecked Sub Menus will be hidden.</p>
                    <ul class="<?php echo $pre; ?>-menu-list" unselectable="on">
                        <?php foreach($menu as $index => $item): ?>
                            <?php if(! isset($hidden[$item[0] . $pre . $item[2]]) && $item[0] != ''): ?>
                                <li class="<?php echo $pre; ?>-menu-item" unselectable="on">
                                    <input class="hide" type="text" hidden name="<?php echo $pre; ?>menu_orig_names[<?php echo $item[2] ?>]" value="<?php echo strip_tags($orig[$item[2]]); ?>"/>
                                    <input class="hide" type="text" hidden name="<?php echo $pre; ?>menu_order[<?php echo $index; ?>]" value="<?php echo $item[2] ?>"/>
                                    <input type="checkbox" class="hide" name="<?php echo $pre; ?>menu_hidden[<?php echo $item[0] . $pre . $item[2]; ?>]" />
                                    <div class="<?php echo $pre; ?>-menu-name">
                                        <input type="text" class="hide" name="<?php echo $pre; ?>menu_rename[<?php echo $item[2]; ?>]" value="<?php echo strip_tags($item[0]); ?>" />
                                        <div class="<?php echo $pre; ?>-menu-html"><?php echo strip_tags($item[0]); ?></div>
                                    </div>
                                    <div>
                                        <ul class="<?php echo $pre; ?>-submenu-list hide" unselectable="on">
                                            <?php foreach($item['submenu'] as $sub): ?>
                                            <li class="<?php echo $pre; ?>-submenu-item <?php if($sub['parent']): ?><?php echo $pre; ?>-submenu-item-parent<?php endif; ?>" unselectable="on">
                                                <input class="hide" type="text" hidden name="<?php echo $pre; ?>submenu_orig_names[<?php echo $sub[2]; ?>]" value="<?php echo strip_tags($sub_orig[$sub[2]]); ?>"/>
                                                <input type="checkbox" name="<?php echo $pre; ?>submenu_hidden[<?php echo $item[2] . $pre . $sub[2]; ?>]" <?php if(! isset($sub_hidden[$item[2] . $pre . $sub[2]])): ?>checked="checked"<?php endif; ?> />
                                                <input type="text" class="hide" name="<?php echo $pre; ?>submenu_rename[<?php echo $sub[2]; ?>]" value="<?php echo strip_tags($sub[0]); ?>" />
                                                <div class="<?php echo $pre; ?>-submenu-html"><?php echo strip_tags($sub[0]); ?></div>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="<?php echo $pre; ?>-submenu-arrow <?php if(empty($item['submenu'])): ?>hide<?php endif; ?>"><span>&#x25BC;</span><span class="hide">&#x25B2;</span></div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="<?php echo $pre; ?>-menu-hidden">
                    <h4>Unused Menu Items:</h4>
                    <p>Drag items to the left column to display it to the menu.</p>
                    <ul class="<?php echo $pre; ?>-menu-list" unselectable="on">
                        <?php foreach($hidden as $hide => $val): ?>
                            <?php
                            list($name, $file) = explode($pre, $hide);
                            ?>
                            <li class="<?php echo $pre; ?>-menu-item" unselectable="on">
                                <input class="hide" type="text" name="<?php echo $pre; ?>menu_orig_names[<?php echo $file; ?>]" value="<?php echo strip_tags($orig[$file]); ?>"/>
                                <input class="hide" type="text" name="<?php echo $pre; ?>menu_order[]" value="<?php echo $file ?>"/>
                                <input type="checkbox" class="hide" name="<?php echo $pre; ?>menu_hidden[<?php echo $hide; ?>]" checked />
                                <div class="<?php echo $pre; ?>-menu-name">
                                    <input type="text" class="hide" name="<?php echo $pre; ?>menu_rename[<?php echo $file; ?>]" value="<?php echo strip_tags($name); ?>" />
                                    <div class="<?php echo $pre; ?>-menu-html"><?php echo strip_tags($name); ?></div>
                                </div>
                                <div>
                                    <?php foreach($menu as $item): ?>
                                    <?php if($item[0] != '' && ! strcmp($item[2], $file)): ?>
                                    <ul class="<?php echo $pre; ?>-submenu-list hide" unselectable="on">
                                        <?php foreach($item['submenu'] as $sub): ?>
                                        <li class="<?php echo $pre; ?>-submenu-item <?php if($sub['parent']): ?><?php echo $pre; ?>-submenu-item-parent<?php endif; ?>" unselectable="on">
                                            <input class="hide" type="text" hidden name="<?php echo $pre; ?>submenu_orig_names[<?php echo $sub[2]; ?>]" value="<?php echo strip_tags($sub[0]) ?>"/>
                                            <input type="checkbox" name="<?php echo $pre; ?>submenu_hidden[<?php echo $item[2] . $pre . $sub[2]; ?>]" <?php if(! isset($sub_hidden[$item[2] . $pre . $sub[2]])): ?>checked="checked"<?php endif; ?> />
                                            <input type="text" class="hide" name="<?php echo $pre; ?>submenu_rename[<?php echo $sub[2]; ?>]" value="<?php echo strip_tags($sub[0]); ?>" />
                                            <div class="<?php echo $pre; ?>-submenu-html"><?php echo strip_tags($sub[0]); ?></div>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <div class="<?php echo $pre; ?>-submenu-arrow <?php if(empty($item['submenu'])): ?>hide<?php endif; ?>"><span>&#x25BC;</span><span class="hide">&#x25B2;</span></div>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div id="<?php echo $pre; ?>pages" class="<?php echo $pre; ?>-content hide">
            <div class="<?php echo $pre; ?>-hint">
                <p>The pages section places direct links to all pages in the below the main admin navigation links in the pull down menu. This will allow you to place direct links to a page that requires regular editing such as a pricing list for instance. You can further customize the links by renaming them so you could place "Edit Pricing List" instead of "Pricing List" which will make the link seem more logical to non-technical administrators.</p>
                <a href="#">Dismiss</a>
                <hr />
            </div>
            <div class="<?php echo $pre; ?>-menu-wrap">
                <div class="<?php echo $pre; ?>-menu-show">
                    <div>
                        <label for="<?php echo $pre; ?>-hand-off-page-header">Header:</label>
                        <input id="<?php echo $pre; ?>-hand-off-page-header" type="text" name="<?php echo $pre; ?>hand_off[page_header]" value="<?php echo $hand_off['page_header']; ?>" />
                    </div>
                    <h2>Admin Bar Page Shortcut</h2>
                    <p>Click on the item to rename it.</p>
                    <ul class="<?php echo $pre; ?>-menu-list" unselectable="on">
                        <?php foreach($pages_show as $id => $val): ?>
                            <?php
                            $page = null;
                            foreach($pages as $item) {
                                if($item -> ID == $id) {
                                    $page = $item;
                                    break;
                                }
                            }
                            ?>
                            <li class="<?php echo $pre; ?>-menu-item" unselectable="on">
                                <input class="hide" type="text" name="<?php echo $pre; ?>pages_orig_names[<?php echo $id; ?>]" value="<?php echo $pages_orig[$id]; ?>"/>
                                <input class="hide" type="text" name="<?php echo $pre; ?>pages_order[]" value="<?php echo $id ?>"/>
                                <input type="checkbox" class="hide" name="<?php echo $pre; ?>pages_show[<?php echo $hide; ?>]" checked />
                                <div class="<?php echo $pre; ?>-menu-name">
                                    <input type="text" class="hide" name="<?php echo $pre; ?>pages_rename[<?php echo $id; ?>]" value="<?php echo $page -> post_title; ?>" />
                                    <div class="<?php echo $pre; ?>-menu-html"><?php echo $page -> post_title; ?></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="<?php echo $pre; ?>-menu-hidden">
                    <h4>Pages:</h4>
                    <p>Drag items to the left column to display it to the admin bar.</p>
                    <ul class="<?php echo $pre; ?>-menu-list" unselectable="on">
                        <?php foreach($pages as $index => $item): ?>
                            <?php if(! isset($pages_show[$item -> ID])): ?>
                                <li class="<?php echo $pre; ?>-menu-item" unselectable="on">
                                    <input class="hide" type="text" hidden name="<?php echo $pre; ?>pages_orig_names[<?php echo $item -> ID ?>]" value="<?php echo $pages_orig[$item -> ID]; ?>"/>
                                    <input class="hide" type="text" hidden name="<?php echo $pre; ?>pages_order[<?php echo $index; ?>]" value="<?php echo $item -> ID ?>"/>
                                    <input type="checkbox" class="hide" name="<?php echo $pre; ?>pages_show[<?php echo $item -> ID; ?>]" />
                                    <div class="<?php echo $pre; ?>-menu-name">
                                        <input type="text" class="hide" name="<?php echo $pre; ?>pages_rename[<?php echo $item -> ID; ?>]" value="<?php echo $item -> post_title; ?>" />
                                        <div class="<?php echo $pre; ?>-menu-html"><?php echo $item -> post_title; ?></div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div id="<?php echo $pre; ?>toggles" class="<?php echo $pre; ?>-content hide">
            <div class="<?php echo $pre; ?>-hint">
                <p>Toggles will allow you to granular control of the WP admin section. The plugin has automatically started you with suggested toggles but  you can change anything to your heart's desire. Toggles for instance will severely clean up the pages/posts editor of WordPress only showing the administrator what is needed for editing text and photos.</p>
                <a href="#">Dismiss</a>
                <hr />
            </div>
            <div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Admin UI</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <p>Uncheck to disable Admin Page elements</p>
                    <hr />
                    <div>
                        <div>
                            <input id="<?php echo $pre; ?>-admin-adminbar" type="checkbox" name="<?php echo $pre; ?>admin_hidden[admin_bar]" <?php if(! isset($admin_hidden['admin_bar'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-admin-adminbar">Default Admin Bar</label>
                        </div>
                        <div>
                            <input id="<?php echo $pre; ?>-admin-logo" type="checkbox" name="<?php echo $pre; ?>admin_hidden[logo]" <?php if(! isset($admin_hidden['logo'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-admin-logo">Admin Bar Logo</label>
                        </div>
                        <div>
                            <input id="<?php echo $pre; ?>-admin-logout" type="checkbox" name="<?php echo $pre; ?>admin_hidden[logout]" <?php if(! isset($admin_hidden['logout'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-admin-logout">Log Out</label>
                        </div>
                        <div>
                            <input id="<?php echo $pre; ?>-admin-post_header" type="checkbox" name="<?php echo $pre; ?>admin_hidden[post_header]" <?php if(! isset($admin_hidden['post_header'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-admin-post_header">Post/Page Header</label>
                        </div>
                        <div>
                            <input id="<?php echo $pre; ?>-admin-dismiss" type="checkbox" name="<?php echo $pre; ?>admin_hidden[dismiss]" <?php if(! isset($admin_hidden['dismiss'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-admin-sismiss">Welcome Message Panel Dismiss Button</label>
                        </div>
                        <div>
                            <input id="<?php echo $pre; ?>-admin-rss" type="checkbox" name="<?php echo $pre; ?>admin_hidden[rss]" <?php if(! isset($admin_hidden['rss'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-admin-rss">RSS Feed</label>
                        </div>
                        <div>
                            <input id="<?php echo $pre; ?>-admin-settings" type="checkbox" name="<?php echo $pre; ?>admin_hidden[settings]" <?php if(! isset($admin_hidden['settings'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-admin-settings">Hand Off Settings</label>
                        </div>
                        <div>
                            <input id="<?php echo $pre; ?>-admin-footer" type="checkbox" name="<?php echo $pre; ?>admin_hidden[footer]" <?php if(! isset($admin_hidden['footer'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-admin-footer">Footer</label>
                        </div>
                    </div>
                </div>
            </div><div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Editor</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <p>Uncheck items to remove from Page/Page Editor.</p>
                    <hr />
                    <div>
                        <div>
                            <input id="<?php echo $pre; ?>-editor-add-new" type="checkbox" name="<?php echo $pre; ?>editor_hidden[addnew]" <?php if(! isset($editor['addnew'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-editor-add-new">Add New (Pages only)</label>
                        </div>
                        <div>
                            <input id="<?php echo $pre; ?>-editor-rich-editor" type="checkbox" name="<?php echo $pre; ?>editor_hidden[richeditor]" <?php if(! isset($editor['richeditor'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-editor-rich-editor">HTML Editor</label>
                        </div>
                        <div>
                            <input id="<?php echo $pre; ?>-editor-slug-bar" type="checkbox" name="<?php echo $pre; ?>editor_hidden[slugbar]" <?php if(! isset($editor['slugbar'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-editor-slug-bar">Slug Bar</label>
                        </div>
                    </div>
                </div>
            </div><div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Columns</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <p>Uncheck to hide field column.</p>
                    <hr />
                    <div>
                        <?php foreach($default_columns as $column => $label): ?>
                            <div>
                                <input id="<?php echo $pre; ?>-manage-column-<?php echo $column; ?>" type="checkbox" name="<?php echo $pre; ?>manage_columns_hidden[<?php echo $column; ?>]" <?php if(! isset($columns[$column])): ?>checked<?php endif; ?>/>
                                <label for="<?php echo $pre; ?>-manage-column-<?php echo $column; ?>"><?php echo $label; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div><div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Hand Off</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <p>Uncheck items to remove from Hand Off menu.</p>
                    <hr />
                    <div>
                        <div>
                            <input id="<?php echo $pre; ?>-hand-off-pages" type="checkbox" name="<?php echo $pre; ?>hand_off[hide_pages]" <?php if(! isset($hand_off['hide_pages'])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-hand-off-pages">Pages Menu</label>
                        </div>
                    </div>
                </div>
            </div><div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Meta Boxes</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <p>Uncheck to disable meta box.</p>
                    <hr />
                    <div>
                        <?php foreach($default_meta as $meta => $label): ?>
                        <div>
                            <input id="<?php echo $pre; ?>-meta-box-<?php echo $meta; ?>" type="checkbox" name="<?php echo $pre; ?>meta_hidden[<?php echo $meta; ?>]" <?php if(! isset($meta_hidden[$meta])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-meta-box-<?php echo $meta; ?>"><?php echo $label; ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div><div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Row Actions</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <p>Uncheck items to remove from table row actions.</p>
                    <hr />
                    <div>
                        <?php foreach($default_actions as $action => $label): ?>
                        <div>
                            <input id="<?php echo $pre; ?>-row-action-<?php echo $action; ?>" type="checkbox" name="<?php echo $pre; ?>row_action_hidden[<?php echo $action; ?>]" <?php if(! isset($actions[$action])): ?>checked<?php endif; ?>/>
                            <label for="<?php echo $pre; ?>-row-action-<?php echo $action; ?>"><?php echo $label; ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="<?php echo $pre; ?>media" class="<?php echo $pre; ?>-content hide">
            <div class="<?php echo $pre; ?>-hint">
                <p>Media allows you to preset the media/photo upload box that is embedded inside the pages/posts editor. This will help curb issues like embedding a super large photo when all is ever needed is a medium sized image.</p>
                <a href="#">Dismiss</a>
                <hr />
            </div>
            <div>
                <h2>Media Gallery Window</h2>
                <p>Customze the media gallery settings.</p>
            </div>
            <div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Display Settings</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <div>
                        <label for="<?php echo $pre; ?>-media-align">Alignment:</label>
                        <select id="<?php echo $pre; ?>-media-align" name="<?php echo $pre; ?>image_default_align">
                            <option value="left" <?php if(! strcmp($alignment, "left")): ?>selected<?php endif; ?>>Left</option>
                            <option value="center" <?php if(! strcmp($alignment, "center")): ?>selected<?php endif; ?>>Center</option>
                            <option value="right" <?php if(! strcmp($alignment, "right")): ?>selected<?php endif; ?>>Right</option>
                            <option value="none" <?php if(! strcmp($alignment, "none")): ?>selected<?php endif; ?>>None</option>
                        </select>
                    </div>
                    <div>
                        <label for="<?php echo $pre; ?>-media-link">Link Type:</label>
                        <select id="<?php echo $pre; ?>-media-link" name="<?php echo $pre; ?>image_default_link_type">
                            <option value="file" <?php if(! strcmp($link, "file")): ?>selected<?php endif; ?>>Media File</option>
                            <option value="post" <?php if(! strcmp($link, "post")): ?>selected<?php endif; ?>>Attachment Page</option>
                            <option value="custom" <?php if(! strcmp($link, "custom")): ?>selected<?php endif; ?>>Custom URL</option>
                            <option value="none" <?php if(! strcmp($link, "none")): ?>selected<?php endif; ?>>None</option>
                        </select>
                    </div>
                    <div>
                        <label for="<?php echo $pre; ?>-media-size">Size:</label>
                        <select id="<?php echo $pre; ?>-media-size" name="<?php echo $pre; ?>image_default_size">
                            <option value="thumbnail" <?php if(! strcmp($size, "thumbnail")): ?>selected<?php endif; ?>>Thumbnail – 150 × 150 </option>
                            <option value="medium" <?php if(! strcmp($size, "medium")): ?>selected<?php endif; ?>>Medium – 300 × 169</option>
                            <option value="large" <?php if(! strcmp($size, "large")): ?>selected<?php endif; ?>>Large – 660 × 371</option>
                            <option value="full" <?php if(! strcmp($size, "full")): ?>selected<?php endif; ?>>Full Size – 1440 × 810</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="<?php echo $pre; ?>-block">
                <div class="<?php echo $pre; ?>-block-title">Media Items</div>
                <div class="<?php echo $pre; ?>-block-content">
                    <div>
                        <input id="<?php echo $pre; ?>-editor-media-button" type="checkbox" name="<?php echo $pre; ?>editor_hidden[gallery]" <?php if(! isset($editor['gallery'])): ?>checked<?php endif; ?>/>
                        <label for="<?php echo $pre; ?>-editor-media-button">Create Gallery</label>
                    </div>
                    <div>
                        <input id="<?php echo $pre; ?>-editor-media-featured-image" type="checkbox" name="<?php echo $pre; ?>editor_hidden[featured_image]" <?php if(! isset($editor['featured_image'])): ?>checked<?php endif; ?>/>
                        <label for="<?php echo $pre; ?>-editor-media-featured-image">Set Featured Image</label>
                    </div>
                    <div>
                        <input id="<?php echo $pre; ?>-editor-media-name" type="checkbox" name="<?php echo $pre; ?>editor_hidden[media_name]" <?php if(! isset($editor['media_name'])): ?>checked<?php endif; ?>/>
                        <label for="<?php echo $pre; ?>-editor-media-name">Rename Add Media to Add Photo</label>
                    </div>
                </div>
            </div>
        </div>
        <div id="<?php echo $pre; ?>roles" class="<?php echo $pre; ?>-content hide">
            <div class="<?php echo $pre; ?>-hint">
                <p>Roles will allow you to limit access to the Advance functions of Hand Off. This feature can actually help an administrator create a tailored CMS experience for users below the administrator role on a website.</p>
                <a href="#">Dismiss</a>
                <hr />
            </div>
            <div>
                <h2>Roles</h2>
                <p>Select which user role has access to Hand Off's Mode button.</p>
            </div>
            <div>
                <label for="<?php echo $pre; ?>-admin-mode">Available Roles</label>
                <select id="<?php echo $pre; ?>-admin-mode" name="<?php echo $pre; ?>admin_hidden[role]">
                <?php foreach($roles as $role => $opt): ?>
                    <option value="<?php echo $role; ?>" <?php if(! strcmp($admin_hidden['role'], $role)): ?>selected<?php endif; ?>><?php echo $opt['name']; ?></option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div id="<?php echo $pre; ?>addons" class="<?php echo $pre; ?>-content hide">
            <div>
                <p>Commercial plugin support, themes and more coming soon...</p>
            </div>
        </div>
        <?php submit_button(); ?>
    </form>
</div>