<?php
class ViewFactory
{
    public static function createView($contr,$file)
    {
        include_once('library/Crax/view/view.class.php');
        return new View($contr,$file);
    }
    
    public static function createLayout($contr)
    {
        include_once('library/Crax/view/layout.class.php');
        return new Layout($contr);
    }
}
?>