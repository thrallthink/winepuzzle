<?php 

ini_set('max_execution_time',0);
ini_set('memory_limit',-1);


class WinePuzzle
  {

    public $fileName='';  // stores file name
    public $wineSold=0;   // wine bottle sold counter

     public function  __construct($fileName)
     {
        $this->fileName=$fileName;
     }

     public function SolvePuzzle()
     {
        try
        {
          if (!file_exists($this->fileName))  // checking if file exists, if not then throw exception
            {
                throw new Exception($this->fileName." not found, please provide proper input file.");
            }
          $fileOpenRead = fopen($this->fileName,"r");
        }catch(Exception $e)
        {
          return $e->getMessage();
        }
        $wineChoice=array();   //initializing empty array to store the wine choices 
  
        while(! feof($fileOpenRead))  //reading till end of the file
        {
          $line = fgets($fileOpenRead);   //reading line by line
          $explodeLine=explode("\t",$line); 
          $person=$explodeLine[0];
          $wine=$explodeLine[1];

          $wineChoice[$wine][]=$person;

        } //end of while feof

        fclose($fileOpenRead); // closing file after reading the data

        // $wineArray=array_keys($wineChoice);

        $outputList=array();   // initializing the output array : contains solved data 
        $wineSoldOut=array();  // Initializing WineSoldOut array : contains list of wine bottle sold out
                  
        foreach($wineChoice as $winekey=>$personvalue)  // key is wine name, value is person array
          {
            if(!array_key_exists($winekey,$wineSoldOut)) // condition added to reduce unecessary looping if wine already sold out
            {
              foreach($personvalue as $personkey=>$personnamevalue)  
              {
                if(!isset($outputList[$personnamevalue])  && !array_key_exists($winekey,$wineSoldOut))
                  {
                    $outputList[$personnamevalue][]=$winekey;
                    $wineSoldOut[$winekey]=$winekey;
                    $this->wineSold++;
                  }elseif(isset($outputList[$personnamevalue]) && count($outputList[$personnamevalue])<3 && !array_key_exists($winekey,$wineSoldOut))
                  {
                    $outputList[$personnamevalue][]=$winekey;
                    $this->wineSold++;
                    $wineSoldOut[$winekey]=$winekey;
                  }
              }  // end of inner foreach loop
            } // end of if array_key_exists function
          } // end of foreach loop of $wineChoice

          if(count($outputList)>0)
            return $this->CreateOutput($outputList);
          else
            return "No Output to Display";

     } // end of SolvePuzzle Function

     public function CreateOutput($outputList) // passing output array for displaying/writing solution to web/file
     {
      $personCount=array(); // for getting the list of person and the bottle count each received

      $outputFile = fopen("outputFile.txt", "w");  //opening file in write mode to make sure to have fresh output

      $outputCountText="Total Wine Bottle Sold : ".$this->wineSold."\n";
      fwrite($outputFile, $outputCountText);  //Total Wine Bottle Sold : 299239  - for first input file   
      foreach (array_keys($outputList) as  $person) 
      {
        foreach ($outputList[$person] as  $wine) 
        {
          // if(isset($personCount[$person]))
          //   $personCount[$person]=$personCount[$person]+1;
          // else
          //   $personCount[$person]=1;
          fwrite($outputFile, $person."\t".$wine);
        }
      }

      fclose($outputFile); // closing output file after writing solution
      return $outputCountText;

     }// end of CreateOutput Function

  }

$winepuzzle = new WinePuzzle("person_wine_3.txt");
$output=$winepuzzle->SolvePuzzle();
echo $output;

  

//awk -F "\t" '!seen[$2]++' outputfile.csv >> outputfilefiltered.csv

?>