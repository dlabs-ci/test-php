GIVEN that there is historical data available
WHEN I execute the Yearly Views report
THEN I expect to see a monthly breakdown of the total views per profiles

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to have the profiles names listed in alphabetical order

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to see "n/a" when data is not available


# Added test cases

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to see "n/a" in monthly column when data for that month is not available

GIVEN that there is historical data available
WHEN I execute the Yearly Views report without the year argument
THEN I expect to see a monthly breakdown of the total views per profiles for current year

GIVEN that there is historical data available
WHEN I view the Yearly Views report with the year argument
THEN I expect to see monthly breakdown of the total views per profiles for that year

GIVEN that there is historical data available
WHEN I view the Yearly Views report with out of range year (e.g. 10000)
THEN I expect to see "n/a"

GIVEN that there is historical data available
WHEN I view the Yearly Views report with year argument where profiles have no views (e.g. 2020)
THEN I expect to see "n/a"


# Future test cases (not yet implemented)

GIVEN that there is historical data available
WHEN I view the Yearly Views report with invalid year argument (e.g. 'foo' string instead of numeric)
THEN I expect to see "n/a"

GIVEN that there is historical data available
WHEN I view the Yearly Views report with profiles set argument
THEN I expect to see monthly breakdown of the total views per profiles for those profiles only

GIVEN that there is historical data available
WHEN I view the Yearly Views report with empty profiles set argument
THEN I expect to see "n/a"