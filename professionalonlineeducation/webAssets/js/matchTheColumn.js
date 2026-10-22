/* Functions ---------- */
function buildTargetFields(data,id,zeroSet) {
  //  alert(id);
    let y = 0;
    $.each(data, function(i, n) {
        $("#targetFields_"+id).append('<div><label>' + n.title + ':</label><div data-correct="'+zeroSet[y]+'" data-key="'+ i +'" class="targetField_'+id+' targetFieldEle k-textbox k-space-right" data-field-id="' + i + '" data-source=""></div></div>');
        y++;
    });
}

function buildSourceList(data,id) {

    $.each(data, function(i, n) {
        $("#sourceFields_"+id).append('<div data-key="'+ n +'" class="sourceFieldEle sourceField_'+id+' k-widget">' + n + '</div>');
    });
}

function bindDelete(theField,targetData) {

    $(theField).find(".k-cancel").click(function (event) {
        clearField(theField,targetData);
    });
}

function clearField(theField,targetData) {
    setField(theField, "",targetData);
    $(theField).html("");
}

function setField(theField, value,targetData,questionKey) {
    let indicator;
    var field = $(theField).data("field-id");
    var fieldId = $(theField).data("correct");
    if (questionKey === fieldId){
        $(theField).addClass('MTCorrectAnsCon');
        $(theField).removeClass('MTWrongAnsCon');
        // indicator = '<span class="MTCorrectAns"><i class="fa fa-check" aria-hidden="true"></i></span>';
    }else{
        $(theField).addClass('MTWrongAnsCon');
        $(theField).removeClass('MTCorrectAnsCon');
        // indicator = '<span class="MTWrongAns"><i class="fa fa-times" aria-hidden="true"></i></span>';
    }

    $(theField).html(value  + '<span class="k-icon k-cancel"</span>');
    $(theField).data("source", value);

    setTargetData(field, value,targetData);
}

function setTargetData(fieldId, value,targetData) {
    // console.log(fieldId);
    // console.log(value);
    // console.log(targetData);
    targetData[fieldId].value = value;
    outputTargetData();
}

function outputTargetData(id,targetData) {
    $("#output_"+id).text(JSON.stringify(targetData));
}
