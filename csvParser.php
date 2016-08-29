<?php
/* MIT License
*
*      Copyright (c) 2016 Rutvik Rajagopal
*
*    Permission is hereby granted, free of charge, to any person obtaining a copy
*    of this software and associated documentation files (the "Software"), to deal
*    in the Software without restriction, including without limitation the rights
*    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
*    copies of the Software, and to permit persons to whom the Software is
*    furnished to do so, subject to the following conditions:
*
*    The above copyright notice and this permission notice shall be included in all
*    copies or substantial portions of the Software.
*
*    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
*    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
*    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
*    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
*    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
*    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
*    SOFTWARE.
*/

class csvParser {
    public $csvToArray;


    public function checkUrl() {
        try {
            if (isset($_GET['exch']) && isset($_GET['sym']) && !empty($_GET['exch']) && !empty($_GET['sym'])) {
                //filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
                $sym = filter_input(INPUT_GET, 'sym', FILTER_SANITIZE_SPECIAL_CHARS); //optimised get setter
                $exch = filter_input(INPUT_GET, 'exch', FILTER_SANITIZE_SPECIAL_CHARS);
                $this->symbol = $sym;
                $this->exchange = $exch;
                return 1;
            } else {
                throw new Exception('Unable to check URL');
            }
          } catch (Exception $urlCheck) {
            echo $urlCheck;
          }
    }

    //converting the csv to an array
    public function csvToArray() {
        try {
            if ($this->checkUrl() == 1) {
                $url = 'https://www.quandl.com/api/v3/datasets/' . $this->exchange . '/' . $this->symbol . '.csv'; //quandl url
                $this->csvToArray = array_map('str_getcsv', file($url)); //convert csv to an array map
                return $this->csvToArray; //return the array map 
            } else {
                throw new Exception('Unable to convert CSV to Array');
            }
        } catch (Exception $errCsv) {
            echo $errCsv;
        }
    }
  

    // Pull dates
    public function getDate() {
        $dates = array(); //create an array
        for ($i = 1; $i < count($this->csvToArray); $i++) {
            //  echo 'Date: ' .$this->csvToArray[$i][0]. '</br>' ;
            $dates[] = $this->csvToArray[$i][0]; //appending values to the array
        }
        return $dates;
    }

    //Get Opening Prices for the day
    public function getOpen() {
        $opens = array(); //create an array
        for ($i = 1; $i < count($this->csvToArray); $i++) {
            $opens[] = $this->csvToArray[$i][1]; //append values to the array
        }
        return $opens;
    }

    //Get closing prices for the day
    public function getClose() {
        $closes = array(); //create  a new array

        for ($i = 1; $i < count($this->csvToArray); $i++) {
            $closes[] = $this->csvToArray[$i][5]; // append values to the array
        }
        return $closes;
    }

    //get the daily highest stock price
    public function getHigh() {
        $highs = array(); //create an array

        for ($i = 1; $i < count($this->csvToArray); $i++) {
            $highs[] = $this->csvToArray[$i][2]; // append values to the array
        }
        return $highs;
    }

    //get the daily lowest stock price
    public function getLow() {
        $lows = array(); //create an array

        for ($i = 1; $i < count($this->csvToArray); $i++) {
            $lows[] = $this->csvToArray[$i][3]; //append values to the array
        }
        return $lows;
    }

    //get the last traded price of the day
    public function getLast() {
        $lastTrades = array(); ///create an array

        for ($i = 1; $i < count($this->csvToArray); $i++) {
            $lastTrades[] = $this->csvToArray[$i][4]; //append values to the array
        }
        return $lastTrades;
    }

    public function __construct() {
        $this->csvToArray();
        //loop through the returned arrays to print out all values in a line
        for ($i = 0; $i < count($this->csvToArray); $i++) {
            echo $this->getDate()[$i] . '  ';
            echo $this->getOpen()[$i] . '  ';
            echo $this->getClose()[$i] . '  ';
            echo $this->getHigh()[$i] . '  ';
            echo $this->getLow()[$i] . '  ';
            echo $this->getLast()[$i];
            echo '</br> ';
        }
    }

}

//New instance
echo $csvUrl;
$csv = new csvParser;
