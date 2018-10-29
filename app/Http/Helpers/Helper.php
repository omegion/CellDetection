<?php

if( ! function_exists('unique_slug') ){
    /**
     *
     * Generate a unique random string of characters
     * uses str_random() helper for generating the random string
     *
     * @param     $table - name of the table
     * @param     $col - name of the column that needs to be tested
     * @param int $chars - length of the random string
     *
     * @return string
     */
    function unique_slug($table, $title){

        $temp = str_slug(substr($title, 0, 45), '-');

        if(DB::table($table)->where('slug',$temp)->first()){
            $i = 1;
            $newslug = $temp . '-' . $i;
            while(DB::table($table)->where('slug',$newslug)->first()){
                $i++;
                $newslug = $temp . '-' . $i;
            }
            $temp =  $newslug;
        }
        

        return $temp;

    }

}

if( ! function_exists('unique_random_slug') ){
    /**
     *
     * Generate a unique random string of characters
     * uses str_random() helper for generating the random string
     *
     * @param     $table - name of the table
     * @param     $col - name of the column that needs to be tested
     * @param int $chars - length of the random string
     *
     * @return string
     */
    function unique_random_slug($table, $size = 8){

        $slug = str_random($size);

        while(DB::table($table)->where('slug',$slug)->first()){
            $slug = str_random($size);
        }

        return $slug;

    }

}