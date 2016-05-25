<?php
	/*
	 *	mean = mean year of shows in user list (AVG in MySQL)
	 *	stdv = standard deviation of shows in user list (STDDEV in MySQL)
	 *
	 *	for all shows:
	 *		get show year
	 *		normalize show year ((z = show year - mean)/stdv)
	 *		if abs(z) > 4
	 *			return 0;
	 *		else
	 *			return 10*erf(abs(z)) (10 * value in lookup table)
	 */
?>