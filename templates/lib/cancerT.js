//当前页面导航变色
$(document).ready(function(){
	var title = $('title').text();
	$("#nav-ui a").removeClass("nav-color");
	$("#nav-ui a").each(function(){		
		if($(this).text()===title){
			$(this).addClass("nav-color").css("color", "white");
		}		
	});
});

//选项卡
window.onload = function(){
var myTab = document.getElementById("content-tabi");    //整个div
var myUl = myTab.getElementsByTagName("ul")[0];//一个节点
var myLi = myUl.getElementsByTagName("li");    //数组
var myDiv = myTab.getElementsByClassName("content-tabs"); //数组
for(var i = 0; i<myLi.length;i++){
	myLi[i].index = i;
	myLi[i].onclick = function(){
		for(var j = 0; j < myLi.length; j++){
			myLi[j].className="off";
			myDiv[j].className = "content-tabs hide";
		}
		this.className = "on";
		myDiv[this.index].className = "content-tabs";
	}
  }
}

