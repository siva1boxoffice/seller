<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

 

function format_price($price)
{
        if(fmod($price, 1) !== 0.00){
            return number_format((float)$price, 2);
        } else {
            return number_format((float)$price, 2);
            //return round($price);
        }
         
    }
