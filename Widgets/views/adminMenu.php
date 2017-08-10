<?php
foreach($items AS $key => $item) {
    if(!empty($item['items'])) {
        $items[$key]['options'] = [
            'class' => 'treeview'
        ];
        $items[$key]['dropDownOptions'] = [
            'class' => 'treeview-menu',
            'id' => "{$key}__dropdown"
        ];
    }
}
echo \yii\widgets\Menu::widget([
    'activateParents' => true,
    'labelTemplate' => '<a href="#">{label}<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span></a>',
    'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
    'options' => [
        'class' => 'sidebar-menu tree',
        'data' => [
            'widget' => 'tree'
        ]
    ],
    'items' => $items
]);