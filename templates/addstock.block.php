<?php
    $t = get('t');
    $series = Config::get('series');
    $this_series = $series[$t];
?>
        <div id="add_dialog" class="fluid" style="display: none" title="添加库存信息">
            <form action="<?php echo home_url().module_url('doAdd'); ?>" method="POST" class="main" id="add_form">
                <div class="formRow">
                    <?php
                        foreach($this_series['data'] as  $k => $value):

                    ?>
                    <div class="grid2 textR">
                        <span><?php echo $value['name']; ?>：</span>  
                    </div>
                    <div class="grid2">
                        <input name="<?php echo $value['alias']; ?>" class="text" type="text" value="<?php echo $value['default'];?>">
                    </div>  
                    <?php
                        $k++;
                        if($k%3 == 0) echo '<div class="clear"></div></div><div class="formRow">';
                        endforeach;
                        if($k%3 != 0)
                            echo '<div class="clear"></div></div><div class="formRow">';
                    ?>

                    <div class="grid2" style="float:right;text-align: right;">
                        <a href="javascript:;" class="buttonS bBlue" id="add_save"><span style="color: #fff">保存</span></a>
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
