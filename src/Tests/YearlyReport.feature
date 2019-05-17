Story: 
GIVEN that there is historical data available
WHEN I execute the Yearly Views report
THEN I expect to see a monthly breakdown of the total views per profiles

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to have the profiles names listed in alphabetical order

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to see "n/a" when data is not available

Test cases: 
1. 
Scenario: A properly formated value for the year is supplied
Expected result: Report is shown that is alphabetically ordered with view count groupped by month

2. 
Scenario: A year is supplied where there is missing data for months
Expected result: Same as first case, but cells have "n/a" displayed instead of view count

3.
Scenario: No year supplied 
Expected result: Application throws an error saying that year should be an integer greater than 0

4. 
Scenario: A year is supplied for which there is no data
Expected result: Empty table is shown

5.
Scenario: A year is supplied but its value is less than 0
Expected result: Application throws an error saying this is invaldi value