;!function (win) {
	"use strict";
	var doc = document

	,Xadmin = function(){
	    this.v = '2.2'; //版本号
	}

	/**
	 * [open 打开弹出层]
	 * @param  {[type]}  title [弹出层标题]
	 * @param  {[type]}  url   [弹出层地址]
	 * @param  {[type]}  w     [宽]
	 * @param  {[type]}  h     [高]
	 * @param  {Boolean} full  [全屏]
	 * @return {[type]}        [description]
	 */
	Xadmin.prototype.open = function (title,url,w,h,full) {
		if (title == null || title == '') {
	        var title=false;
	    };
	    if (url == null || url == '') {
	        var url="404.html";
	    };
	    if (w == null || w == '') {
	        var w=($(window).width()*0.9);
	    };
	    if (h == null || h == '') {
	        var h=($(window).height() - 50);
	    };
	    var index = layer.open({
	        type: 2,
	        area: [w+'px', h +'px'],
	        fix: false, //不固定
	        maxmin: true,
	        shadeClose: true,
	        shade:0.4,
	        title: title,
	        content: url
	    });
	    if(full){
	       layer.full(index);
	    }
	}
	/**
	 * [close 关闭弹出层]
	 * @return {[type]} [description]
	 */
	Xadmin.prototype.close = function() {
		var index = parent.layer.getFrameIndex(window.name);
    	parent.layer.close(index);
	};
  /**
   * [close 关闭弹出层父窗口关闭]
   * @return {[type]} [description]
   */
  Xadmin.prototype.father_reload = function() {
      parent.location.reload();
  };
	/**
	 * [get_data 获取所有项]
	 * @return {[type]} [description]
	 */
	Xadmin.prototype.get_data = function () {
    if(typeof is_remember!="undefined")
          return false;
		return layui.data('tab_list')
	}
	/**
	 * [set_data 增加某一项]
	 * @param {[type]} id [description]
	 */
	Xadmin.prototype.set_data = function(title,url,id) {

		if(typeof is_remember!="undefined")
        	return false;

		layui.data('tab_list', {
		  key: id
		  ,value: {title:title,url:url}
		});
	};

  /**
   * [get_data 获取所有项]
   * @return {[type]} [description]
   */
  Xadmin.prototype.get_cate_data = function () {
    if(typeof is_remember!="undefined")
          return false;
    return layui.data('cate')
  }
  /**
   * [set_data 增加某一项]
   * @param {[type]} id [description]
   */
  Xadmin.prototype.set_cate_data = function(data) {

    if(typeof is_remember!="undefined")
          return false;

    layui.data('cate', data);
  };
	/**
	 * [del_data 删除某一项]
	 * @param  {[type]} id [description]
	 * @return {[type]}    [description]
	 */
	Xadmin.prototype.del_data = function(id) {
		if(typeof is_remember!="undefined")
        	return false;
		if(typeof id!="undefined"){
			layui.data('tab_list', {
			  key: id
			  ,remove: true
			});
		}else{
			layui.data('tab_list',null);
		}
	};
	/**
	 * [del_other_data 删除其它]
	 * @param  {[type]} id [description]
	 * @return {[type]}    [description]
	 */
	Xadmin.prototype.del_other_data = function(id) {
		if(typeof is_remember!="undefined")
        	return false;
		var tab_list = this.get_data();

		layui.data('tab_list',null);

		layui.data('tab_list', {
		  key: id
		  ,value: tab_list[id]
		});
	};
	win.xadmin = new Xadmin();

}(window);
