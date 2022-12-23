<?php
namespace helpers\redirect;
 

class redirect
   {

        public static function  to($location)
        { 
            if (!headers_sent()) {
                @session_start();
                header("Location: " . URL . $location);
            } else {

                @session_start();
                echo '<script type="text/javascript">';
                echo 'window.location.href="' . URL . $location . '";';
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url=' . URL . $location . '" />';
                echo '</noscript>';
            }



            exit();
        }

        public static function  back()
        {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
