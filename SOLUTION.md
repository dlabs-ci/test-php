SOLUTION
========

Estimation
----------
Estimated: 3 hours

Spent: 2 hours


Solution
--------
I aggregate sum of all the views broken down by profiles and months for given year.
If there are records for that year I get list of all profiles. Then I iterate thru both of array
twice. This way I can get all the missing records from both tables and address them accordingly.

Once all the data is merged I sort the table and display it to output.

Observations
------------
* I simplified a bit with iterating thru all the data when I'm checking for missing profiles.
That means 12 loops for each user. I could add additional MySQL query which would get unique profile_ids
from views table and cross reference profiles with that result,
* I would move search_array_by_values to helpers function..
* In real life scenario it would be smart to filter out all the profiles without any views in whole year.
With current solution table could get bloated with lots of N/A
* This was my first time writing Gherkin tests :O :)

