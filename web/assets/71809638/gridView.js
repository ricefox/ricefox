// 提交
function gridFormSubmit(e)
{
    var data= e.data;
    var keys=$('#'+data['gridId']).yiiGridView('getSelectedRows');
    if(keys.length<=0){
        console.log(e.target);
        alert('请先选中数据');
        return false;
    }
}

$(function()
{
    // grid表单的提交操作。
    $('.grid-footer-item').each(function()
    {
        var $button=$(this).find('button');
        if($button.length>0){
            $button.on('click',function()
            {
                var action=$button.attr('data-action');
                if(action)this.form.action=action;
                if($button.hasClass('delete')){
                    var confirm=window.confirm('你确删除选中的数据吗？');
                    if(!confirm)return false;
                }
            });
        }
    });
    //设置grid显示的行数
    $('#gridShowRows').on('change',function()
    {
        Cookies.set('gridShowRows',this.value);
        window.location.reload();
    });
}());