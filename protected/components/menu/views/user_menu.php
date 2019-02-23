<div class="account-nav">
    <?php $menu->resetLoop(); while($menu->loop()): ?>
        <div class="menu-title">
            <?php echo $menu->currentItem("name") ?>
        </div>
        <?php $subMenu = $menu->currentItemGetMenu(); ?>
        <?php if($subMenu): ?>
            <ul class="menu-list">
                <?php $subMenu->resetLoop(); while($subMenu->loop()): ?>
                    <li>
                        <a href="<?php echo $subMenu->currentItem("url") ?>" <?php if($subMenu->currentItemActive(true)): ?>class="active"<?php endif; ?>><?php echo $subMenu->currentItem("name") ?></a>
                        <?php $subMenu2 = $subMenu->currentItemGetMenu(); ?>
                        <?php if($subMenu2): ?>
                            <ul class="inner-menu-list">
                                <?php $subMenu2->resetLoop(); while($subMenu2->loop()): ?>
                                    <li <?php if($subMenu2->currentItemActive()): ?>class="bold"<?php endif; ?>>
                                        <a href="<?php echo $subMenu2->currentItem("url") ?>"><?php echo $subMenu2->currentItem("name") ?></a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    <?php endwhile; ?>
</div>