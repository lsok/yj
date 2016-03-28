/***************
*判断服务器响应的事件，如果返回是4则说明交互完成，判断标示头，
*url最后参数'&n='+Math.random() 可以防止缓存
strMessage 出错的显示消息
statueMessage  未加载完显示的信息
*************************************************/
function queryCity(url,DivId,strMessage,statueMessage){
	window.httpObj = createXMLHTTPObject();
    window.httpObj.open('GET', url , true);
	
	window.httpObj.onreadystatechange=function(){
			if(window.httpObj.readyState==4){//4说明是执行交互完毕0 (未初始化)1 (正在装载)2 (装载完毕) 3 (交互中)4 (完成)
				if(window.httpObj.status==200){//http的一个报头说明成功找到
                  document.getElementById(DivId).innerHTML=window.httpObj.responseText;
				}else{
					alert(strMessage);
				}
			}
			else
			{
				document.getElementById(DivId).innerHTML=statueMessage;	
			}
		}
  window.httpObj.send(null)
}
