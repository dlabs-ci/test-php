SOLUTION
========
Before I've started the assignment, I discovered that the assignment has a serious vulnerability. In the process of an assignment's delivery, a candidate have to fork the code from the public repository.  The number of forks is displayed in the main repository. 
When you click on the button with the number of forks, you can go to the public repositories of the previous candidates who performed this task.

Thus, after clicking, I saw 17 repositories, and some of them contained solutions for this assignment. Please take a look
https://github.com/dlabs-ci/test-php/network/members

Vulnerability itself does not reveal the level of technical expertise of the candidate but can give him good tips

Considering the discovered vulnerability I have decided to rewrite this assignment from scratch.

What was done.
1. Symfony was reinstalled from  symfony/skeleton repository
2. ORM, migrations, entity-maker, validator, fixtures packages were added.
3. DB has been modified. Names of fields and tables have been changed. changes can be run through migrations.
4. Entities and related repositories for the profile and profile views were added.
5. The repository for the profile views has been modified in order to obtain return the data according to the provided query.  Also, this repository is scalable, so new query options can be added easily. 
6. For the data delivery, I've created a Report service.  Report Service should receive search arguments and request type (in our case console request)
Then service receives data from repository and map received data to an appropriate array. 
This service also scalable so different type of requests can be added (PDF, CSV, JSON e.tc),
7. Data loader was changed from DB query to fixtures. (command php bin/console test: data: reset)

Time Estimate:
1 - 2 days
Before I've started, I had no experience with Symfony and Doctrine, and I wanted to solve the problem using this particular environment. 

Time Spent: 
14 hours
