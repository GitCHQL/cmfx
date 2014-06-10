<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge" />
<title>系统后台</title>
<link href="/statics/css/admin_style.css<?php echo ($js_debug); ?>" rel="stylesheet" />
<link href="/statics/js/artDialog/skins/default.css<?php echo ($js_debug); ?>" rel="stylesheet" />
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/",
    JS_ROOT: "statics/js/",
    TOKEN: ""
};
</script>
<script src="/statics/js/wind.js<?php echo ($js_debug); ?>"></script>
<script src="/statics/js/jquery.js<?php echo ($js_debug); ?>"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <div class="nav">
    <ul class="cc">
      <li class="current"><a href="javascript:;">所有文章</a></li>
      <li><a target="_self" href="<?php echo U('post/add',array('term'=>empty($term['term_id'])?'':$term['term_id']));?>">添加文章</a></li>
    </ul>
  </div>
  <div class="h_a">搜索</div>
  <form  method="post" action="<?php echo u('post/index');?>">
    <div class="search_type cc mb10">
      <div class="mb10"> 
        <span class="mr20">分类：
        <select class="select_2" name="term">
          	<option value='0' >全部</option>
          	<?php echo ($taxonomys); ?>
        </select>
        &nbsp;&nbsp;时间：
        <input type="text" name="start_time" class="input length_2 J_date" value="<?php echo ((isset($formget["start_time"]) && ($formget["start_time"] !== ""))?($formget["start_time"]):''); ?>" style="width:80px;" autocomplete="off">-<input type="text" class="input length_2 J_date" name="end_time" value="<?php echo ($formget["end_time"]); ?>" style="width:80px;" autocomplete="off">
        
        <!-- 
        <select class="select_2" name="searchtype" style="width:70px;">
          <option value='0' >标题</option>
        </select>
         -->
               &nbsp; &nbsp;关键字：
        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="<?php echo ($formget["keyword"]); ?>" placeholder="请输入关键字...">
        <input type="submit" class="btn" value="搜索"/>
        </span>
      </div>
    </div>
  </form>
  <form class="J_ajaxForm" action="" method="post">
    <div class="table_list">
      <table width="100%">
        <thead>
	          <tr>
	            <td width="16"><label><input type="checkbox" class="J_check_all" data-direction="x" data-checklist="J_check_x"></label></td>
	            <td width="50">排序</td>
	            <td>ID</td>
	            <td>标题</td>
	            <!-- <td>点击量</td> -->
	            <td width="80">发布人</td>
	            <td width="120"><span>发布时间</span></td>
	            <td width="50">状态</td>
	            <td width="120">操作</td>
	          </tr>
        </thead>
        	<?php $status=array("1"=>"已审核","0"=>"未审核"); ?>
        	<?php if(is_array($posts)): foreach($posts as $key=>$vo): ?><tr>
		            <td><input type="checkbox" class="J_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="<?php echo ($vo["tid"]); ?>" ></td>
		            <td><input name='listorders[<?php echo ($vo["tid"]); ?>]' class="input mr5"  type='text' size='3' value='<?php echo ($vo["listorder"]); ?>'></td>
		            <td><a><?php echo ($vo["tid"]); ?></a></td>
		            <td><a href="<?php echo U('portal/article/index',array('id'=>$vo['tid']));?>" target="_blank">
		            	<span style="" ><?php echo ($vo["post_title"]); ?></span></a>
		            </td>
		            <!-- <td>0</td> -->
		            <td><?php echo ($users[$vo['post_author']]['user_login']); ?></td>
		            <td><?php echo ($vo["post_date"]); ?></td>
		            <td><?php echo ($status[$vo['post_status']]); ?></td>
		            <td>
		            	<a href="javascript:open_iframe_dialog('<?php echo u('comment/commentadmin/index',array('post_id'=>$vo['ID']));?>','评论列表')">查看评论</a> |
		            	<a href="<?php echo U('post/edit',array('term'=>empty($term['term_id'])?'':$term['term_id'],'id'=>$vo['ID']));?>" target="_blank" >修改</a>|
		            	<a href="<?php echo U('post/delete',array('term'=>empty($term['term_id'])?'':$term['term_id'],'tid'=>$vo['tid']));?>" class="J_ajax_del" >删除</a>
					</td>
	          	</tr><?php endforeach; endif; ?>
          </table>
      <div class="p10"><div class="pages"> <?php echo ($Page); ?> </div> </div>
     
    </div>
    <div>
      <div class="btn_wrap_pd">
        <label class="mr20"><input type="checkbox" class="J_check_all" data-direction="y" data-checklist="J_check_y">全选</label>                
        <button class="btn J_ajax_submit_btn" type="submit" data-action="<?php echo u('post/listorders');?>">排序</button>
        <button class="btn J_ajax_submit_btn" type="submit" data-action="<?php echo u('post/check',array('check'=>1));?>" data-subcheck="true" >审核</button>
        <button class="btn J_ajax_submit_btn" type="submit" data-action="<?php echo u('post/check',array('uncheck'=>1));?>" data-subcheck="true" >取消审核</button>
        <button class="btn J_ajax_submit_btn" type="submit" data-action="<?php echo u('post/delete');?>" data-subcheck="true" data-msg="你确定删除吗？">删除</button>
        <button class="btn" type="button" id="J_Content_remove">批量移动</button>
      </div>
    </div>
  </form>
</div>
<script src="/statics/js/common.js<?php echo ($js_debug); ?>"></script>
<script>

function refersh_window() {
    var refersh_time = getCookie('refersh_time');
    if (refersh_time == 1) {
        window.location="<?php echo u('post/index',$formget);?>";
    }
}
setInterval(function(){
	refersh_window();
}, 2000);
$(function () {
	setCookie("refersh_time",0);
    Wind.use('ajaxForm','artDialog','iframeTools', function () {
        //批量移动
        $('#J_Content_remove').click(function (e) {
            var str = 0;
            var id = tag = '';
            $("input[name='ids[]']").each(function () {
                if ($(this).attr('checked')) {
                    str = 1;
                    id += tag + $(this).val();
                    tag = ',';
                }
            });
            if (str == 0) {
				art.dialog.through({
					id:'error',
					icon: 'error',
					content: '您没有勾选信息，无法进行操作！',
					cancelVal: '关闭',
					cancel: true
				});
                return false;
            }
            var $this = $(this);
            art.dialog.open("/index.php?g=admin&m=post&a=move&ids=" + id, {
                title: "批量移动"
            });
        });
    });
});


</script>
</body>
</html>