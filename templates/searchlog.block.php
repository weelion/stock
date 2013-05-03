        <div class="widget fluid">
          <div class="formRow">
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
            <div class="grid1 textR">
              <span>操作员：</span>  
            </div>  
            <div class="grid2">
              <input id="username" class="search text" type="text" value="<?php echo get('username'); ?>" name="username">
            </div>  
            <div class="grid1 textR">
              <span>类型：</span>  
            </div>
            <div class="grid1">
            <?php 
                $types = array('user' => '会员','system' => '系统','stock' =>'库存','bin' => 'Bin');
            ?>
             <select name="type" id="type">
                <?php
                    foreach ($types as $key=>$value ){
                        echo '<option value="'.$key.'" '.($key==get('model')?'selected="selected"':'').'>'.$value.'</option>';
                    }
                ?>
             </select>
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
				defaultDate: "+1w",
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
                var username=$('#username').val();
                var min = $('#fromDate').val();
                var max = $('#toDate').val();
                var type = $('#type').val();
                var url = '?m=logs&a=index';
                if(username){
                    url+='&username='+username;
                }
                if(min){
                    url+='&min='+min;
                }
                if(max){
                    url+='&max='+max;
                }
                if(type){
                    url+='&type='+type;
                }
                window.location.href=url;
            });
		});
        </script>