<?php   
TC::layout(
["path"=>$path],
function() use($files,$folders,$path){
    
    $file_ico=function($item){
        $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
        if(in_array($ext,['bmp','jpg','jpeg','png','gif'])){
      	    return "image";
        }
        if(in_array($ext,['mp4','mkv','webm','avi','mpg', 'mpeg', 'rm', 'rmvb', 'mov', 'wmv', 'mkv', 'asf'])){
      	    return "ondemand_video";
        }
        if(in_array($ext,['ogg','mp3','wav'])){
      	    return "audiotrack";
        }
        return "insert_drive_file";
    }
?>
<div class="mdui-container-fluid">

<!--div class="mdui-typo" style="padding: 20px;">
	
</div-->

<style>
.thumb .th{
	display: none;
}
.thumb .mdui-text-right{
	display: none;
}
.thumb .mdui-list-item a ,.thumb .mdui-list-item {
	width:217px;
	height: 230px;
	float: left;
	margin: 10px 10px !important;
}

.thumb .mdui-col-xs-12,.thumb .mdui-col-sm-7{
	width:100% !important;
	height:230px;
}

.thumb .mdui-list-item .mdui-icon{
	font-size:100px;
	display: block;
	margin-top: 40px;
	color: #7ab5ef;
}
.thumb .mdui-list-item span{
	float: left;
	display: block;
	text-align: center;
	width:100%;
	position: absolute;
    top: 180px;
}
.forcedownload{
    display:block;
    float:right;
}
.thumb .forcedownload{
    display:none;
}
.forcedownload:hover{
    color:#555;
}
</style>
<div class="nexmoe-item">
<div class="mdui-row">
	<ul class="mdui-list">
		<li class="mdui-list-item th">
		  <div class="mdui-col-xs-12 mdui-col-sm-7">文件 <i class="mdui-icon material-icons icon-sort" data-sort="name" data-order="downward">expand_more</i></div>
		  <div class="mdui-col-sm-3 mdui-text-right">修改时间 <i class="mdui-icon material-icons icon-sort" data-sort="date" data-order="downward">expand_more</i></div>
		  <div class="mdui-col-sm-2 mdui-text-right">大小 <i class="mdui-icon material-icons icon-sort" data-sort="size" data-order="downward">expand_more</i></div>
		  <i class="mdui-icon material-icons" style="opacity:0">file_download</i>
		</li>
		<?php if($path != '/'):?>
		<li class="mdui-list-item mdui-ripple">
			<a href="<?php echo TC::abspath($path,"../");?>">
			  <div class="mdui-col-xs-12 mdui-col-sm-7">
				<i class="mdui-icon material-icons">arrow_upward</i>
		    	..
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"></div>
			  <div class="mdui-col-sm-2 mdui-text-right"></div>
		  	</a>
		</li>
		<?php endif;?>
		
		<?php foreach($folders as $item): if(!$item)continue;?>
		<li class="mdui-list-item mdui-ripple">
			<a href="<?php echo TC::abspath($path,$item['name']);?>">
			  <div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
				<i class="mdui-icon material-icons">folder_open</i>
		    	<span><?php echo $item['name'];?></span>
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"><?php echo $item['lastOpTime'];?></div>
			  <div class="mdui-col-sm-2 mdui-text-right"></div>
		  	</a>
		</li>
		<?php endforeach;?>
		<?php foreach($files as $item): if(!$item)continue;?>
		<li class="mdui-list-item file mdui-ripple">
			<a data-readypreview="<?php echo TC::ext($item['name']);?>" href="<?php echo TC::abspath($path,rawurlencode($item['name']));?>">
			  <div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
				<i class="mdui-icon material-icons" data-icon='<?php echo json_encode($item['icon']);?>'><?php echo $file_ico($item);?></i>
		    	<span><?php echo $item['name'] ;?></span>
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"><?php echo $item['lastOpTime'];?></div>
			  <div class="mdui-col-sm-2 mdui-text-right"><?php echo TC::human_filesize($item['size']);?></div>
			  <div class="forcedownload" >
			      <a href="<?php echo TC::abspath($path,rawurlencode($item['name']));?>">
			          <i class="mdui-icon material-icons">file_download</i>
			      </a>
			  </div>
		  	</a>
		</li>
		<?php endforeach;?>
	</ul>
</div>
</div>
<?php //if($readme):?>
<!--div class="mdui-typo mdui-shadow-3" style="padding: 20px;margin: 20px; 0">
	<div class="mdui-chip">
	  <span class="mdui-chip-icon"><i class="mdui-icon material-icons">face</i></span>
	  <span class="mdui-chip-title">README.md</span>
	</div>
	<?php //e($readme);?>
</div-->
<?php //endif;?>
</div>
<?php TC::readypreview(); ?>
<script>
TC.askPreview=function(ext,link){
    return new Promise(function(resolve,reject){
        if($('.mdui-fab i').text() != "apps")return resolve();
        if(confirm("请选择您的操作\n[确认]:预览\n[取消]:下载"))return resolve();
        reject();
    });
}
jQuery(".forcedownload").click(function(e){
   e.stopPropagation();
});
function downall() {
     let dl_link_list = Array.from(document.querySelectorAll("li a"))
         .map(x => x.href) // 所有list中的链接
         .filter(x => x.slice(-1) != "/"); // 筛选出非文件夹的文件下载链接

     let blob = new Blob([dl_link_list.join("\r\n")], {
         type: 'text/plain'
     }); // 构造Blog对象
     let a = document.createElement('a'); // 伪造一个a对象
     a.href = window.URL.createObjectURL(blob); // 构造href属性为Blob对象生成的链接
     a.download = "folder_download_link.txt"; // 文件名称，你可以根据你的需要构造
     a.click() // 模拟点击
     a.remove();
}
function thumb(){
	if($('.mdui-fab i').text() == "apps"){
		$('.mdui-fab i').text("format_list_bulleted");
		$('.nexmoe-item').removeClass('thumb');
		$('.nexmoe-item .mdui-icon').show();
		$('.nexmoe-item .mdui-list-item').css("background","");
	}else{
		$('.mdui-fab i').text("apps");
		$('.nexmoe-item').addClass('thumb');
		$('.mdui-col-xs-12 i.mdui-icon').each(function(){
			if($(this).text() == "image" || $(this).text() == "ondemand_video"){
			    try{
			        var j = jQuery(this).data("icon");
				    jQuery(this).hide();
				    jQuery(this).parent().parent().parent().css("background","url("+j.smallUrl+")  no-repeat center");
			    }catch(e){
			        console.error(e)
			    }
			}
		});
	}
}
</script>
<a href="javascript:thumb();" class="mdui-fab mdui-fab-fixed mdui-ripple mdui-color-theme-accent"><i class="mdui-icon material-icons">format_list_bulleted</i></a>
    <?php
    });
?>