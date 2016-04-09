/**
 * Created by wen on 2016/4/4.
 */
(function($,undefined)
{
    var options=window.welfareOptions;
    var selected={length:0};
    $('#welfare').multiselect({
        buttonWidth:options['buttonWidth'] || options['width'] || null,
        ulWidth:options['ulWidth'] || options['width'] || null,
        nonSelectedText:options['nonSelectedText'],
        maxSelected:options['maxSelected'] || 5,
        cols:options['cols'],
        numberDisplayed:options['maxSelected'] || 5,
        enableFiltering:true,
        enableCaseInsensitiveFiltering:true,
        filterPlaceholder:'搜索',
        selectedClass:'selected',
        beforeChange:function($input,event)
        {
            var checked=$input.prop('checked') || false;
            if(checked){
                selected.length++;
                if(selected.length>this.options.maxSelected){
                    selected.length--;

                    alert('你最多只能选择 '+this.options.maxSelected+' 项');
                    $input.prop('checked',false);
                    return false;
                }
            }else{
                selected.length--;
                return true;
            }
        },
        buttonClass:function()
        {
            return 'btn btn-default multiselect-btn ';
        }
    });
})(jQuery);