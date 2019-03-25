Test case 1:
Input argument "year": 2014
- Check if the displayed data is correct.
- Check if all the profile names are displayed and in the correct ascending order.
- Check if n/a is displayed where there is no data availiable.

Test case 2:
Input argument "year": empty or int value is equal to 0
- User is prompted with all the availiable year's that actually have populated records in the views table.
From user prompt select year 2014 and then proceed with the same checks as in Test case 1.

Test case 3:
Input argument "year": invalid (example: 2008)
- Check if error message is displayed.

Test case 4:
Input argument "year": -12312312
- Check if it returns an exception.