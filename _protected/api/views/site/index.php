<?php

/* @var $this yii\web\View */
/* @var $apiList Array */
$this->title = 'API';
?>
<table border="1">
    <thead>
        <td>End Point</td>
        <td>Link</td>
        <td>Method</td>
        <td>Description</td>
    </thead>
    <tbody>
    <?php foreach($apiList as $api):?>
        <tr>
            <td><?= $api['end_point']?></td>
            <td><?= $api['link']?></td>
            <td><?= $api['method']?></td>
            <td><?= $api['description']?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

