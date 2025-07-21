$(function(){
    function loadImages(page){
        $.ajax({
            url: '/controller/aboutus_images.php',
            type: 'GET',
            dataType: 'json',
            data: {page: page},
            success: function(res){
                if(res && res.html){
                    $('#aboutusImageList').html(res.html);
                    buildPageList(res.total_page, page);
                }
            }
        });
    }

    function buildPageList(total, current){
        var $list = $('.number_list_con .list_con');
        $list.empty();
        for(var i=1;i<=total;i++){
            var num = i < 10 ? '0'+i : i;
            var cls = 'list_a';
            if(i === parseInt(current)) cls += ' on';
            $('<a>',{ 'class':cls,'data-page':i,text:num }).appendTo($list);
        }
        $('<div>',{ 'class':'bar'}).appendTo($list);
    }

    $(document).on('click','.number_list_con [data-page]',function(e){
        e.preventDefault();
        var page = $(this).data('page');
        if(page === 'prev' || page === 'next' || page === 'first' || page === 'last'){
            var current = parseInt($('.number_list_con .list_a.on').data('page')) || 1;
            if(page === 'prev' && current > 1) page = current - 1;
            if(page === 'next' && current < $('.number_list_con .list_a').length) page = current + 1;
            if(page === 'first') page = 1;
            if(page === 'last') page = $('.number_list_con .list_a').length;
        }
        loadImages(page);
    });

    var first = $('.number_list_con [data-page]:not([data-page="prev"]):not([data-page="next"]):not([data-page="first"]):not([data-page="last"]').first().data('page') || 1;
    loadImages(first);
});
