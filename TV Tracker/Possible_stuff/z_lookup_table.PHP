<?php include "base.php"?>
<?php
	/* Error function for normal distribution */
	function erf($x)
	{
        $pi = 3.1415927;
        $a = (8*($pi - 3))/(3*$pi*(4 - $pi));
        $x2 = $x * $x;

        $ax2 = $a * $x2;
        $num = (4/$pi) + $ax2;
        $denom = 1 + $ax2;

        $inner = (-$x2)*$num/$denom;
        $erf2 = 1 - exp($inner);

        return sqrt($erf2);
	}

	function erfc($x)
	{
		return 1-erf($x);
	}
	
	mysql_db_query("CREATE TABLE z_lookup (
					z_val float(1,2),
					euclid float,					 
					PRIMARY KEY (z_val) ");
					
	float $x = 0;
	while($x < 3.56)
	{
		/* Add value to our z_lookup table */
		mysql_db_query("INSERT INTO z_lookup
						VALUES (".$x.",". erfc(abs($x)) .")");
		$x += 0.01;
	}
?>