(function($,undefined)
{
    var CascadeItemOptions={
        tpl:'<div class="col-block hidden"></div>',
        container:null,
        emptyShow:false,
        hasData:null,
        data:{}
    };
    function CascadeItem(options,cascade)
    {
        this.ele=$(options['selector'],cascade.ele);
        this.ele.data('cascadeItem_instance',this);
        this.options=$.extend({},CascadeItemOptions,options);
        var container=this.options.container===null?this.options.selector:this.options.container;
        this.container=this.ele.closest(container).wrap(this.options.tpl).parent();
        this.requires=[];
        this.requiresOr=[];
        this.data=this.options.data || {};
    }
    CascadeItem.prototype={
        constructor:CascadeItem,
        // 设置requires项的实例
        init:function(items)
        {
            var requires=this.options.requires,self=this,requiresOr=this.options.requiresOr;
            if($.isArray(requires)){
                $.each(requires,function(index,require)
                {
                    if(items[require])self.requires.push(items[require]);
                });
            }else if(typeof requires==='string'){
                if(items[requires])self.requires.push(items[requires]);
            }
            if($.isArray(requiresOr)){
                $.each(requiresOr,function(index,require)
                {
                    var nReq={},hasValue=require['hasValue'],hasData=require['hasData'],noValue=require['noValue'],
                        noData=require['noData'];
                    $.each({hasValue:hasValue,hasData:hasData,noValue:noValue,noData:noData},function(key,arr)
                    {
                        if($.isArray(arr)){
                            nReq[key]=[];
                            $.each(arr,function(index,req)
                            {
                                if(items[req])nReq[key].push(items[req]);
                            });
                        }
                    });
                    if(!$.isEmptyObject(nReq))self.requiresOr.push(nReq);
                });
            }
        },
        replaceSelectOption:function(data,placeholder)
        {
            var self=this;
            var option='';
            if(placeholder)option+='<option value>'+placeholder+'</option>';
            $.each(data,function(index,value)
            {
                option+='<option value="'+index+'">'+value+'</div>';
            });
            self.ele.empty().append(option);
        },
        replaceEle:function(ele)
        {
            this.ele.replaceWith(ele);
        },
        run:function()
        {

            var self=this;
            //console.log(self);
            if(self.isRequireCond()){
                self.container.removeClass('hidden');
            }else{
                self.container.addClass('hidden');
            }
        },
        getValue:function()
        {
            return this.ele.val();
        },
        // 判断是否含有数据。
        hasData:function()
        {
            var self=this,hasData=self.options.hasData;
            if(hasData===true)return true;
            if($.isFunction(hasData)){
                return hasData.call(self);
            } else {
                if(hasData==='select' || self.ele[0].nodeName.toLowerCase()==='select'){
                    var state;
                    self.ele.find('option').each(function()
                    {
                        if(!self.isEmpty(this.value)){
                            state=true;
                            return false;
                        }
                    });
                    return state===true;
                }
            }
            return false;
        },
        // 判断值是否为空
        isEmpty:function(value)
        {
            value= $.trim(value);
            var num=Number(value);
            if(typeof num==='number'){
                return !num;
            }else{
                return !value;
            }
        },
        isRequireCond:function()
        {
            var self=this;
            if(self.options.emptyShow===false && !self.hasData())return false;
            if(self.requires.length<=0 && self.requiresOr.length<=0)return true;
            if(self.requires.length>0){
                var states=$.map(self.requires,function(require)
                {
                    return !self.isEmpty(require.getValue()) ? true : null;
                });
                return states.length===self.requires.length;
            }
            if(self.requiresOr.length>0){
                var state=false;
                $.each(self.requiresOr,function(index,requireObj)
                {
                    var hasValue=requireObj['hasValue'],hasData=requireObj['hasData'],
                        noValue=requireObj['noValue'],noData=requireObj['noData'],map;
                    if(hasValue){
                        map=$.map(hasValue,function(item)
                        {
                            return !self.isEmpty(item.getValue()) ? true : null;
                        });
                        hasValue=map.length===hasValue.length;
                    }else{
                        hasValue=true;
                    }
                    if(hasData){
                        map=$.map(hasData,function(item)
                        {
                            return item.hasData() ? true : null;
                        });
                        hasData=map.length===hasData.length;
                    }else{
                        hasData=true;
                    }
                    if(noValue){
                        map=$.map(noValue,function(item)
                        {
                            return self.isEmpty(item.getValue()) ? true : null;
                        });
                        noValue=map.length===noValue.length;
                    }else{
                        noValue=true;
                    }
                    if(noData){
                        map=$.map(noData,function(item)
                        {
                            return !item.hasData() ? true : null;
                        });
                        noData=map.length===noData.length;
                    }else{
                        noData=true;
                    }
                    if(hasValue && hasData && noValue && noData){
                        state=true;
                        return false;
                    }
                });
                return state;
            }
            return false;
        },
        onChange:function(event,cascade)
        {
            var self=this;
            if($.isFunction(this.options.onChange)){

                this.options.onChange.call(this,event,cascade,function()
                {
                    if(self.options.depended!==undefined){
                        var depended=self.options.depended;
                        if(typeof depended==='string'){
                            cascade.items[depended].run();
                        }else if($.isArray(depended)){
                            $.each(depended,function(index,dep)
                            {
                                cascade.items[dep].run();
                            });
                        }
                    }
                });
            }
        }
    };

    function Cascade(ele,options)
    {
        this.ele=ele;
        this.options= $.extend({},this.defaultOptions,options);
        this._init();
    }

    Cascade.prototype={
        defaultOptions:{},
        items:{},
        _init:function()
        {
            var self=this;
            $.each(self.options.items,function(index,item)
            {
                if(index===0 && item['emptyShow']===undefined){
                    item['emptyShow']=true;
                }
                self.items[item['selector']]=new CascadeItem(item,self);
            });
            $.each(self.items,function(key,item)
            {
                item.init(self.items);
                self.ele.on('change',item.options['selector'],function()
                {
                    item.onChange.call(item,event,self);
                });
            });
            self.run();
        },
        run:function()
        {
            var items=this.items,self=this;
            $.each(items,function(key,item)
            {
                item.run();
            });
        },
        onChange:function(event)
        {
            this.run(event);
        }
    };
    $.fn.cascade=function(options)
    {
        var self=this,
            instance=self.data('cascade_instance');

        if((typeof options==='object' || options===undefined) && !instance){
            self.data('cascade_instance',new Cascade(self,options));
        }else if(typeof options==='string' && instance[options]){
            instance[options].apply(instance,Array.prototype.slice.call(arguments,1));
        }
    };
})(jQuery);
/**
 * 示例
 $('.district').cascade({
    items:[
    {
        selector:'#provinceId',
        container:'.form-inline',
        depended:['#cityId','#areaId',"#addressId"],
        onChange:function(event,cascade,callback)
        {
            var item=this;
            var value=item.getValue();
            if(value){
                if(item.data[value]){
                    var data=item.data[value];
                    cascade.items['#cityId'].replaceEle(data);
                    callback && callback();
                    return true;
                }
                $.post("$url",{provinceId:value},function(json)
                {
                    if(json['status']===true){
                        cascade.items['#cityId'].replaceSelectOption(json['city'],'请选择');
                        item.data[value]=cascade.items['#cityId'].ele;
                        $.each(json['area'],function(index,area)
                        {
                            cascade.items['#cityId'].data[index]=area;
                        });
                        callback && callback();
                    }
                },'json');
            }else{
                callback && callback();
            }
        }
    },
    {
        selector:'#cityId',
        container:'.form-inline',
        requires:'#provinceId',
        depended:['#areaId','#addressId'],
        onChange:function(event,cascade,callback)
        {
            var item=this;
            var value=item.getValue();
            if(value){
                if(item.data[value]){
                    var data=item.data[value];
                    cascade.items['#areaId'].replaceSelectOption(data,'请选择');
                    callback && callback();
                }
            }else{

                callback && callback();
            }
        }
    },
    {
        selector:'#areaId',
        container:'.form-inline',
        requires:['#provinceId','#cityId'],
        depended:'#addressId',
        onChange:function(event,cascade,callback)
        {
            callback && callback();
        }
    },
    {
        selector:'#addressId',
        container:'.form-inline',
        requiresOr:[{hasValue:['#provinceId','#cityId'],noData:["#areaId"]},{hasValue:['#provinceId','#cityId','#areaId']}],
        hasData:true
    }
    ]
})
 */
