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

### To get Product limit via Rest API (For bonus points)

##### https://\<domain name\>/rest/V1/alphonso/toys/cartlimit

### Bugs / Improvements
    - Customer can update cart with more than 3 items, if the customer updates Cart quantity
    - Wanted to prevents the customer from navigating to checkout by introducing a Controller predispatch
        observer but ran into difficulties trying to show the message (Not a good customer experience)
    - Tests don't work - I have committed unit test to show that I can do it - but it's Sunday afternoon
        and want to stop, nevertheless I can do it - but spent too much time trying to do the controller predispatch.

#### Want to upload this to Packagist so composer can be used to install so am gonna dedicate bit of time to that - as the last time, I had deployed to packagist was sometime ago 

