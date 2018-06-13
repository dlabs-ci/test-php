
Feature: User executes Test Data Reset command

  Scenario: Application is up and running
   Given the app is correctly connected with the database
    When the User executes Test Data Reset command
    Then the existing data should be erased from database
     And the app should output progress bar indicating the progress regarding filling the database
     And the database should be filled with historical data when the progress bar reaches the end