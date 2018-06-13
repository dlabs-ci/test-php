
Feature: User executes Yearly Report command without existent data for given year

  Scenario: Database is filled with historical data
   Given the database doesn't have records for year 2010
    When the User executes Yearly Report command for 2010 year, eg. "php bin/console report:profiles:yearly 2010"
    Then the app should output all the profiles rows
     And the app should output "n/a" for every month in the year for respective profile row