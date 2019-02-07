Feature Get profiles views
  Scenario:
    Given I have data for 5 users in 2000 year
    When I ask for report dated by 2000
    Then I have 5 rows with user data

  Scenario:
    Given I have user's profile views for 2010 year,
    When I receive a response for 2010
    Then I all sum of all user's request for each month

  Scenario:
    Given I receive reports from table
    When I don't have views for specific user in for January
    And I have report for same user for another month in the same year
    Then I should receive N/A for user in January

  Scenario:
    Given I receive reports from table
    When I receive table with 2 or more profiles
    Then I should be sorted by Profile Name

  Scenario:
    Given I receive reports from table
    When I have views for some year
    Then I should receive 12 month cells for the selected year

  Scenario:
    Given I receive reports from table
    When I have views for some month in a year
    Then I should see month views in appropriate cell

  Scenario:
    Given I receive reports from table
    When I input is invalid
    Then I should receive an error appropriate message
