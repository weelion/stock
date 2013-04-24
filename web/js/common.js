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

    // 自动删除
    $('.nNote').delay(2500).slideUp(500);



});