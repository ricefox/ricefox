/**
 * Created by wen on 2016/3/19.
 */

function helpStyle($form)
{

    $form.find('.form-inline').each(function()
    {
        var $label=$(this).find('.control-label');

        var $help=$(this).find('.help-block');
        if($label.length>0 && $help.length>0){
            $help.css('margin-left',$label.outerWidth()+2);
        }
    });
}
