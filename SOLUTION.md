SOLUTION
========

Estimation
----------
Estimated: 4 hours

Spent: 3 hours (+ 1 hour the solution and improvement description)

I've given myself some buffer when making an estimate, primarily on the account
of unfamiliar development environment and no prior experience with the Symfony framework.

This estimate was provided with a simplifed solution in mind. Ideally I would prefer more
time research the Symfony functionalities (Commands, DependencyInjection, etc..), and to back up the solution with implemented unit tests.

Solution
--------

My approach was to split the functionality into smaller 'service' classes that can be more easily tested and mocked when using the unit tests.

The command functionality was extended to accept the 'year' attribute which is used to load the 
data for that year. If no year is provided the application default to current year. 

I've created a simple 'loader' class (YearlyViewsDataLoader, abstraction of ViewsDataLoader) which 
accepts the arguments (year at this point), builds the required query and return the result data.

To avoid too data parsing by PHP scripts, I've tried to get the query result output as close
as possible to the expected example output.

This data is passed on the 'renderer' class (in this case the ConsoleViewsDataRenderer) which parses
the data and outputs it in a console table.

Improvements
-------- 

There are possibilites for perfomance improvement. It's harder to anticipate any bottlenecks
without knowing how the solution would work as a part of a big picture or what the actual data size would be, but here are some possibilities:

- Chunking the query result when data sets are too large to avoid issues with memory performance.
- Consider delegating the functionality to a job, so the report will be generated when the server load is lighter.
- Implementing a caching system, especially for older data which (I assume) no longer changes.
- Adding an option to specify a set of profiles for which the data should be loaded, to avoid loading data for all existing profiles.
- Similary, going down a level, specifying a range of months for which the data should be loaded.


The 'product' could be expanded by enabling more report output formats (e.g. JSON, CSV...), adding an option to send generated reports to email or deploy them to external storage or to create reports automatically on the set time interval (end of the month, end of year).