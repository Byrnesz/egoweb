<a href="javascript:void(0)" onclick="$('input[type=checkbox]').prop('checked', true)">Select All</a> ::
<a href="javascript:void(0)" onclick="$('input[type=checkbox]').prop('checked', false)">De-select All</a>
Include Response Data<br>
<?php

    foreach($interviews as $interview){
        if($interview->completed == -1)
            $completed = "<span style='color:#0B0'>COMPLETED</span>";
        else
            $completed = "INCOMPLETE";
        echo "<div class='multiRow' style='width:200px;text-align:left'>".CHtml::checkbox('export[]', false,array('class'=>"export",'value'=>$interview->id, "id"=>"export-$interview->id,")). " " . Interview::getEgoId($interview->id)."</div>";
        echo "<div class='multiRow' style='width:120px'>".$completed."</div>";
        echo "<div class='multiRow'>".CHtml::button('Review',array('onclick'=>"document.location='".$this->createUrl('/interview/'.$study->id.'/'.$interview->id . "#/page/0'")))."</div>";
        echo "<div class='multiRow'>".CHtml::button('Visualize',array('submit'=>$this->createUrl(rootUrl + '/data/visualize?expressionId=&interviewId='.$interview->id)))."</div>";
        echo "<br style='clear:both'>";
    }
?>
<br clear="all">
