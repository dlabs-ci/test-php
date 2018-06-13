SOLUTION
========

Estimation
----------
Estimated: 16 hours

Spent: 10 hours


Solution
--------
Having in mind that this project is only one part of a major project and that there are no basic dependencies (such as ORM) inside the system, I decided to continue in that direction.
I already have a connection to the database in the ReportYearly command, so I recalled that connection in the Profile Class constructor.
The Profile Class represents a model that will communicate with the database. Within this class, all queries related to the database are defined, so I defined, within this class, the historicalDataQuery that filters all the data in the required way.
After that, I installed the "behat" dependency so I could run the tests. Tests are stored in the historical_data.feature file.
I would improve the current status by setting up ORM, creating entities and repositories for Profile and Views.
After that, I would create a relationship between Views and Profiles. I would create a query for filtering database data per each year, and I would also create a query for sorting the data by alphabet. 
I would change the current flow of the ReportYearly command, in the way where I would take only the year for which data is being retrieved, and if that year is less than the current one, then I would call the data from the cache because that is data that will not be changed anymore.
