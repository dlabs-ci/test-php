SOLUTION
========

Estimation
----------
Estimated: 1 hours

Spent: 2 hours


Solution
--------
Actual coding was under 1 hour. Rest of the time was spent on writeing test and code comments. 
I spent a lot of time googling doctrine and symphony syntact since I haven't used netther before.

Test cases
----------

``` gherkin

Feature: Generate report
  Report sould not be generated if the user does't provide a year.
  Report sould not be generated if the user does't provide a valid year.
  Report sould not be generated we don't have historical data for provided year.


Scenario: User doesn't provide a year.
  GIVEN that there is historical data available
  AND I haven't provided a year as the second argument for the command
  When I execute the Yearly Views report
  Then Error should be displayed

Scenario: User doesn't provide a valid year.
  GIVEN that there is historical data available
  AND I have provided a "aaa" as the second argument for the command.
  When I execute the Yearly Views report
  Then Error should be displayed  

Scenario: User doesn't provide a valid year.
  GIVEN that there is historical data available
  AND I have provided a 2000 as the second argument for the command.
  When I execute the Yearly Views report
  Then Error should be displayed 

Scenario: User doesn't provide a valid year.
  GIVEN that there is historical data available
  AND I have provided a 2020 as the second argument for the command.
  When I execute the Yearly Views report
  Then Error should be displayed 

Scenario: User provides a year that we don't have data for all months.
  GIVEN that there is historical data available
  AND I have provided a 2014 as the second argument for the command.
  When I view the Yearly Views report
  Then Data for months Jan - Aug should be displayed as "n/a"   

Scenario: User provides a year that we don't have data for all months.
  GIVEN that there is historical data available
  AND I have provided a 2017 as the second argument for the command.
  When I view the Yearly Views report
  Then Data for months Mar - Dec should be displayed as "n/a" 

Scenario: User provides a year that we have data for all months.
  GIVEN that there is historical data available
  AND I have provided a 2015 as the second argument for the command.
  When I view the Yearly Views report
  Then All months should have numeric data
  Then Profiles should be sorted in alphabetical order.

```