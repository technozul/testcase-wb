<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Ajax extends Controller
{
    public function __construct()
    {
        $this->start_time = microtime(true);
    }

    // generate text info
    public function generateTextInfo(Request $request) {
        $generated_chars_info = []; // text info into array object
        $clear_text = $request->special_chars ? $request->text : preg_replace('/[^A-Za-z0-9_]/', '', $request->text); // remove special characters including space if request special_chars not exist
        $split_text = str_split($clear_text); // split text
        $count_text = count($split_text); // count text
        $i = 0; // index increment

        // compare text count
        if ($count_text >= 255 || $count_text <= 2) {
            return response()->json([
                'status' => 0,
                'error' => 'Text must be: min 2 characters and max 255 characters.'
            ], 400);
        }
        
        // looping text count
        do {
            $char = $split_text[$i]; // char
            $arr_index = array_search($char, array_column($generated_chars_info, 'character')); // search index of array in result
            
            // check index of array
            if ($arr_index === false) {
                $siblings = []; // siblings char
                $index_pos = []; // index position of character
                $start_search_pos = 0; // start search position char
                $max_distance = 0; // maximum distance
                $distance = 0; // distance
                $prev_distance = 0; // previous distance
                $prev_pos = false; // previous position
                
                // loop string position
                while (($pos = strpos($clear_text, $char, $start_search_pos)) !== false) {
                    $start_search_pos = $pos + 1;
                    $distance = $pos - ($prev_pos !== false ? $prev_pos : $pos); // calculate distance, position - prev position if not false

                    if ($distance >= $prev_distance) {
                        $max_distance = $prev_distance = $distance;
                    }

                    $prev_pos = $pos;
                    $index_pos[] = $pos;
                    
                    // conditional char position
                    if ($pos == 0) {
                        $siblings['before'][] = $split_text[$pos+1];
                        $siblings['after'] = [];
                    } else if ($pos == $count_text-1) {
                        $siblings['before'] = [];
                        $siblings['after'][] = $split_text[$pos-1];
                    } else if ($pos > 0 && $pos < $count_text-1) {
                        $siblings['before'][] = $split_text[$pos+1];
                        $siblings['after'][] = $split_text[$pos-1];
                    }
                }

                // array unique siblings
                $siblings = array_map(function ($item) {
                    return array_values(array_unique($item));
                }, $siblings);
                
                // assign result
                $generated_chars_info[] = [
                    'character' => $char,
                    'count' => 1,
                    'max_distance' => $max_distance,
                    'index_position' => $index_pos,
                    'siblings' => $siblings
                ];
                
                // continue loop
                continue;
            }
            
            // increment character count
            $generated_chars_info[$arr_index]['count']++;
        } while (++$i < $count_text);

        // response
        $response = [
            'execution_time' => $this->measureExecutionTime(),
            'data' => $generated_chars_info
        ];

        // return data
        return response()->json($response, 200);
    }

    // find nth pos of digit
    public function findNthDigit(Request $request)
    {
        $number = (int) $request->number; // number

        // negative number check
        if ($number < 0) {
            return response()->json([
                'status' => 0,
                'error' => 'Please input positive number.'
            ], 400);
        }

        $i = 1; // increment index
        $nine = 9; // nine multiplier
        $number_len = strlen($number); // length of number
        $leftover = 0; // leftover of substraction
        $max_digit_group = ($number_len > 1) ? 9 : $number; // maximum digit group

        do {
            $power = 9 * pow(10, $i); // power with ^ 10
            $number -= $nine; // substract

            // check i less than number length
            if ($i+1 < $number_len) {
                $max_digit_group += $power;
            }

            // check number greater than 0
            if ($number > 0) {
                $leftover = $number;
            }

            // raise nine multiplier
            $nine = $power * ($i+1);
        } while (++$i <= $number_len);

        $num_at_n = $max_digit_group + ceil($leftover / $number_len); // number at n
        $offset = $leftover % $number_len; // get offset
        $nth = (int) strval($num_at_n)[$offset - 1]; // get char at string position

        // response
        $response = [
            'execution_time' => $this->measureExecutionTime(),
            'data' => $nth
        ];

        return response()->json($response, 200);
    }
    
    // /**
    //  * find nth pos of digit
    //  * seandainya cara diatas ga bisa, ini cara terakhir :)
    //  */
    // public function findNthDigit(Request $request)
    // {
    //     // check data type
    //     if (!is_numeric($request->number)) {
    //         return response()->json([
    //             'status' => 0,
    //             'error' => 'Input must be a number.'
    //         ], 400);
    //     }

    //     $number = (int) $request->number; // number
    //     $current_digit = 0; // current digit
    //     $pos = 1; // position

    //     // loop number input
    //     for ($i = 1; $i <= $number; $i++) {
    //         $digit_length = strlen($i); // digit length
            
    //         // check digit length
    //         if ($digit_length > 1) {
    //             $split_digit = str_split($i); // split digit

    //             // loop splited digit
    //             for ($j = 0; $j < count($split_digit); $j++) {
    //                 $pos++; // increment position

    //                 // check position
    //                 if ($pos == $number) {
    //                     $current_digit = (int) $split_digit[$j];
    //                     break;
    //                 }
    //             }
    //         } else {
    //             $current_digit = $pos = $i;
    //         }
    //     }

    //     $nth = $current_digit;

    //     // response
    //     $response = [
    //         'execution_time' => $this->measureExecutionTime(),
    //         'data' => $nth
    //     ];

    //     return response()->json($response, 200);
    // }

    // measure execution time
    public function measureExecutionTime()
    {
        return number_format(microtime(true) - $this->start_time, 10);
    }
}
