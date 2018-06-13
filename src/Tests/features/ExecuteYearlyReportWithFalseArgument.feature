
Feature: User executes Yearly Report command with argument that is not numeric

  Scenario: Database is filled with historical data
   Given the database has mixed records for default year
     And the app's default year is 2016
    When the User executes Yearly Report command with argument that is not numeric, eg. "php bin/console report:profiles:yearly test123"
    Then the app should output all the profiles rows
     And the app should output "n/a" or views numbers for every month in the year for respective profile row