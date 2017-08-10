<aside class="main-sidebar">
    <section class="sidebar" style="height: auto;">
        <?= $this->render('columnLeftBefore'); ?>
        <ul class="sidebar-menu">
            <?php if (ISSET(Yii::$app->extensions['jarrus90/yii2-multilang'])) { ?>
                <li class="header"><?= Yii::t('core', 'Choose language'); ?></li>
                <?= \jarrus90\Multilang\Widgets\SelectWidget::widget(['layout' => '@jarrus90/Multilang/views/widgets/select/backend']); ?>
            <?php } ?>
            <li class="header"><?= Yii::t('core', 'Main navigation'); ?></li>
        </ul>
        <?= \jarrus90\Core\widgets\AdminMenu::widget(); ?>
        <?= $this->render('columnLeftAfter'); ?>
    </section>
</aside>