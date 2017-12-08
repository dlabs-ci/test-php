Feature: In order to work with year users views data
  As a manager
  I need to be able to do generate yearly views report

  Background:
    Given the database has been loaded and contains data from 2014-01 to 2017-06 for 5 users

  Scenario: Generate report for the fully filled year
    When I run "bin/console report:profiles:yearly" with "2014" year parameter
    Then Profile column header should contain "2014" year
    And the response should contain "5" data row
    And the response should contain "12" non-empty data column

  Scenario: Generate report for the current year
    When I run "bin/console report:profiles:yearly" command without parameters
    Then Profile column header should contain "current" year
    And the response should contain "5" data row
    And the response should contain "6" non-empty data column
    And the response should contain "6" empty data column filled with n/a

  Scenario: Generate report for further date
    When I run "bin/console report:profiles:yearly" command with "2100" year parameter
    Then Profile column header should contain "2100" year
    And the response should contain "5" data row
    And the response should contain "12" empty data column filled with n/a

  Scenario: Generate report for too old date
    When I run "bin/console report:profiles:yearly" command with "1950" year parameter
    Then Profile column header should contain "1950" year
    And the response should contain "5" data row
    And the response should contain "12" empty data column filled with n/a

  Scenario: Generate report for the fully filled year
    Given the database created but it is empty
    When I run "bin/console report:profiles:yearly" command without parameters
    Then Profile column header should contain "2017" year
    And the response should contain "0" data rows
