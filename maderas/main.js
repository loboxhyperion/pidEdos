const menu = document.getElementById('#menu');

window.addEventListener('scroll', ()=>{
    var scroll = window.scrollY;
    if(scroll>10){
        menu.style.backgroundColor = '#121212';
    }else{
        menu.style.backgroundColor ='transparent';
    }
});
