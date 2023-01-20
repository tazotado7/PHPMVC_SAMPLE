<?php
namespace helpers\redirect;
 

class redirect
   {
    //ჩვენს საიტზე სად გვსურს გადავამისამართოთ, URL -> კონფიგ ფაილშუ უკვე ჩვენი საიტი გაწერილია.
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

        // მომხმარებლის წინა გადმომისამართებული გვერძე დაბრუნება
        public static function  back()
        {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
