<?php
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'expression-text-form',
    'enableAjaxValidation'=>false,
    'action'=>'/authoring/expression/'.$studyId,

));

Yii::app()->clientScript->registerScript('optionsToValue', "
function buildValue(times, expressionIds, questionIds){
    $('#Expression_value').val(times + ':' + expressionIds + ':' + questionIds);
}
");

Yii::app()->clientScript->registerScript('expressionsToValue', "
jQuery('.expressionList').change(function() {
    expressionValue = $('#Expression_value').val().split(/:/);
    expressionValue[1] = '';
    $('.expressionList').each(function() {
        if($(this).is(':checked')){
            if(expressionValue[1] != '')
                expressionValue[1] = expressionValue[1] + ',' + $(this).val();
            else
                expressionValue[1] = $(this).val();
        }
    });
    buildValue(expressionValue[0], expressionValue[1], expressionValue[2]);
    console.log($('#Expression_value').val());
});
");

Yii::app()->clientScript->registerScript('questionsToValue', "
jQuery('.questionList').change(function() {
    expressionValue = $('#Expression_value').val().split(/:/);
    expressionValue[2] = '';
    $('.questionList').each(function() {
        if($(this).is(':checked')){
            if(expressionValue[2] != '')
                expressionValue[2] = expressionValue[2] + ',' + $(this).val();
            else
                expressionValue[2] = $(this).val();
        }
    });
    buildValue(expressionValue[0], expressionValue[1], expressionValue[2]);
    console.log($('#Expression_value').val());
});
");

Yii::app()->clientScript->registerScript('timesToValue', "
jQuery('#times').change(function() {
    expressionValue = $('#Expression_value').val().split(/:/);
    expressionValue[0] = $(this).val();
    buildValue(expressionValue[0], expressionValue[1], expressionValue[2]);
    console.log($('#Expression_value').val());
});
");

echo $form->hiddenField($model, 'id', array('value'=>$model->id));
echo $form->hiddenField($model, 'studyId', array('value'=>$studyId));
echo $form->hiddenField($model, 'questionId', array('value'=>$question->id));
echo $form->hiddenField($model, 'value', array('value'=>$model->value));
echo $form->hiddenField($model, 'type', array('value'=>'Counting'));


if(strstr($model->value, ":")){
    list($times, $expressionIds, $questionIds) = explode(':', $model->value);
}else{
    $times = 1;
    $expressionIds = "";
    $questionIds = "";
}

?>			<legend>
				<span>Counting expression</span>
			</legend>
			<div wicket:id="feedback"/>
<?php echo $form->labelEx($model,'name'); ?>
<?php echo $form->textField($model,'name', array('style'=>'width:100px')); ?>
<?php echo $form->error($model,'name'); ?>
    	 	<br />
    	 	<br />
<?php
    echo CHtml::textField('times', $times, array('id'=>'times'));
?>
           times <?php

echo $form->dropdownlist($model,
    'operator',
    array(
        'Sum'=>'Sum',
        'Count'=>'Count',
    )
);
            ?>
    	 	of the selected expressions and questions below

<br style="clear:both">
<br style="clear:both">
<span style="text-decoration:underline">Expressions</span>

<div>
<?php

$study = Study::model()->findByPk($studyId);
$criteria=new CDbCriteria;
if($study->multiSessionEgoId){
	$multiIds = q("SELECT id FROM question WHERE title = (SELECT title FROM question WHERE id = " .$study->multiSessionEgoId . ")")->queryColumn();
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

    $selected = explode(',', $expressionIds);
    echo CHtml::CheckboxList(
        'expressionList',
        $selected,
        CHtml::listData(Expression::model()->findAll($criteria), 'id', 'name'),
        array(
            'separator'=>'<br>',
            'class'=>'expressionList',
        )
    );
?>
</div>
<br style="clear:both">
    	 	<span style="text-decoration:underline">Questions</span>
    	 	<div>
<?php
    $selected = explode(',', $questionIds);
    echo CHtml::CheckboxList(
        'questionList',
        $selected,
        CHtml::listData(Question::model()->findAll($criteria), 'id', 'title'),
        array(
            'separator'=>'<br>',
            'class'=>'questionList',
        )
    );
?>

            </div>
    	 	<br />
    		<input wicket:id="saveExpression" type="submit" value="Save"/>
	        <?php

$this->endWidget();
?>
<button onclick="$.get('/authoring/ajaxdelete?expressionId=<?php echo $model->id; ?>&studyId=<?php echo $model->studyId; ?>', function(data){location.reload();})">delete</button>