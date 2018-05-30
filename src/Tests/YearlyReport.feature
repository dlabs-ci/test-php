Feature: Get views for profiles

  Scenario: There is views for all profiles
   Given there are profiles in database
     And each user has some views for each month
    When I execute the Yearly Views report
    Then I expect to see a monthly breakdown of the total views per profiles
     And all values are numbers


   Scenario: There is no views for each month for all profiles
	   Given there are profiles in database
	     And each user do not has views for each month
	    When I execute the Yearly Views report
	    Then I expect to see a monthly breakdown of the total views per profiles
	     And where is no views i see n/a


    Scenario: There is no views for all month for all profiles
	   Given there are profiles in database
	     And each user do not has any views for each month
	    When I execute the Yearly Views report
	    Then I expect to see a monthly breakdown of the total views per profiles
	     And i see n/a for all months for each profile


	Scenario: There is no profile for search option
	   Given there are profiles in database
	    When I execute the Yearly Views report
	    Then I expect to see "There is no profile with this name in database!"

	Scenario: There is some views for each month for profile
	   Given there are profiles in database
	     And user do not has views for each month
	    When I execute the Yearly Views report
	    Then I expect to see a monthly breakdown of the total views per profile
	     And i see n/a for all months with no views


	Scenario: Selected year is in future
	    When I execute the Yearly Views report
	    Then I expect to see "Wrong year. The selected year is in future!"