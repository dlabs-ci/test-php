
Feature: User executes Yearly Report command with existent data for given year

  Scenario: Database is filled with historical data
   Given the database has records for year 2015
     And the data for every month in given year are existent
    When the User executes Yearly Report command for 2015 year, eg. "php bin/console report:profiles:yearly 2015"
    Then the app should output all the profiles rows
     And the app should output all the historical data divided by months in the year for respective profile row