<?php
  class FormState
  {
    public function __construct()
    {
    }
    
    public function generateScript()
    {
      $postPopulate = '<script type="text/javascript">';
      $postPopulate .= '
      $(document).ready(function(){'."\n";
      
      foreach ($_POST as $key=>$value)
      {
        $value = str_replace("\r\n", '\n', $value);
        $postPopulate .='
          $("form *[name=\''.$key.'\'] ").attr("value", "'.$value.'");
          $("form *[name=\''.$key.'\']").filter("input[type=\'checkbox\']").attr("checked","checked");
          $("form *[name=\''.$key.'\']").filter("input[type=\'radio\']").attr("checked","checked")
        ';
        if( is_array($value) )
        {
          foreach ($value as $subKey=>$subValue)
          {
            $postPopulate .='$("form *[name=\''.$key.'[]\']").filter("input[type=\'radio\']").filter("input[value=\''.$subValue.'\']").attr("checked","checked") '.";\n";
          }
        }
      }
      
      $scrollPos = isset( $_POST['scrollPosition'] )? $_POST['scrollPosition'] : 0;
      
      $postPopulate .= '
        var hiddenField = document.createElement("input");
        hiddenField.type="hidden";
        hiddenField.name="scrollPosition";
        hiddenField.value="'.$scrollPos.'";

        $("form").append(hiddenField);
        
        
        $(window).load(function(){
          $(document).scrollTop("'.$scrollPos.'");
        });
        
        $(document).scroll(function(){
          $("*[name=\'scrollPosition\']").attr("value",$(document).scrollTop());
        });
      });
      </script>';
      
      return $postPopulate;
    }
  }
?>
