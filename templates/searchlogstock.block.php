        <div class="widget fluid">
          <div class="formRow">
            <div class="grid1 textR">
              <span>产品型号：</span>  
            </div>  
            <div class="grid2">
              <input id="extra" class="search text" type="text" value="<?php echo get('extra'); ?>" name="extra">
            </div> 
            <div class="grid1 textR">
              <span>操作时间：</span>  
            </div>  
            <div class="grid3">
                <ul class="datesRange">
                    <li><input id="fromDate" class="timepicker" type="text" value="<?php echo get('min'); ?>" title="开始时间" placeholder="开始时间" name="min" readonly="readonly"></li>
                    <li class="sep">~</li>
                    <li><input id="toDate" class="timepicker" type="text" value="<?php echo get('max'); ?>" title="结束时间" placeholder="结束时间" name="max" readonly="readonly"></li>
                </ul>
            </div> 
            <div class="grid1">
              <a href="javascript:;" class="buttonS bBlue" id="search"><span>搜索</span></a>
            </div>
            <div class="clear"></div>
          </div>
        </div>
        <script>
        $(function() {
			var dates = $( "#fromDate, #toDate" ).datepicker({
				defaultDate: "+0",
				changeMonth: false,
				showOtherMonths:true,
				numberOfMonths: 1,
		        autoSize: true,
		        dateFormat: 'yy-mm-dd',
				onSelect: function( selectedDate ) {
					var option = this.id == "fromDate" ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" ),
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					dates.not( this ).datepicker( "option", option, date );
				}
			});

            $('#search').click(function(){
                var extra=$('#extra').val();
                var min = $('#fromDate').val();
                var max = $('#toDate').val();
                var url = '?m=logs&a=index';
                if(extra){
                    url+='&extra='+extra;
                }
                if(min){
                    url+='&min='+min;
                }
                if(max){
                    url+='&max='+max;
                }
                window.location.href=url;
            });
		});
        </script>