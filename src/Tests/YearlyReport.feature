GIVEN that there is 0 profile data available
WHEN I execute the Yearly Views report
THEN I expect to see a blank table with Profile header

GIVEN that there isn't input option "year" set
WHEN I execute the Yearly Views report
THEN I expect to see a monthly breakdown of the total views per profiles for active year

GIVEN that there is option "year" set
AND year is invalid integer
WHEN I execute the Yearly Views report
THEN I expect to see error text: "Year must be a valid integer"


