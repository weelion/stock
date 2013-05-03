<?php
    $t = get('t');
    $series = Config::get('series');
    $this_series = $series[$t];
?>
        <div id="edit_dialog" class="fluid" style="display: none" title="编辑库存信息">
            <form action="<?php echo home_url().module_url('doEdit2'); ?>" method="POST" class="main" id="edit_form">
                <div class="formRow">
                    <?php
                        $i = 0;
                        foreach($this_series['data'] as  $key => $value):
                            if($value['alias'] == 'total') :
                    ?>
                    <div class="grid2 textR">
                        <span><?php echo $value['name']; ?>：</span>  
                    </div>
                    <div class="grid2">
                        <div class="ui-spinner">
                            <button class="ui-spinner-add ui-type" type="button" v="1"></button>
                            <button class="ui-spinner-subtract ui-type" type="button" v="2"></button>
                            <input type="hidden" name="operator" value="0"/>
                            <input type="text" name="<?php echo $value['alias']; ?>" value="" class="ui-spinner-box" autocomplete="off">
                        </div>
                    </div>  
                    <?php else: ?>
                    <div class="grid2 textR">
                        <span><?php echo $value['name']; ?>：</span>  
                    </div>
                    <div class="grid2">
                        <input name="<?php echo $value['alias']; ?>" class="text" type="text" value="" <?php if($value['alias'] != 'total'): ?>readonly="readonly"<?php endif; ?>>
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
                        <input name="id" type="hidden" value="" />
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