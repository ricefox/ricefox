/**
 * Created by wen on 2016/4/3.
 */
$(function()
{
    var options=window.panelOptions,targetId=options['targetId'],
        maxSelected=options['maxSelected'], separator=options['separator'],
        selected={length:0},panelItem={};
    var $panel=$('#panel');
    var $panelShow=$panel.find('#panelShow');
    var $panelItem=$panel.find('.panel-item');
    $panelItem.on('click',function(e)
    {
        e.preventDefault();
        var $this=$(this);
        $panelItem.removeClass('active');
        $this.addClass('active');
        var href=$this.attr('href');
        if($this.attr('loaded')===undefined){
            $panel.find(href).appendTo($panelShow);
            $this.attr('loaded',1);
        }
        $panelShow.find('.sub-item').addClass('hidden');
        $panelShow.find(href).removeClass('hidden');

    });
    $panelItem.first().trigger('click');
    $panel.css('position','absolute');
    var $target=$('#'+targetId);
    $target.after($panel);
    $target.prop('readonly',true).css('cursor','text').css('background-color','white');
    var offset=$target.offset();
    $panel.css('left',offset.left);
    $target.on('click',function()
    {
        $panel.toggleClass('hidden');
        if($panel.hasClass('hidden')){
            $target.trigger('blur');
        }
    });

    $panel.on('click','.job-item',function()
    {
        var $this=$(this),id=$this.attr('data-value'),text=$this.text();
        var panelId='panel'+$this.closest('.sub-item').attr('id').slice(3);
        panelItem[panelId]=panelItem[panelId] || {0:$panelItem.filter('#'+panelId),length:0};
        if($this.hasClass('active')){
            $this.removeClass('active');
            delete selected[id];
            selected['length']--;
            var all=getAll();
            $target.attr('data-id',all[0]);
            $target.val(all[1]);
            panelItem[panelId]['length']--;
        }else{
            if(selected['length']>maxSelected-1){
                alert('你最多只能选择 '+maxSelected+' 项');
                return false;
            }else{
                selected['length']++;
                selected[id]=text;
                all=getAll();
                $target.attr('data-id',all[0]);
                $target.val(all[1]);
                $this.addClass('active');
                panelItem[panelId]['length']++;
            }
        }
        $.each(panelItem,function(key,panel)
        {
            if(panel['length']>0){
                panel[0].addClass('selected');
            }else{
                panel[0].removeClass('selected');
            }
        });
        if(selected['length']===maxSelected && maxSelected===1){
            $target.trigger('click');
        }
    });
    $($target[0].form).on('submit',function()
    {
        var id=$target.attr('data-id');
        $(this).find('#jobId').remove();
        $('<input type="hidden" name="jobId" id="jobId" />').val(id).prependTo(this);
    });
    function getAll()
    {
        var allText='',allId='';
        //console.log(selected);
        for(var key in selected){
            var value=selected[key];
            if(Number(key)>0){
                allText+=value+separator;
                allId+=key+',';
            }
        }
        allId=allId.slice(0,-1);
        allText=allText.slice(0,-separator.length);
        return [allId,allText];
    }
});