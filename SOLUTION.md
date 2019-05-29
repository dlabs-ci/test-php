SOLUTION
========

Estimation
----------
Estimated: 2 hours

Spent:  hours


Solution
--------
While the solution is far from optimal in case we are using a larger amounts of data,
due to the format in which the arrays have to be for the function to work it is the easiest
solution simply due to the fact, that should somebody need to refactor or fix any errors
in code all the componenst are quite minimalistic and simple. I suppose that the db driver
enables getting values directly from db in strings/integers instead of arrays, and that could
also reduce the overhead a bit, but since I have never worked with this db driver before,
I did not know where to look up for the documentation. If this were to be done in Symfony
I would have prefered to work with entities and relations, and that could make the work a bit 
simpler, yet could also complicate it.

Tests 
-------

Tests that can be preformed for this

``` gherkin
GIVEN that there is historical data available
WHEN I execute the Yearly Views report
THEN I expect to see a monthly breakdown of the total views per profiles

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to have the profiles names listed in alphabetical order

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to see "n/a" when data is not available

```
