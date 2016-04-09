ue.ready(function()
{
    var content=document.getElementById('content').value;
    if(content){
        this.setContent(content);
    }
});

(function()
{
    var $content=$('#content');
    var $form=$('#myform');
    var html;
    var submitState;
    $form.on('click','input[type="submit"]',function()
    {
        //this.form.action="javascript:void(0)";
        if(submitState===true) return true;
        html=ue.getContent();
        var $btn=$(this);
        var $html=$('<div>'+html+'</div>');
        if($html.find('pre').length>0)
        {
            doShhandle(html,$btn);
            return false;
        }
        else
        {
            $content.val(html);
            $form.find('#hascode').val(0);
            return true;
        }
    });
    function doShhandle(html,$btn)
    {
        var url='/index.php?m=content&c=code&a=shhandle&pc_hash='+pc_hash;
        $.ajax(url,
        {
            data:{content:html},
            async:false,
            type:'POST',
            dataType:'json',
            success:function(json)
            {
                if(json.status)
                {
                    $('#sh_handle').remove();
                    var $frame=$('<iframe id="sh_handle" src="'+JS_PATH+'sh/sh.html" style="position: absolute;left: -9999px;"></iframe>');
                    $('body').append($frame);
                    $frame.on('load',function()
                    {
                        var doc=$frame[0].contentWindow.document || $frame[0].contentDocument;
                        var content=doc.getElementById('shhandle_content');
                        var html=content.innerHTML;
                        $form.find('#hascode').val(1);
                        var $html=$('<div>'+html+'</div>');
                        //var gcpHandle;
                        // 这里使用google prettify 进行代码美化。
                        if($html.find('pre').length>0){
                            $html.find('pre').addClass('prettyprint');
                            $('#gcp_handle').remove();
                            var url2='/index.php?m=content&c=code&a=gcphandle&pc_hash='+pc_hash;
                            $.ajax(url2,{
                                data:{content:$html.html()},
                                async:false,
                                type:'POST',
                                dataType:'json',
                                success:function(json)
                                {
                                    if(json['status']){
                                        var $frame2=$('<iframe id="gcp_handle" src="'+JS_PATH+'gcp/gcp.html" style="position: absolute;left: -9999px;"></iframe>');
                                        $('body').append($frame2);
                                        $frame2.on('load',function(){
                                            var doc=$frame2[0].contentWindow.document || $frame2[0].contentDocument;
                                            var content=doc.getElementById('gcphandle_content');
                                            var html=content.innerHTML;
                                            $form.find('#mecontent').html(html);
                                            submitState=true;
                                            setTimeout(function()
                                            {
                                                $btn.trigger('click');
                                            },10);
                                        });
                                    }
                                }
                            });

                        }else{
                            $form.find('#mecontent').html(html);
                            submitState=true;
                            setTimeout(function()
                            {
                                $btn.trigger('click');
                            },10);
                        }
                    });
                }
            }
        });
    }

}());