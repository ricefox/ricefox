/**
 * Created by wen on 2016/4/4.
 */
$(window).on('load',function()
{
    if(typeof window.cityOptions==='string'){
        window.cityOptions=$.parseJSON(window.cityOptions);
    }
});
$(function()
{
    var $container=$('#cityContainer');
    var $dropdowns=$container.find('>.dropdown');
    var $toggles=$dropdowns.find('.dropdown-toggle'),$panels=$dropdowns.find('.dropdown-panel');
    var $cityToggle=$toggles.filter('#city');
    $toggles.each(function()
    {
        var $this=$(this);
        var $panel=$this.parent().find('.dropdown-panel');
        //var offset=$this.offset();
        //$panel.css('left',offset.left);
        $this.data('panel',$panel);
    });
    function showPanel($panel)
    {
        $panel.parent().addClass('open');
        $panel.removeClass('hidden');
        hidePanel($panels.not($panel));
    }
    function hidePanel($panel)
    {
        $panel.parent().removeClass('open');
        $panel.addClass('hidden');
    }
    $toggles.on('click',function(event)
    {
        event.preventDefault();
        event.stopPropagation();
        //console.log(event);
        var $this=$(this);
        var $parent=$this.parent();
        var $panel=$parent.find('> .dropdown-panel');
        if($parent.hasClass('open')){
            hidePanel($panel);
        }else{
            if($panel.children().length>0){
                showPanel($panel);
            }
        }
    });

    var $tabItems=$container.find('.tab-item');
    $tabItems.eq(1).addClass('active');
    var $cityPanel=$container.find('#cityPanel');
    $tabItems.on('click',function()
    {
        var $this=$(this);
        var key=$this.find('a').attr('id2');
        if($cityPanel.find('#data'+key).length<=0){
            var url=window.cityOptions['getCityUrl'];
            $.get(url,{key:key},function(json)
            {
                if(json['status']){
                    $cityPanel.append(json['html']);
                    toggle();
                }
            },'json');
        }else{
            toggle();
        }
        function toggle()
        {
            $cityPanel.find('.panel-content').addClass('hidden');
            $cityPanel.find('#data'+key).removeClass('hidden');
            $tabItems.removeClass('active');
            $this.addClass('active');
        }
    });
    var areaData={},$areaPanel=$panels.filter('#areaPanel'),$areaToggle=$toggles.filter('#area');
    $cityPanel.on('click','a.item',function()
    {
        var $this=$(this),id=$this.attr('id2'),text=$this.text();
        $cityToggle.val(text).attr('value2',id);
        $areaToggle.val('').attr('value2','');
        $quanToggle.val('').attr('value2','');
        hidePanel($cityPanel);
        if(areaData[id]===undefined){
            var url=window.cityOptions['getAreaUrl'];
            $.get(url,{key:id},function(json)
            {
                if(json['status']){
                    if(json['html']){
                        areaData[id]=json['html'];
                    }else{
                        areaData[id]='';
                    }
                    toggle();


                }
            },'json');
        }else{
            toggle();
        }
        function toggle()
        {
            if(areaData[id]){
                $areaPanel.empty().append(areaData[id]);
                showPanel($areaPanel);
            }else{
                $areaPanel.empty();

            }
        }
    });
    var $quanToggle=$toggles.filter('#quan'),quanData={},$quanPanel=$panels.filter('#quanPanel');
    $areaPanel.on('click','a.item',function()
    {
        var $this=$(this),id=$this.attr('id2'),text=$this.text();
        $areaToggle.val(text).attr('value2',id);
        $quanToggle.val('').attr('value2','');
        hidePanel($areaPanel);
        if(quanData[id]===undefined){
            var url=window.cityOptions['getQuanUrl'];
            $.get(url,{key:id},function(json)
            {
                if(json['status']){
                    if(json['html']){
                        quanData[id]=json['html'];
                    }else{
                        quanData[id]='';
                    }
                    toggle();
                }
            },'json');
        }else{
            toggle();
        }
        function toggle()
        {
            if(quanData[id]){
                $quanPanel.empty().append(quanData[id]);
                showPanel($quanPanel);
            }else{
                $quanPanel.empty();
            }
        }
    });
    $quanPanel.on('click','a.item',function()
    {
        var $this=$(this),id=$this.attr('id2'),text=$this.text();
        $quanToggle.val(text).attr('value2',id);
        hidePanel($quanPanel);
    });
    $($cityToggle[0].form).on('submit',function()
    {

    });
});