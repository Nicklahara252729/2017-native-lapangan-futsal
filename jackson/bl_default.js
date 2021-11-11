
$(function(){
    $(window).scroll(function(){
        if($(window).scrollTop()>100){
            $('.top').css({
                
 height:'40px',
                opacity:'1',
            });
            $('.navbar').css({
                position:'fixed',
                boxShadow:'0px 0px 3px 0px lightgray',                
            });
        }else{
            $('.top').css({
                
                height:'0px',
                opacity:'0',
            });
            $('.navbar').css({
                position:'relative',
                boxShadow:'0px 0px 0px 0px',
            });
        }
    });
    
    $('.top').click(function(){
        $('body,html').animate({
            scrollTop:'0',
        },1000);
    });
});

$(function(){
    $('.btn-register').click(function(){
        $('.register').css({
            display:'block',
        });
        $('body').css({
            overflow:'hidden',
        });
    });
    $('.btn-login').click(function(){
        $('.login').css({
            display:'block',
        });
        $('body').css({
            overflow:'hidden',
        });
    });
});
