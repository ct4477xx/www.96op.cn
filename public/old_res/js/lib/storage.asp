<!--JavaScript storage-->
<script src="../../js/lib/layer/layer.js"></script>
<script src="../../js/lib/jquery.validate.min.js"></script>
<script type="text/javascript">
//表单验证
//$(function(){
//	$("#from_post").validationEngine();
//});
$("#from_post").validate({
//rules: {
//	this_SiteName:'required'
//},
submitHandler: function(form) { 
	var validateBool=$("#from_post").validationEngine("validate");//验证表单是否全部通过
	var post_formFlag = false;
	if(validateBool){
		$('#Btn').val('保存中...').attr('disabled',true);
		$('#Btn_ord').val('正在占位中,请稍等...').attr('disabled',true);
		//$('#Btn_ord').val('提交订单').attr('disabled',false);////////////////////////
		if(post_formFlag) {
			layer.msg("系统繁忙...", {shift:6}); 
			$('#Btn').val('保存').attr('disabled',false); 
			$('#Btn_ord').val('提交订单').attr('disabled',false);
			return false;
		}
		post_formFlag = true;
		$.ajax({  
		  type: 'POST',
		  url: $("#from_post").attr("href"),
		  dataType:'json',   
		  data: $("#from_post").serialize(),  
		  success:function(json) {  
			if(!json.success){
			  layer.msg("操作失败！", {shift:6},3000); 
			  post_formFlag = false;
			} else {
			  layer.msg(json.msg, {shift:5},2000); 
			  $('#Btn').val('保存').attr('disabled',false); 
			  //$('#Btn_ord').val('提交订单').attr('disabled',false);   //防止下单后重复点击,所以没有做状态还原
			  post_formFlag = false;
			  //刷新
			  var sType;
			  sType=json.isReload
			  //console.log(sType);
			  switch(sType)
				{
					case "9","closereloadsonreload":
						parent.$.dialog.close();parent.sonspace.location.reload();
						break;
					case "8","reload":
						history.go(-1);
						break;
					case "7","sonreload":
						parent.sonspace.location.reload();
						break;
					case "6","sonurl":
						parent.sonspace.location.href=json.url;
						break;
					case "5","purl":
						parent.$.dialog.close();parent.location.href=json.url;
						break;
					case "4","url":
						parent.location.href=json.url;
						break;
					case "3","palerturl":
						alert(json.txt);parent.location.href=json.url;
						break;
					case "2","gourl":
						location.href=json.url;
						break;
					case "1","closereload":
						parent.$.dialog.close();parent.location.reload();
						break;
					case "0","alertreload":
						alert(json.txt);parent.location.reload();
						break;
					default:
						//alert("服务器响应缓慢,请在页面自动刷新后查看是否操作成功.");parent.location.reload();
						break;
				}
			}
		  },  
		  timeout:5000,
		  error: function(){
			$('#Btn').val('保存').attr('disabled',false); 
			$('#Btn_ord').val('提交订单').attr('disabled',false);
			post_formFlag = false;
		  } 
		});
	}
	return false;
  }
});
</script>
<%Op.Include("../../sys/foot.asp")%>