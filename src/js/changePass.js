(function(){
  let changeBtn = document.getElementById('changePassword');
  let changeTarget1 = document.getElementById('changeTarget1');
  let changeTarget2 = document.getElementById('changeTarget2');

  changeBtn.addEventListener('click',function(){

    let targetType1 = changeTarget1.getAttribute('type');
    let targetType2 = changeTarget2.getAttribute('type');
    
    if(targetType1 == 'password' && targetType2 == 'password'){
      changeTarget1.setAttribute('type','text');
      changeTarget2.setAttribute('type','text');
    }else{
      changeTarget1.setAttribute('type','password');
      changeTarget2.setAttribute('type','password');
    }
  })
})()
