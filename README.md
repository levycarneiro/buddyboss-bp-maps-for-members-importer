This script is meant for the following use-case:

- You use the BuddyBoss theme/plugin on your site
- You have additional fields for address, such as:
-   City = Boston
-   State = MA
-   Country = USA
- You installed the BP Maps for Members plugin
- This maps plugin doesn't use the existing data from those 3 fields.
- It uses only a Location-type field, which has a geocode (such as "42.40081989999999,-70.749455").
- So one needs to convert the data from the 3 fields into a geocode that the plugin can use.

We have 2 scripts:

- export-users-addresses-as-csv.php: exports the current address data of all active users, into a CSV file.
- get-geocode.php: it reads the CSV file from the previous script, sends the city/state/country data to the Google Maps API and returns the geocode for each user. It spits out 2 SQL queries for each user: one for adding the geocode to the DB (such as "42.40081989999999,-70.749455") and another for adding the formatted address such as "Boston, MA, USA".

