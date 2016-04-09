/**
 * Created by wen on 2016/4/3.
 */
(function($,undefined)
{

    function TagPanel(options,root)
    {
        this.$root=root;
        $.extend(this,options);
    }
    TagPanel.prototype={
        data:{},
        template:{
            panelContainer:'<ul>',
            panelItem:'<li>',
            tagContainer:'<div class="col-block">'

        },
        render:function()
        {
            var self=this;
            self.renderPanel();
        },
        renderPanel:function()
        {
            var self=this,data=self.data['panel'];
            if(data!==undefined){
                var $ul=$('<ul>');
                $.each(data,function(key,value)
                {
                    var $li=$('<li data-value="'+key+'">'+value+'</li>');
                    $ul.append($li);
                });
                self.$root.append($ul);
            }
        },
        renderTags:function()
        {
            var self=this,tags=self.data['tags'],groups=self.data['groups'];
            if(tags){
                $.each(tags,function(key,items)
                {
                    //var $group=$('<ul data-value="'+groups[key]+'">'+);
                })
            }
        }
    };
    $.fn.tagPanel=function(options)
    {
        var instance=this.data('tagPanel_instance');
        if(!instance){
            this.data('tagPanel_instance',new TagPanel(options,this));
        }
    }
})(jQuery);

