/***************
*�жϷ�������Ӧ���¼������������4��˵��������ɣ��жϱ�ʾͷ��
*url������'&n='+Math.random() ���Է�ֹ����
strMessage �������ʾ��Ϣ
statueMessage  δ��������ʾ����Ϣ
*************************************************/
function queryCity(url,DivId,strMessage,statueMessage){
	window.httpObj = createXMLHTTPObject();
    window.httpObj.open('GET', url , true);
	
	window.httpObj.onreadystatechange=function(){
			if(window.httpObj.readyState==4){//4˵����ִ�н������0 (δ��ʼ��)1 (����װ��)2 (װ�����) 3 (������)4 (���)
				if(window.httpObj.status==200){//http��һ����ͷ˵���ɹ��ҵ�
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
