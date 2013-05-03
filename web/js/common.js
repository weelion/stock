$(function() {

    // IE6强制升级提示
    if ( jQuery.browser.msie && ( jQuery.browser.version == "6.0" ) && !jQuery.support.style ){
        $('div:not(#ie6-warning)').remove();
    }

    // 样式
    $("select, .check, .check :checkbox, input:radio, input:file").uniform();

    $("body").bind("ajaxSend", function(){
        $('#ajax_loader').show();
    }).bind("ajaxComplete", function(e, xhr){
        $('#ajax_loader').hide();
        var data = eval('(' + xhr.responseText + ')');
        // console.log(data);
        if(data.ajax_error) {
            $.jGrowl(data.title + ': ' + data.desc);
        }

    });

	// sidebar二级
    $('li.sideBarDrop').click(function () {
		$(this).children().eq(1).slideToggle(200);
	});
    $(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("sideBarDrop"))
		$(".leftUser").slideUp(200);
	});

     // 下拉菜单 
    $('.dropdown-toggle').dropdown();


        // 列表全选
    $(".checkAll").live('click', function() {
        var checkedStatus = this.checked;

        // 指定范围
        var key = $(this).attr('key');

        // 多个全选按钮
        var multi = $('.checkAll[key="'+key+'"]').attr('checked', this.checked);
        if(this.checked)
            multi.parent().addClass('checked');
        else 
            multi.parent().removeClass('checked');

        $('#'+key+' tbody tr td:first-child input:checkbox').each(function(){
            this.checked = checkedStatus;
            if (checkedStatus == this.checked) {
                $(this).closest('.checker > span').removeClass('checked');
                $(this).closest('table tbody tr').removeClass('thisRow');
            }
            if (this.checked) {
                $(this).closest('.checker > span').addClass('checked');
                $(this).closest('table tbody tr').addClass('thisRow');
            }
        });
    
    });

    // 列表单选效果
    $('tbody tr td:first-child input:checkbox').live('change', function() {
        $(this).closest('tr').toggleClass("thisRow", this.checked);
    });


    // 提示信息删除
    $('.nNote').click(function(){
        $(this).slideUp(500);
    });

    // 提示自动删除
    $('.nNote').delay(2500).slideUp(500);

    // sidebar 高度控制
    $('#sidebar').css('height', $(document).height());

});

jQuery(function($){
     $.datepicker.regional['zh-CN'] = {
        clearText: '清除',
        clearStatus: '清除已选日期',
        closeText: '关闭',
        closeStatus: '不改变当前选择',
        prevText: '<上月',
        prevStatus: '显示上月',
        prevBigText: '<<',
        prevBigStatus: '显示上一年',
        nextText: '下月>',
        nextStatus: '显示下月',
        nextBigText: '>>',
        nextBigStatus: '显示下一年',
        currentText: '今天',
        currentStatus: '显示本月',
        monthNames: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
        monthNamesShort: ['一','二','三','四','五','六', '七','八','九','十','十一','十二'],
        monthStatus: '选择月份',
        yearStatus: '选择年份',
        weekHeader: '周',
        weekStatus: '年内周次',
        dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
        dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
        dayNamesMin: ['日','一','二','三','四','五','六'],
        dayStatus: '设置 DD 为一周起始',
        dateStatus: '选择 m月 d日, DD',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        initStatus: '请选择日期',
        isRTL: false};
        $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
    });
$(document).ready(function(){

 $('.dTable tbody tr').hover(
  function() {$(this).attr('style','background: none repeat scroll 0 0 #B6C6D7;');},
  function() {$(this).removeAttr('style');}
 );
     // 如果复选框默认情况下是选择的，变色.
 $('.dTable input[type="checkbox"]:checked').parents('tr').addClass('selected');
 // 复选框
 $('.dTable tbody tr').click(
  function() {
   if ($(this).hasClass('selected')) {
    $(this).removeClass('selected');
    $(this).find('input[type="checkbox"]').removeAttr('checked');
   } else {
    $(this).addClass('selected');
    $(this).find('input[type="checkbox"]').attr('checked','checked');  //find 取得当前元素集合的每个元素的后代
   }
  }
 );
});