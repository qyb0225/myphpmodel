$(function() {
    $('input:eq(0)').focus();
    $('.ds-user').hover(function() { 
        $('.ds-user-operation').slideToggle(10); 
    });

});

function postData(url, data, type = 0) {
    $.ajax({
            type:"POST",
            url: url,
            data: data,
            datatype: "json",
            success: function() {
                if(type) {
                    window.location.reload();
                }
            },
            error: function(){
                alert("提交错误！");
            }
        });
}
function getData(url, backFunction, item) {
    $.ajax({
            type:"GET",
            contentType: "application/x-www-form-urlencoded",
            url: url,
            datatype: "json",
            success: function(data) {
                backFunction(eval(data), item);
            },
            error: function(){
                alert("提交错误！");
            }
        });
}
function inputRegulation(obj, regulation,error) {
    $(obj).change(function() {
        var inputValue = $(this).val();
        var patter = new RegExp();
        var patter = regulation;
        if(!patter.test(inputValue)) {
            $(obj).focus();
            dsmileErrorAlert(error + '格式不正确！');
            }
    });

}

function accordChange(obj1, slide, thenFunc = ''){
    obj1.bind('input propertychange',function(){
        var text = $(this).val();
        var slideLength = slide.length;
        if(text) {
            for(var  i= 0; i < slideLength; i++) {
                var flag = slide[i];
                if($(flag).text().indexOf(text) > -1) {
                    $(flag).removeClass('ds-disappear');
                }else {
                    $(flag).addClass('ds-disappear');
                }
            }
        }else{
            for(var  i= 0; i < slideLength; i++) {
                var flag = slide[i];
                $(flag).removeClass('ds-disappear');
            }
        }
        if(thenFunc) {
            thenFunc();
        }
    });
}

function getSlideValue(input, obj) {
  $(input).focus(function() {
      $(this).after($(obj));
      $(obj).show();
      $(obj+ ' .ds-slide').css('display', '');
  });
  $(obj+ ' .ds-slide').click(function() {
      $(this).parent().hide();
      var text = $(this).children().text();
      $(this).parent().siblings('input').val( text );
  });
  accordChange($(input), $(obj+ ' .ds-slide'));
}

function findListRangeRows(begin, end, list, rowObj, notRow) {
    var rows = $(list).children().not(notRow);
    var rowsLength = rows.length; 
    for(var i=0; i<rowsLength; i++) {
        var value = $(rows[i]).find(rowObj).text();
        if(value < begin || value > end) {
            $(rows[i]).addClass('ds-disappear');
        }else {
            $(rows[i]).removeClass('ds-disappear');
        }
    }
}

function sortList(list, rowObj, des_asc = 1) {
    var rows       = $(list).children();
    var rowsLength = rows.length; 
    for(var i = 0 ; i < rowsLength; i++){     
        var beforeValue;    
        var afterVale;
        var flag = 0;
        var newRows = $(list).children();
        var newRowsLength = newRows.length; 
        beforeValue = $(newRows[i]).find(rowObj).text();
    for(var j = i ; j < newRowsLength; j++){
          afterVale = $(newRows[j]).find(rowObj).text();
         if(des_asc*(beforeValue - afterVale) <= 0){
             beforeValue = afterVale;
             flag = j;
          }    
        } 
        $(newRows[flag]).insertBefore($(newRows[i]));
      }
   }
   