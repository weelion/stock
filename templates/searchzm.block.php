        <div class="widget fluid">
          <div class="formRow">
            <div class="grid1 textR">
              <span>物料代码：</span>  
            </div>  
            <div class="grid2">
              <input id="code" class="search text" type="text" value="<?php echo get('code'); ?>">
            </div>
            <div class="grid1 textR">
              <span>生产批号：</span>  
            </div>  
            <div class="grid2">
              <input id="pno" class="search text" type="text" value="<?php echo get('pno'); ?>">
            </div>  
            <div class="grid1 textR">
              <span>产品型号：</span>  
            </div>  
            <div class="grid2">
              <input id="model" class="search text" type="text" value="<?php echo get('model'); ?>">
            </div> 
            <div class="clear"></div>
          </div>
          <div class="formRow" style="border-bottom: none">
            <div class="grid1 textR">
              <span>亮度(LM)：</span>  
            </div>  
            <div class="grid1">
              <input id="min_bright" class="search text" type="text" value="<?php echo get('min_bright'); ?>">
            </div> 
            <div style="width: 24px;float: left; text-align: center">~</div>
            <div class="grid1" style="margin-left: 0;">
              <input id="max_bright" class="search text" type="text" value="<?php echo get('max_bright'); ?>">
            </div>
            <div class="grid1 textR">
              <span>色温(K)：</span>  
            </div>  
            <div class="grid1">
              <input id="min_ctemp" class="search text" type="text" value="<?php echo get('min_ctemp'); ?>">
            </div> 
            <div style="width: 24px;float: left; text-align: center">~</div>
            <div class="grid1" style="margin-left: 0;">
              <input id="max_ctemp" class="search text" type="text" value="<?php echo get('max_ctemp'); ?>">
            </div>
            <div class="grid1 textR">
              <span>电压(K)：</span>  
            </div>  
            <div class="grid1">
              <input id="min_voltage" class="search text" type="text" value="<?php echo get('min_voltage'); ?>">
            </div> 
            <div style="width: 24px;float: left; text-align: center">~</div>
            <div class="grid1" style="margin-left: 0;">
              <input id="max_voltage" class="search text" type="text" value="<?php echo get('max_voltage'); ?>">
            </div>
            <div class="grid1">
              <a href="javascript:;" class="buttonS bBlue" id="search"><span>搜索</span></a>
            </div>
            <div class="clear"></div>
          </div>
        </div>