<?php

    function formatListForSentence($values_array){
        $values_set = [];

        foreach($values_array as $key=>$value){
            if($value){
                array_push($values_set, $key);
            }
        }
        $value_string = implode(', ', $values_set);
        $last  = array_slice($values_set, -1);
        $first = join(', ', array_slice($values_set, 0, -1));
        $both  = array_filter(array_merge(array($first), $last), 'strlen');
        $value_string = join(' and ', $both);

        return $value_string;
    }
