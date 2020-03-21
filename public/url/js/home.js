$(function() {
  // 下拉列表
  $(".erp-dropdown,.user-box").hover(function() {
    $(this).addClass("open");
  }, function() {
    $(this).removeClass("open");
  })

  // 设置高度
  function resetHeight() {
    var h = document.documentElement.clientHeight || document.body.clientHeight;
    var w = document.documentElement.clientWidth || document.body.clientWidth;
    var h1 = h - 82,
        w1 = w - 200;
    $(".container").height(h1);
    $(".side-nav").height(h1);
    $(".workspace").width(w1);
    $(".slimScrollDiv").height(h1);
  }
  resetHeight();
  var _h = $(".container").height();

  // 添加滚动条
  $(".side-nav").slimScroll({
    height: _h,
    railVisible: true
  });

  window.onresize = function() {
    navChange("#J_mainNav", 1000);
    resetHeight();
  }
  navChange("#J_mainNav", 1000);
  var h = document.documentElement.clientHeight || document.body.clientHeight;
  console.log(h);

})

//导航栏显示隐藏
function navChange(id, _width) { //[id获取节点导航节点,   _width页面可视宽度] 
  var j = 0,
    id = $(id),
    navList = id.find('.nav-item'),
    more = navList.eq(navList.length - 1),
    w = document.documentElement.clientWidth || document.body.clientWidth,
    Lw = 200,
    Lr = 130,
    ListWidth = 0,
    ListMOre = more.find('.dropdown-menu'),
    scrollL = 0,
    arr = [];
  for (var i = 0; i < navList.length - 1; i++) {
    ListWidth += navList.eq(i).width();
    if (w > _width) {
      if (ListWidth < (w - Lw - Lr - more.width())) {
        navList.eq(i).show();
        more.hide();
        j++;
      }
      if (ListWidth > (w - Lw - Lr - more.width())) {
        navList.eq(i).hide();
        more.show();
      }
    }

    if (w < _width) {
      if (ListWidth < (_width - Lw - Lr - more.width())) {
        navList.eq(i).show();
        more.show();
        j++;
      }


    }

  };

  for (var i = j; i < (navList.length - 1); i++) {
    if (w < _width) {
      navList.eq(i).hide();
    }
    arr.push(navList.eq(i).html());
  };


  //鼠标移入移出
  more.hover(function() {
    $(this).addClass("open");
  }, function() {
    $(this).removeClass("open");
  });
  //更多动态添加
  ListMOre.empty();
  for (var i = 0; i < arr.length; i++) {
    ListMOre.append('<li>' + arr[i] + '</li>');
  }

  if (w < _width) {
    document.body.style.overflowX = 'scroll';
    document.body.style.overflowY = 'hidden';
    $('.header').css('width', _width);
  } else if (w > _width) {
    document.body.style.overflowX = 'hidden';
    document.body.style.overflowY = 'hidden';
    $('.header').css('width', '100%');
  }
}