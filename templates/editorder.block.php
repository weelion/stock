<?php
    $t = get('t');
    $series = Config::get('series');
    $this_series = $series[$t];
    $this_series_serach = $this_series['info_serach'];
    foreach($this_series['data'] as $value){
        $table_name[$value['alias']] = $value;
    }
    $this_series_serach['total']=0;
?>
        <div id="edit_dialog" class="fluid" style="display: none" title="编辑出单信息">
            <form action="<?php echo home_url().module_url('doEdit'); ?>" method="POST" class="main" id="edit_form">
                <div class="formRow">
                    <?php
                        $i = 0;
                        foreach($this_series_serach as  $key => $value):
                            if($key == 'total') :
                    ?>
                    <div class="grid2 textR">
                        <span><?php echo $table_name[$key]['name']; ?>：</span>  
                    </div>
                    <div class="grid2">
                        <div class="ui-spinner">
                            <button class="ui-spinner-add ui-type" type="button" v="1"></button>
                            <button class="ui-spinner-subtract ui-type" type="button" v="2"></button>
                            <input type="hidden" name="operator" value="0"/>
                            <input type="text" name="<?php echo $key; ?>" value="" class="ui-spinner-box" autocomplete="off" data="<?php echo $key; ?>">
                        </div>
                    </div>  
                    <?php else: ?>
                    <div class="grid2 textR">
                        <span><?php echo $table_name[$key]['name']; ?>：</span>  
                    </div>
                    <div class="grid2">
                        <input name="<?php echo $key; ?>" class="text" type="text" value="" data="<?php echo $key; ?>">
                    </div>  
                    <?php endif; ?>
                    <?php
                        $i++;
                        if($i%3 == 0) echo '<div class="clear"></div></div><div class="formRow">';
                        endforeach; 
                        if($i%3 != 0)
                            echo '<div class="clear"></div></div><div class="formRow">';
                    ?>
                    <div class="grid2" style="float:right;text-align: right;">
                        <a href="javascript:;" class="buttonS bBlue" id="edit_save"><span style="color: #fff">保存</span></a>
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>

        <script>
            $(function(){
                $(".ui-type").click(function() {
                    var v = $(this).attr('v');
                    if(v == 1) {
                        $(".ui-type[v=2]").removeAttr('style');
                        $(this).attr('style', 'background: url(../images/elements/forms/spinnerAdd_ed.png) no-repeat;');
                    } else {
                        $(".ui-type[v=1]").removeAttr('style');
                        $(this).attr('style', 'background: url(../images/elements/forms/spinnerSubtract_ed.png) no-repeat;');
                    }

                    $("input[name='operator']").val(v);
                });
            })
        </script>