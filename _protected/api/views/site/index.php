<?php

/* @var $this yii\web\View */
/* @var $apiList Array */
$this->title = 'API';
?>
<table border="1">
    <thead>
        <td><strong>End Point</strong></td>
        <td><strong>Link</strong></td>
        <td><strong>Method</strong></td>
        <td><strong>Parameters</strong></td>
        <td><strong>Description</strong></td>
    </thead>
    <tbody>
    <?php foreach($apiList as $api):?>
        <tr>
            <td><?= $api['end_point']?></td>
            <td><?= $api['link']?></td>
            <td><?= $api['method']?></td>
            <td><?= $api['parameters']?></td>
            <td><?= $api['description']?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

