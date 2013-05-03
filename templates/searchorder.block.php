<?php
    $t = get('t');
    $series = Config::get('series');
    $this_series = $series[$t];
    $this_serach = $this_series['info_serach'];
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
                $total = count($this_serach)/3;
                foreach ($this_serach as $key=>$value){
                    if(in_array($key,$this_series['range'])){
                        $num+=1;
                        $str .= '<div class="grid1 textR"><span>'.$serach_name[$key].'</span></div>';
                        if(in_array($key,$this_series['range'])){
                            $str .= '<div class="grid1">
                                        <input id="min_'.$key.'" class="search text" type="text" value="'.get('min_'.$key).'">
                                    </div>
                                    <div style="width: 24px;float: left; text-align: center">~</div>
                                    <div class="grid1" style="margin-left: 0;">
                                        <input id="max_'.$key.'" class="search text" type="text" value="'.get('max_'.$key).'">
                                    </div>';
                        }else{
                            $str .= '<div class="grid2"><input id="'.$key.'" class="search text" type="text" value="'.get($key).'"></div>';
                        }
                        if($num%3==0 && $total!=$num/3){
                            $str .='<div class="clear"></div></div><div class="formRow" style="border-bottom: none">';
                        }
                    }
                }
                if($num%3 != 0) {
                            echo '<div class="clear"></div></div><div class="formRow">';
                }
                $str.='<div class="grid1"><a href="javascript:;" class="buttonS bBlue" id="search"><span>搜索</span></a></div><div class="clear"></div>';
                echo $num?$str.'</div>':'</div>';
            ?>
        </div>