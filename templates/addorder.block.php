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
        <div id="add_dialog" class="fluid" style="display: none" title="添加出单信息">
            <form action="<?php echo home_url().module_url('doAdd'); ?>" method="POST" class="main" id="add_form">
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
                            <input type="text" name="<?php echo $key; ?>" value="" class="ui-spinner-box" autocomplete="off">
                        </div>
                    </div>  
                    <?php else: ?>
                    <div class="grid2 textR">
                        <span><?php echo $table_name[$key]['name']; ?>：</span>  
                    </div>
                    <div class="grid2">
                        <input name="<?php echo $key; ?>" class="text" type="text" value="">
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
                        <a href="javascript:;" class="buttonS bBlue" id="add_save"><span style="color: #fff">保存</span></a>
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>