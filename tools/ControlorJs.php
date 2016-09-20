<?php
function AdditionJsPost() {
    $inputReg = '';
    $additionJs = "
        $(document).ready(function(){
           $('#production5').focus(function(){
                $(this).after($('.ds-checkbox-items'));
                $('.ds-checkbox-items').slideDown(500);
            });
            $('#production1').blur(function() {
                var text = $(this).val();
                if(!text){
                    text = '单位';
                }
                var flag = '';
                if(text.indexOf('(')>-1 && text.indexOf(')')>-1) {
                   flag = text;
                }else {
                   flag = '('+text+')';
                }
                $(this).val(flag);
            });
            $('.ds-checkbox-button').click(function()
            {
                var checkValue = '';
                var boxChecked = $('.ds-checkbox :checkbox:checked').parent().siblings('.ds-checkbox-title');
                checkValue = boxChecked.map(function() {return $(this).text();}).get().join(';');
                $('.ds-checkbox-items').slideUp(500);
                $('#production5').val( checkValue );
            });
        });
    ";
    ViewScript( $additionJs );
}
function AnalysisJsPost() {
    $analysisJs = "
var data = {'date': '', 
            'abstract': '', 
            'obj': '',
            'had': 0, 
            'item': ''
            };
$(document).ready(function() {
    var rootUrl   = './action/AnalysisTableAction.php';
    var url       = location.href;
    var urlArray  = url.split('?');
    var item      = urlArray[1].split('=')[1];
    var initUrl   = rootUrl + '?' + urlArray[1];
    data['item']  = item; 
    getData(initUrl, tableDataProcess, item);
    
    $('.ds-sort-row').click(function() { 
        $(this).addClass('ds-sort-active').siblings().removeClass('ds-sort-active'); 
        var idVal = $(this).attr('id');
        sortList('.ds-analysis','.ds-info-item .ds-info-cell:eq('+idVal+') span');
    });

    $('.ds-analysis-item label').dblclick(function() { 
        var name = $(this).text();
        var url = './action/DeleteInfo.php';
        var data = {'tablechar': 'name', 'item': item, 'chardata': name};
        $(this).parent().remove();
        postData( url ,data);
    });

    $('.ds-analysis-item').not('.ds-analysis-report').click(function() {
        $(this).addClass('ds-active').siblings().removeClass('ds-active');
        var text     = $(this).children('label').text();
        var newUrl   = rootUrl + '?' + urlArray[1] + '&obj=' + text;
        $('.ds-table').find('tr').not('.ds-first-tr').remove();
        getData(newUrl, tableDataProcess, item);
    });

    $('.ds-report-plus').click(function() { 
        var text = $(this).parent().siblings('label').text();
        data['obj']  = text; 
        tradeReport(text, item) 
    });

    $('input[type=date]').blur(tableRowChoiceByDate);

    accordChange($('.ds-analysis-search input'), $('.ds-analysis-item'));
    $('table tbody').dblclick(function(e) {
            var className = $(e.target).attr('class');
            if(className == 'ds-detail-control') {
                var idValue = $(e.target).parent().attr('id');
                tableRowDelete(item, idValue);
                $(e.target).parent().remove();
            }
    });
});
    
function tableRowDelete(item, idValue) {
    var url    = './action/DeleteInfo.php';
    var table  = 'order_' + item;
    var data   = {'tablechar': 'order_id', 'item': table, 'chardata': idValue};
    console.log(data);
    postData( url ,data);
}
function tableRowChoiceByDate() {
    var dates = $('input[type=date]');
    var date1 = $(dates[0]).val();
    var date2 = $(dates[1]).val();
    var begin = 0;
    var end   = 99999999;
    if(date1){
        date1 = date1.replace(/\/|\.|\s|\-/g,'');
        if(date1.length == 8 ) {
            begin = date1;
        }else{
            alert('起始日期为八位数字');
        }    
    }  
    if(date2){
        if(date2.length == 8 || date2.length == 10) {
            date2 = date2.replace(/\/|\.|\s|\-/g,'');
            end   = date2;
        }else {
            var beginY = date1.substr(0, 4);
            var beginM = date1.substr(4, 2);
            var beginD = date1.substr(6, 2);
            var myDate = new Date();
            myDate.setFullYear(beginY, beginM, beginD);
            myDate.setDate(myDate.getDate()+eval(date2));
            beginY  = String(myDate.getFullYear());
            beginM  = String(myDate.getMonth());
            beginD  = String(myDate.getDate());
            beginM  = additionZero(beginM);
            beginD  = additionZero(beginD);
            end     = beginY + beginM + beginD;

        }
    }
    if(begin > end) {
        var flag = end;
        end   = begin;
        begin = flag;
    }
    findListRangeRows(begin, end, '.ds-analysis-detail tbody', 'td:eq(0)', '.ds-first-tr');
}
function additionZero(value) {
    if(value<10) {
        value = '0' + value;
    }
    return value;
}
function tradeReport(text, item) {
    text = '<span>'+ text+ '</span>';
    var title = enToch(item)+ text;
    dsmileModal(title+ '往来交易', 'tradeReportData');
}
function tradeReportData() {
    var url             = './action/TradeReportAction.php';
    var inputDom        = $('.ds-modal-rect').find('input');
    data['date']        = $(inputDom[0]).val(); 
    data['had']         = $(inputDom[1]).val(); 
    data['abstract']    = $(inputDom[2]).val(); 
    postData(url, data, 1);
    modalHide();
}
function tableDataProcess(data, item) {  
    var abstractKeys ={
        'buyer':      {0: 'order_id', 
                        1: 'date', 
                        2: ['buyer', 'production', 'number', 'sale'], 
                        3: 'money', 
                        4: 'gained', 
                        5: 'debt',
                        6: 'state'},
        'provider':   {0: 'order_id',
                        1: 'date', 
                        2: ['provider', 'production', 'number', 'sale'], 
                        3: 'money', 
                        4: 'pained', 
                        5: 'debt',
                        6: 'state'},
        'factory':    {0: 'order_id', 
                        1: 'date', 
                        2: ['factory', 'production', 'number', 'sale', 'type'], 
                        3: 'money', 
                        4: 'pained', 
                        5: 'debt',
                        6: 'state'},
        'express':    {0: 'order_id', 
                        1: 'date', 
                        2: ['express', 'buyer', 'production', 'provider', 'number'], 
                        3:'money', 
                        4: 'pained', 
                        5: 'debt',
                        6: 'state'},
        'production': {0: 'order_id', 
                        1: 'date', 
                        2: ['production', 'buyer', 'provider', 'number', 'sale'], 
                        3: 'money', 
                        4: 'pure_gain',
                        5: 'state'},
        'person':     {0: 'order_id', 
                        1: 'date', 
                        2: ['person', 'buyer', 'production', 'provider', 'number', 'sale'], 
                        3: 'money', 
                        4: 'pure_gain',
                        5: 'state'},
    };  
    if(!data){
        return flase;
    }
    var length = data.length;
    var keys   = abstractKeys[item];
    var beforeDebt   = 0;
    var moneyWhole   = 0;
    var pureWhole    = 0;
    var debtTd       = '';
    for(var i = length-1; i>=0; i--) {
        var newData      = data[i];
        var money        = newData[keys[3]];
            money        = money ? money : 0;
            moneyWhole = moneyWhole + parseFloat(money);
        if(item !='person' && item !='production') {
            var had          = newData[keys[4]];
            had              = had   ? had   : 0;
            var newDebt      = parseFloat(money) + beforeDebt - parseFloat(had);
            newData[keys[5]] = newDebt.toFixed(2);
            beforeDebt       = newDebt; 
            pureWhole        = pureWhole + parseFloat(had);
        }else{
            var pure   = newData[keys[4]];
            pureWhole  = pureWhole + parseFloat(pure);
        }
    }
    if(item !='person' && item !='production') {
        debtTd = '<td>'+beforeDebt.toFixed(2)+'</td>';
    }
    var whole = '<tr class=ds-yellow-background><td>累计</td><td>-</td><td>'+moneyWhole.toFixed(2)+'</td><td>'+pureWhole.toFixed(2)+'</td>'+debtTd+'</tr>';
    $(whole).appendTo('.ds-table');   

    for(var key in data){    
        var tableData    = [];
        for(var i = 0; i<7;i++) {
            if(typeof(keys[i]) == 'undefined') {
                break;
            }
            var newData  = data[key];
            if(i != 2) {
                tableData.push(newData[keys[i]]);
            }else{
                var flag = '';
                for(var value of keys[i]) {
                    var keyValue = newData[value];
                    flag = flag + keyValue +' ';
                }
                tableData.push(flag);
            }
        }
        var stateClass   = '';
        var tableDetail  = '';
        var detailLength = tableData.length;
        var flag = '';
        if(tableData[detailLength - 1] == 1) {
            stateClass = 'class = ds-red ';
            flag = 'class=ds-detail-control';
        }
        var tableDetail = '<tr '+stateClass+'id='+tableData[0]+'>';;
        for(var key in tableData) {
            var control = '';
            if(key == 0 || key == (detailLength - 1)) {
                continue;
            }
            if(key == 1) {
                control = flag;
            }
            tableDetail = tableDetail + '<td '+control+' >'+ tableData[key] +'</td>';
        }
        $(tableDetail).appendTo('.ds-table tbody');
        }         
    }
    ";
    ViewScript($analysisJs);
}

function OperationJsPost() {
    $inputReg = '';
    $operationJs = "
        function calcValue(obj1, obj2, obj3) {
          $(obj1+ ','+ obj2).change(function() {
            var num1 = $(obj2).val();
            var num2 = $(obj1).val();
            if(!num1) {
              num1 = 0;
            }
            if(!num2) {
              num2 = 0;
            }
            var result = num1*num2;
            $(obj3).val(result.toFixed(2));})
        }
        $(document).ready(function() {
            var initInput  = [5, 11, 17, 24, 31, 38, 45, 52, 56, 59];
            for(var key of initInput) {
                var text = $('input:eq('+ key +')').val(); 
                if(!text) {
                    $('input:eq('+ key +')').val((0).toFixed(2));
                }
            }
            var countInput = [5, 58, 11, 17, 24, 31, 38, 45, 52, 56];
            var countInputObj = [];
            for(var value of countInput ) {
              countInputObj.push('input:eq('+ value +')');
            }
            calcValue('input:eq(3)', 'input:eq(4)', 'input:eq(5)');
            calcValue('input:eq(9)', 'input:eq(10)', 'input:eq(11)');
            calcValue('input:eq(15)', 'input:eq(16)', 'input:eq(17)');
            calcValue('input:eq(22)', 'input:eq(23)', 'input:eq(24)');
            calcValue('input:eq(29)', 'input:eq(30)', 'input:eq(31)');
            calcValue('input:eq(36)', 'input:eq(37)', 'input:eq(38)');
            calcValue('input:eq(43)', 'input:eq(44)', 'input:eq(45)');
            calcValue('input:eq(50)', 'input:eq(51)', 'input:eq(52)');
            getSlideValue('#operation1', '#buyer');
            getSlideValue('#operation2', '#production');
            getSlideValue('#operation7', '#provider');
            getSlideValue('#operation8', '#production');
            getSlideValue('#operation13', '#factory');
            getSlideValue('#operation14', '#production');
            getSlideValue('#operation20', '#factory');
            getSlideValue('#operation21', '#production');
            getSlideValue('#operation27', '#factory');
            getSlideValue('#operation28', '#production');
            getSlideValue('#operation34', '#factory');
            getSlideValue('#operation35', '#production');
            getSlideValue('#operation41', '#factory');
            getSlideValue('#operation42', '#production');
            getSlideValue('#operation48', '#factory');
            getSlideValue('#operation49', '#production');
            getSlideValue('#operation55', '#express');
            getSlideValue('#operation60', '#person');

            $('input[type=text]').not('#operation59').change(function() {
                 var countValue = 0;
                 for(var key in countInputObj ) {
                   var inputValue = $(countInputObj[key]).val();
                   if(!inputValue) {
                      inputValue = 0;
                   }
                    if(key == 0) {
                      countValue = inputValue;
                    }
                    if(key == 1) {
                      countValue = countValue*(1-inputValue/100);
                    }
                    if(key > 1) {
                      countValue -= inputValue;
                    }
                 }
                 $('input:eq(59)').val( countValue.toFixed(2) );
            });
        });
    ";
    ViewScript( $operationJs );
}

function MainJsPost() {
    $mainJs = "
        var url       = location.href;
        var urlArray  = url.split('?');
        $(document).ready(function() {
            mainCalc();
            orderControl();            
            $('#revise').click(orderRevise);       
            accordChange($('.ds-main-table input'), $('.ds-table tr').not('.ds-first-tr'), mainCalc);
            $('select').change(orderByMonth);
        });
        function orderByMonth() {
            var year = $('select:eq(0)').val();
            var month = $('select:eq(1)').val();
            if(year == 'all') {
               month = 'all';
            }
            window.location.href = urlArray[0] + '?year='+ year +'&month=' + month;
        }
        function orderRevise() {
            var content = $(this).parent().parent().parent().attr('id');
            var url1 = './operation.php?orderId='+content;
            location.href = url1;
        }  
        function orderControl() {
            $('.ds-control').click(function() {
                $('.ds-order-control').appendTo($(this));
                $('.ds-order-control').slideDown(500,function() {
                    setTimeout(function() {
                        $('.ds-order-control').slideUp(500);
                    }, 2000);
                });
            });
        }
        function mainCalc() {
            calcCount();
            calcGains();
        }
        function calcCount() {   
            var calc1 = $('tr').not('.ds-disappear').not('.ds-first-tr').length;    
            var calc0 = 0;
            var falg  = 0;
            var objs  = $('tr').not('.ds-disappear').not('.ds-first-tr'); 
            objs.map(function(key, value) {
                var tdObj = $(value).find('td')[0];
                var text  = $(tdObj).text();
                if(text > falg) { 
                    calc0 = calc0 +1;
                };
                return true;
            });
            $('#calc0').text(calc0);
            $('#calc1').text(calc1);

        }
        function calcGains() {
            var calcColumn = [5, 15];
            var calcTr = $('tr').not('.ds-disappear').not('.ds-first-tr');

            for(var value1 of calcColumn) {
                var gainValue = 0;
                var calc = '#calc'+ value1;
                calcTr.map(function(key, value2) {
                   var tdObj = $(value2).find('td')[value1];
                   var text = $(tdObj).text();
                   gainValue += Number(text);  
                });
                $(calc).text(gainValue.toFixed(2));
            }
        }
    ";
    ViewScript( $mainJs );
}

?>
