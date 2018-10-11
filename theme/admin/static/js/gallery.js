$(function(){
    var el = {
        list    : $('#media-list'),
        input   : $('#field-images'),
        uploader: $('#btn-uploader')
    };

    var images = JSON.parse(el.input.val() || '[]');

    var timer = null;

    el.input.change(function(){
        el.list.html('');
        for(var i=0; i<images.length; i++){
            var img = images[i];
            var path = img.path.replace(/(\.[a-zA-Z]+)$/, '_260x260$1');
            var tx = '<div class="col-md-4 thumb">'
                   +    '<div class="thumbnail" href="#">'
                   +        '<img class="img-responsive" src="'+path+'" alt="#">'
                   +        '<div class="caption">'
                   +            '<abbr title="Click to edit" class="image-title" data-index="'+i+'" data-text="'+img.title+'">'
                   +                (img.title?img.title:'<em>Edit title</em>')
                   +            '</abbr>'
                   +        '</div>'
                   +    '</div>'
                   + '</div>';
            el.list.append(tx);
        }
    });

    el.input.change();

    el.uploader.click(function(){
        Media.pick({
            form: 'admin-gallery-image.image',
            multiple: true,
            mime: 'image/*'
        }, function(res){
            images.push({path: res, title: ''});
            el.input.val(JSON.stringify(images));
            if(timer)
                clearTimeout(timer);
            timer = setTimeout(function(){ el.input.change(); }, 1000);
        });
    });

    el.list.on('click', '.image-title', function(){
        var $el = $(this);
        var idx = $el.data('index');
        bootbox.prompt('Please set image title', function(val){
            images[idx].title = val;
            el.input.val(JSON.stringify(images));
            el.input.change();
        });
    })
})