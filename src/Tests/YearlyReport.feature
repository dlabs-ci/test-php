Feature: Allow defining year when executing the report:profiles:yearly command

  Scenario: Monthly report is required and user executes the report:profiles:yearly command
    Given that there is no year defined
    WHEN I execute the Yearly Views report
    THEN I expect to see a monthly breakdown of the total views per profiles for all available years

  Scenario: Monthly report is required and user executes the report:profiles:yearly 2018 command
    Given that year is defined
    WHEN I execute the Yearly Views report
    THEN I expect to see a monthly breakdown of the total views per profiles for that year only

  Scenario: Monthly report is required and user executes the report:profiles:yearly 2200 command
    Given invalid year is defined
    WHEN I execute the Yearly Views report
    THEN I expect to see error message with the available years range I can use

  Scenario: Monthly report is required and user executes the report:profiles:yearly 2016-2018 command
    Given valid year range is defined
    WHEN I execute the Yearly Views report
    THEN I expect to see a monthly breakdown of the total views per profiles for the given year range

  Scenario: Monthly report is required and user executes the report:profiles:yearly 2020-2018 command
    Given invalid year range is defined
    WHEN I execute the Yearly Views report
    THEN I expect to see error message