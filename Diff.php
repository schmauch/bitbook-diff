<?php

namespace schmauch/bitbookDiff;

class Diff {
 
    // slightly modified version of https://www.bitbook.io/how-to-diff-two-strings-in-php-and-show-in-html/
 	
    public static function diffArray($oldArray, $newArray){
        
        print_r($oldArray);
        print_r($newArray);
        
        $matrix = array();
        $maxlen = 0;
        
        foreach($oldArray as $oldIndex => $oldValue){
        		echo 'Wort :'.$oldValue."\n";
            $newKeys = array_keys($newArray, $oldValue);
            
            echo 'old index: '.$oldIndex."\n";
            echo "new keys: \n";
            print_r($newKeys);
            
            foreach($newKeys as $newIndex){
                
                if(isset($matrix[$oldIndex - 1][$newIndex - 1])) {
                    $matrix[$oldIndex][$newIndex] = $matrix[$oldIndex - 1][$newIndex - 1] + 1;
                } else {
                    $matrix[$oldIndex][$newIndex] = 1;
                }

                if($matrix[$oldIndex][$newIndex] > $maxlen){
                    $maxlen = $matrix[$oldIndex][$newIndex];
                    $oldMax = $oldIndex + 1 - $maxlen;
                    $newMax = $newIndex + 1 - $maxlen;
                }
                
                echo "Matrix:\n";
                print_r($matrix);
            }
        }
        
        if($maxlen == 0) {
            return array(array('d'=>$oldArray, 'i'=>$newArray));
        } 
        
        return array_merge(
            self::diffArray(array_slice($oldArray, 0, $oldMax), array_slice($newArray, 0, $newMax)),
            array_slice($newArray, $newMax, $maxlen),
            self::diffArray(array_slice($oldArray, $oldMax + $maxlen), array_slice($newArray, $newMax + $maxlen)));
    }
 
    public static function htmlDiff($old, $new){
        
        $output = '';
        
        $oldArray = explode(' ', $old);
        $newArray = explode(' ', $new);

        $diff = self::diffArray($oldArray, $newArray);
        
        foreach($diff as $k){
            if(is_array($k)){
            	if(!empty($k['d'])) {
            		$output .= '<del>'.implode(' ',$k['d']).'</del>';
            	}
            	if(!empty($k['i'])) {
            		$output .= '<ins>'.implode(' ',$k['i']).'</ins>';
            	}
            }else{
                $output .= $k . ' ';
            }
        }
        
        return $output;
    }
 
}
