<h1>Expressions</h1>

<div style="width:350px; float:left;">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_expression',
)); ?>
</div>

<div style="float:right; width:400px">
<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'new-simple-expression',
	'enableAjaxValidation'=>true,
	'method'=>'GET',
));

echo CHtml::ajaxButton ("New simple expression",
	CController::createUrl('ajaxload'),
	array( 'update' => '#Expression', 'data'=>'js:$("#new-simple-expression").serialize()', 'method'=>'get'),
	array( 'id'=>uniqid(), 'live'=>false)
) . " about ";

echo CHtml::hiddenField("studyId", $studyId);
echo CHtml::hiddenField("form", "_form_expression_text");

$criteria=new CDbCriteria;
if($multi){
	$multiIds = q("SELECT id FROM question WHERE title = (SELECT title FROM question WHERE id = " .$multi . ")")->queryColumn();
	$studyIds = q("SELECT id FROM study WHERE multiSessionEgoId in (" . implode(",", $multiIds) . ")")->queryColumn();
	$criteria=array(
		'condition'=>"studyId in (" . implode(",", $studyIds) . ")",
	);
} else {
	$criteria=array(
		'condition'=>"studyId = " . $studyId,
		'order'=>'ordering',
	);
}

echo CHtml::dropdownlist(
	'questionId',
	'',
	CHtml::listData(Question::model()->findAll($criteria), 'id', 'title'),
	array('empty' => 'Choose One')
);

$this->endWidget();
?>

<?php
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'new-compound-expression',
    'enableAjaxValidation'=>true,
));

echo CHtml::hiddenField("studyId", $studyId);
echo CHtml::hiddenField("form", "_form_expression_compound");

echo CHtml::ajaxButton ("New compound expression",
    CController::createUrl('ajaxload'),
    array( 'update' => '#Expression', 'data'=>'js:$("#new-compound-expression").serialize()', 'method'=>'get'),
    array( 'id'=>uniqid(), 'live'=>false));

$this->endWidget();
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'new-comparison-expression',
    'enableAjaxValidation'=>false,
    'method'=>'GET',

));

echo CHtml::hiddenField("studyId", $studyId);
echo CHtml::hiddenField("form", "_form_expression_comparison");

echo CHtml::ajaxButton ("New comparison expression",
    CController::createUrl('ajaxload'),
    array( 'update' => '#Expression', 'data'=>'js:$("#new-comparison-expression").serialize()', 'method'=>'get'),
    array( 'id'=>uniqid(), 'live'=>false)
) . " about ";

$criteria=new CDbCriteria;
$criteria=array(
    'condition'=>"studyId = " . $studyId . " AND type='Counting'",
);

echo CHtml::dropdownlist(
    'expressionId',
    '',
    CHtml::listData(Expression::model()->findAll($criteria), 'id', 'name'),
    array('empty' => 'Choose One')
);
?>

<?php
$this->endWidget();
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'new-counting-expression',
    'enableAjaxValidation'=>false,
    'method'=>'GET',

));

echo CHtml::hiddenField("studyId", $studyId);
echo CHtml::hiddenField("form", "_form_expression_counting");

echo CHtml::ajaxButton ("New counting expression",
    CController::createUrl('ajaxload'),
    array( 'update' => '#Expression', 'data'=>'js:$("#new-counting-expression").serialize()', 'method'=>'get'),
    array( 'id'=>uniqid(), 'live'=>false));

$this->endWidget();
?>
	<div id="Expression" style="width:400px"></div>
</div>
