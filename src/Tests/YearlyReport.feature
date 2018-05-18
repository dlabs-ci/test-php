Feature: Test yearly report
  Can see yearly report
  As a user of the system

  Scenario: View yearly report
    Given Year is given
    And There is data for given year
    When I execute the Yearly Views report
    Then I expect to see a monthly breakdown of the total views per profiles

    Given Year is given
    And There is data for given year
    And There is missing profile for recorder view
    When I view the Yearly Views report
    Then I expect to see a monthly breakdown of the total views per profiles with profile name as N/A

    Given Year is given
    And There is data for given year
    And There is missing data for X months for a profie
    When I view the Yearly Views report
    Then I expect to see a monthly breakdown of the total views per profiles with N/A shown in that row

    Given Year is given
    And There is no data for given year
    When I execute the Yearly Views report
    Then I expect to see a message telling me there's no data for given year

    Given Year is before 2000
    When I execute the Yearly Views report
    Then I expect to see error telling me there's no record before 2000

    Given Year is in the future (current year + )
    When I execute the Yearly Views report
    Then I expect to see error telling me year can't be in the futute

