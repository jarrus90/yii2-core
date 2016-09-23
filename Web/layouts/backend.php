<?php

use yii\helpers\Html;
use kartik\alert\AlertBlock;
use kartik\growl\Growl;

/* @var $this \yii\web\View */
/* @var $content string */

\dmstr\web\AdminLteAsset::register($this);
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@bower/admin-lte/dist');
$mainClass = str_replace('/', '-', Yii::$app->controller->route);
$bodyClass = isset($this->params['bodyClass']) ? ' ' . $this->params['bodyClass'] : '';
$direction = Yii::$app->getModule('multilang')->getIsRtl() ? ' dir="rtl"' : '';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="sidebar-mini skin-blue <?= $mainClass ?><?= $bodyClass ?>"<?= $direction; ?>>
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <?=
            AlertBlock::widget([
                'type' => AlertBlock::TYPE_GROWL,
                'useSessionFlash' => true,
                'delay' => 500,
                'alertSettings' => [
                    'success' => [
                        'type' => Growl::TYPE_SUCCESS,
                        'pluginOptions' => [
                            'placement' => [
                                'from' => 'top',
                                'align' => 'center',
                            ]
                        ]
                    ],
                    'danger' => [
                        'type' => Growl::TYPE_DANGER,
                        'pluginOptions' => [
                            'placement' => [
                                'from' => 'top',
                                'align' => 'center',
                            ]
                        ]
                    ],
                    'warning' => [
                        'type' => Growl::TYPE_WARNING,
                        'pluginOptions' => [
                            'placement' => [
                                'from' => 'top',
                                'align' => 'center',
                            ]
                        ]
                    ],
                    'info' => [
                        'type' => Growl::TYPE_INFO,
                        'pluginOptions' => [
                            'placement' => [
                                'from' => 'top',
                                'align' => 'center',
                            ]
                        ]
                    ],
                ]
            ]);
            ?>
            <?= $this->render('backend/header.php', ['directoryAsset' => $directoryAsset]) ?>
            <div class="wrapper row-offcanvas row-offcanvas-left">
                <?= $this->render('backend/columnLeft.php') ?>
                <?= $this->render('backend/content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]) ?>
            </div>
            <footer class="main-footer">
                <?= Yii::$app->name; ?> &copy; <?= date('Y'); ?>
            </footer>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
