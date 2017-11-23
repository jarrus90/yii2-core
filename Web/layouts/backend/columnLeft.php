<aside class="main-sidebar">
    <section class="sidebar" style="height: auto;">
        <?= $this->render('columnLeftBefore'); ?>
        <?= \jarrus90\Core\widgets\AdminMenu::widget(); ?>
        <?= $this->render('columnLeftAfter'); ?>
    </section>
</aside>