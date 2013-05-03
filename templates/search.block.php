<?php
    $t = get('t');
    $series = Config::get('series');
    $this_series = $series[$t];
    $req = '';
    if(in_array($_SESSION['s_role'], Config::get('salesman'))){
        $this_serach = $this_series['info_serach'];
        $req = '<span class="req">*</span>';
    }else{
        $this_serach = $this_series['serach'];
    }
    $serach_name = array();
    foreach($this_series['data'] as $value){
        $serach_name[$value['alias']] = $value['name'];
    }
?>
        <div class="widget fluid">
          <div class="formRow">
            <?php
                $num = 0;
                $str = '';
                $total = count($this_serach)/4;
                foreach ($this_serach as $key=>$value){
                    $num+=1;
                    $str .= '<div class="grid1 textR"><span>'.$serach_name[$key].'</span>';
                    if($key == 'model'){
                        $str .=$req;
                    }
                    $str .='</div>';
                    if(in_array($key,$this_series['range'])){
                        $str .= '<div class="grid0_8">
                                    <input id="min_'.$key.'" class="search text" type="text" value="'.get('min_'.$key).'">
                                </div>
                                <div style="float: left; text-align: center;margin-left: 0" class="grid0_2">~</div>
                                <div class="grid0_8" style="margin-left: 0;">
                                    <input id="max_'.$key.'" class="search text" type="text" value="'.get('max_'.$key).'">
                                </div>';
                    }else{
                        $str .= '<div class="grid1_8"><input id="'.$key.'" class="search text" type="text" value="'.get($key).'"></div>';
                    }
                    if($num%4==0 && $total!=$num/4){
                        $str .='<div class="clear"></div></div><div class="formRow" style="border-bottom: none">';
                    }
                }
                if($num%4 != 0) {
                            echo '<div class="clear"></div></div><div class="formRow">';
                }
                $str.='<div class="grid1"><a href="javascript:;" class="buttonS bBlue" id="search"><span>搜索</span></a></div>';
                if($_SESSION['s_role']==2){
                    $str.='<div class="grid2"><a href="javascript:;" class="buttonS bBlue" id="export"><span>数据导出</span></a></div>';
                }
                
                $str.='<div class="clear"></div></div>';

                echo $str;
            ?>
        </div>