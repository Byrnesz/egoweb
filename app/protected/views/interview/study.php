<h2><?php echo Study::getName($studyId); ?></h2>

<h3><a href="/interview/<?php echo $studyId; ?>#/page/0">Start new interview</a></h3>

<h3>Continue interview</h3>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'interviews',
	'dataProvider'=>$dataProvider,
	'pager'=>array(
		'header'=> '',
	),
	'summaryText'=>'',
	'columns'=>array(
		array(
			'name'=>'id',
			'header'=>'Interview',
			'type'=>'raw',
			'value'=>'CHtml::link(Interview::getEgoId($data->id), Yii::app()->createUrl("/interview/".$data->studyId."/".$data->id."#/page/".$data->completed))',
		),
	),
));
?>
