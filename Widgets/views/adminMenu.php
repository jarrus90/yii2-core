<?php
foreach ($items AS $mainItem) {
    $isActive = $mainItem['active'];
    $isList = ISSET($mainItem['childs']);
    ?>
    <li class="<?= $isList ? 'treeview' : ''; ?><?= $isActive ? ' active' : '' ?>">
        <a href="<?= $isList ? '#' : (ISSET($mainItem['url']) ? $mainItem['url'] : '#' ); ?>">
            <?= $mainItem['icon']; ?><span><?= $mainItem['label'] ?></span>
            <?php if ($isList) { ?>
                <i class="fa fa-angle-left pull-right"></i>
        <?php } ?>
        </a>
            <?php if ($isList) { ?>
            <ul class="treeview-menu<?= $isActive ? ' menu-open' : '' ?>">
                <?php
                foreach ($mainItem['childs'] AS $child) {
                    $activeClass = '';
                    if (!empty($child['active']) && $child['active']) {
                        $activeClass = 'active ';
                    }
                    ?>
                    <li class="<?= $activeClass; ?>">
                        <a href="<?= $child['url'] ?>">
                            <span class="fa fa-list"></span> <?= $child['title'] ?>
                        </a>
                    </li>
            <?php } ?>
            </ul>
    <?php } ?>
    </li>
<?php } ?>