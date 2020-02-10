<?php

foreach ($categories as $cat) {

if(!empty(auth()->user())){

    if(is_array($cat->user_type)){
        if(in_array(auth()->user()->user_type_id,$cat->user_type) ){

        }
    }else{
        if($cat->user_type == auth()->user()->user_type_id){

        }
    }

}else{
    if(is_array($cat->user_type)){
        if(!empty(in_array( 2 ,$cat->user_type))?in_array( 2 ,$cat->user_type):'' ){

        }
    }else{

        if($cat->user_type == '2'){

        }
    }
}
}