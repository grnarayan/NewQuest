# NewQuest
#### Naomi Douglass
#### Cat Schaller


This is a task for New Quest Cat Schaller - Done by Roy Narayan with the help of stack overflow and Magento 2 source code
Magento module for Alphonso Toys

### Use Composer to Install the package
    Execute php bin/magento setup:di:compile to compile the XML files
    Execute php bin/magento cache:clean to clean the cache

# Configuration
Maximum number of items that can be purchased within a 30 day period setting can be specified at :

##### Stores > Configuration > General > Alphonso Store Setting > Product Limit

##

### To get Product limit via Rest API 

##### https://\<domain name\>/rest/V1/alphonso/toys/cartlimit

### Bugs / Improvements
    - Customer can update cart with more than 3 items
    - Prevents the customer from navigating to checkout but still am trying to give the message




