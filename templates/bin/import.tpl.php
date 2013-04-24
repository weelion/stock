<?php block('header'); ?>
<?php block('sidebar'); ?>
<script src="<?php echo js_path(); ?>plugins/uploader/plupload.js"></script>
<script src="<?php echo js_path(); ?>plugins/uploader/lang_cn.js"></script>
<script src="<?php echo js_path(); ?>plugins/uploader/plupload.html4.js"></script>
<script src="<?php echo js_path(); ?>plugins/uploader/plupload.html5.js"></script>
<script src="<?php echo js_path(); ?>plugins/uploader/jquery.plupload.queue.js"></script>
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>控制中心</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="http://ufc.dev/">控制中心</a></li>
                <li class="current"><a href="/?m=bin&a=import&t=<?php echo $tdata['t'];?>" title="<?php echo $tdata['title'];?>Bin导入"><?php echo $tdata['title'];?>Bin导入</a></li>
            </ul>
        </div>

        <div class="breadLinks">
             <div class="clear"></div>
        </div>
        
    </div>
    <!-- Breadcrumbs line ends -->
           <!-- Main content begins -->
    <div class="wrapper">
        <div class="widget">
            <div class="whead"><h6>批量导入Bin之<?php echo $tdata['title'];?></h6><div class="clear"></div></div>
            <div class="m20 ml20 mr20">
                <!-- upload images ends -->
                <div style="margin-top: 20px">
                    <span>导入<?php echo $tdata['title'];?>Bin数据：</span>
                    <div class="m10" id="upload_file">
                        <a id="pickfiles" class="bDefault buttonS" href="javascript:void(0);">选择文件</a>
                        <a id="uploadfiles" class="bDefault buttonS" href="javascript:void(0);">导入文件</a>
                        <span id="filelist"></span>
                    </div>
                </div>
            </div>
            <!-- import products ends -->
        </div>
    </div>
    <!-- Main content ends-->

    <script type="text/javascript">
        $(function() {

            // 文件导入
            var uploader = new plupload.Uploader({
                runtimes : 'html5, html4',
                browse_button : 'pickfiles',
                container : 'upload_file',
                max_file_size : '1mb',
                url : '/?m=bin&a=doimport&t=<?php echo $tdata['t'];?>',
                filters : [
                    {title : "Excel文件", extensions : "xls"},
                ],
            });


            $('#uploadfiles').click(function(e) {
                uploader.start();
                e.preventDefault();
            });

            uploader.init();

            $('#pickfiles').click(function() {
                for(i in uploader.files) {
                    uploader.removeFile(uploader.files[i]);
                }

                $('#filelist').html('');
            });

            uploader.bind('FilesAdded', function(up, files) {
                $('#pickfiles').html('重新选择');
                $.each(files, function(i, file) {
                    $('#filelist').append('<span id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' + '</span>');
                });
            });

            uploader.bind('UploadProgress', function(up, file) {
                $('#' + file.id + " b").html(file.percent + "%");
            });

            uploader.bind('Error', function(up, err) {
                $('#pickfiles').html('重新选择');
                $('#filelist').append('<span class="redBack">' + err.message + '</span>');
            });

            uploader.bind('FileUploaded', function(up, file, info) {
                $('#' + file.id + " b").html("100%");
                var response = jQuery.parseJSON(info.response);
                if(response.status == 'success') {
                    $('#filelist').html('<span class="greenBack">导入成功</span>');
                } else {
                    $('#filelist').html('<span class="redBack">' + response.message + '</span>');
                }
            });

        });
    </script>
    </div>
</div>
<?php block('footer'); ?>