<?php

namespace Wepesi\App;
    class appException{
        function exception($ex){
            return ["exception"=>$ex->getMessage()];
        }
    }