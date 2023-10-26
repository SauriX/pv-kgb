<?php
function burbuja($array)
{
    for($i=1;$i<count($array);$i++)
    {
        for($j=0;$j<count($array)-$i;$j++)
        {
            if($array[$j]['id']>$array[$j+1]['id'])
            {
                $k=$array[$j+1];
                $array[$j+1]=$array[$j];
                $array[$j]=$k;
            }
        }
    }
 
    return $array;
}
 
$arrayA=array(array('id'=>5,'n'=>'e'),array('id'=>9,'n'=>'i'),array('id'=>4,'n'=>'d'),array('id'=>7,'n'=>'g'),array('id'=>3,'n'=>'c'),array('id'=>8,'n'=>'h'),array('id'=>2,'n'=>'b'),array('id'=>1,'n'=>'a'),array('id'=>6,'n'=>'f'));
 
echo "Valores iniciales<br>";
for($i=0;$i<count($arrayA);$i++)
    echo $arrayA[$i]['n']."\n";
 
$arrayB=burbuja($arrayA);
 
echo "<br><br>Valores ordenados<br>";
for($i=0;$i<count($arrayB);$i++)
    echo $arrayB[$i]['n']."\n";
?>