SOLUTION
========

Estimation
----------
Estimated: 5 hours

Spent: 3.5 hours


Solution
--------
I'm not really familiar with Symfony framework so I'm making a lot of assumptions regarding the implementation process. I'm sure there are a lot out-of-the-box functionalities inside the Symfony framework and I'm also sure that this simple task could be done in x-y ways.
But I'm a quick learner and I always try to follow good coding practices (the general and best practices by each framework).

In my opinion, a more suitable solution would be to build a RESTFull API for fetching report data. The service would accept input arguments and returned a structured JSON response object. But given that this is not a production code and you don't need to spend time on the presentational part of the app (view) it is a good fit.

If I'm not wrong - the idea here is that you have a single command report class that is responsible for all Yearly reports (Views, Orders, Sales, ...). It would be more suitable that there would be a command-object-per-report  (eg. for yearly views per profile). Each command class could use different query objects througout  it's use - byYear , byYearAndProfile, ... . This way the code is much more structured in future proofed. At this stage there is also no user authentication so each individual that has access to execute this command can view the results.


Test cases : 
1. Running  bin/console report:profiles:yearly '2016'
    Feature : Get a yearly report on views per profile
    Scanario : user runs the command and specifies input arguments (year)
    Result : The system returns the date for a given year
    Output : 

2. Running  bin/console report:profiles:yearly '2016'
    Feature : Get a yearly report on views per profile
    Scanario : user runs the command and does not specifies input arguments (year)
    Result : The system returns a message to the user explaining that the function has a mandatory argument year 
    Output :  Not enough arguments (missing: "year").
 
3. Running  bin/console report:profiles:yearly '2016'
    Feature : Get a yearly report on views per profile
    Scanario : user runs the command and an QUERY EXCEPTION ocurres stoping the execution.
    Result : The system returns a message to the user explaining that there is a problem with the query passed to the report 
    Output :  EXCEPTION : Exception ocurred while executing the query :

4. Runnin bin/console report:profiles:yearly '2016'
    Feature : Get a yearly report on views per profile
    Scanario : user runs the command and an unknown program error ocurres stoping the execution.
    Result : The system returns a message to the user explaining that there is a problem with the query passed to the report 
    Output :  Something unexpected happened!! OH-My-OH-My. : You have requested a non-existent service "database_connectionww".
